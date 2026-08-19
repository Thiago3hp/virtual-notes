<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import chamados from '@/routes/chamados';
import type { Chamado, ChamadoStatus } from '@/types';

const props = defineProps<{
    chamados: Chamado[];
}>();

const emit = defineEmits<{
    edit: [chamado: Chamado];
}>();

const statusLabel: Record<ChamadoStatus, string> = {
    aberto: 'Aberta',
    em_andamento: 'Em Andamento',
    fechado: 'Concluída',
};

const busca = ref('');
const statusFiltro = ref<ChamadoStatus | ''>('');

const chamadosFiltrados = computed(() => {
    const termo = busca.value.trim().toLowerCase();

    return props.chamados.filter((chamado) => {
        const bateBusca =
            !termo ||
            chamado.problema.toLowerCase().includes(termo) ||
            chamado.setor.toLowerCase().includes(termo);

        const bateStatus = !statusFiltro.value || chamado.status === statusFiltro.value;

        return bateBusca && bateStatus;
    });
});

function formatPrazo(prazo: string | null) {
    if (!prazo) return '—';
    const [year, month, day] = prazo.split('-');
    return `${day}/${month}/${year}`;
}

function isOverdue(chamado: Chamado) {
    if (!chamado.prazo || chamado.status === 'fechado') return false;
    return new Date(chamado.prazo) < new Date(new Date().toDateString());
}

function destroyChamado(chamado: Chamado) {
    if (!confirm(`Excluir o chamado #${chamado.id} (${chamado.problema})?`)) return;
    router.delete(chamados.destroy(chamado.id).url);
}
</script>

<template>
    <div class="chamado-table">
        <div class="chamado-table__search">
            <input
                v-model="busca"
                class="search-input"
                placeholder="Buscar por título ou cliente..."
            />
            <select v-model="statusFiltro" class="search-select">
                <option value="">Todos os status</option>
                <option value="aberto">Aberta</option>
                <option value="em_andamento">Em Andamento</option>
                <option value="fechado">Concluída</option>
            </select>
            <span class="search-count">{{ chamadosFiltrados.length }} de {{ props.chamados.length }}</span>
        </div>

        <div class="chamado-table__scroll">
            <table>
                <thead>
                    <tr>
                        <th class="col-id">#</th>
                        <th>Título</th>
                        <th>Cliente</th>
                        <th>Técnico</th>
                        <th class="col-prazo">Prazo</th>
                        <th class="col-prioridade">Prioridade</th>
                        <th class="col-status">Status</th>
                        <th class="col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="chamado in chamadosFiltrados" :key="chamado.id">
                        <td class="col-id">{{ chamado.id }}</td>
                        <td class="cell-title">{{ chamado.problema }}</td>
                        <td class="cell-muted">{{ chamado.setor }}</td>
                        <td class="cell-muted">{{ chamado.tecnico_nome || '—' }}</td>
                        <td class="col-prazo" :class="{ 'is-overdue': isOverdue(chamado) }">
                            {{ formatPrazo(chamado.prazo) }}
                        </td>
                        <td class="col-prioridade">{{ chamado.prioridade }}</td>
                        <td class="col-status">
                            <span class="badge" :class="`badge--${chamado.status}`">
                                {{ statusLabel[chamado.status] }}
                            </span>
                        </td>
                        <td class="col-actions">
                            <button class="btn btn--edit" @click="emit('edit', chamado)">
                                Editar
                            </button>
                            <button class="btn btn--delete" @click="destroyChamado(chamado)">
                                Excluir
                            </button>
                        </td>
                    </tr>

                    <tr v-if="!chamadosFiltrados.length">
                        <td colspan="8" class="empty">
                            {{ props.chamados.length ? 'Nenhum chamado corresponde à busca.' : 'Nenhum chamado registrado ainda.' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
.chamado-table {
    --ct-bg: hsl(222 50% 5%);
    --ct-bg-hover: hsl(222 45% 9%);
    --ct-border: hsl(222 33% 13%);
    --ct-header-fg: hsl(215 20% 55%);
    --ct-text: hsl(210 40% 92%);
    --ct-muted: hsl(215 16% 47%);
    --ct-link: hsl(217 91% 65%);

    background: var(--ct-bg);
    border: 1px solid var(--ct-border);
    border-radius: 0.75rem;
    overflow: hidden;
    color: var(--ct-text);
}

.chamado-table__search {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--ct-border);
}

.search-input {
    flex: 1;
    background: hsl(222 44% 9%);
    border: 1px solid var(--ct-border);
    border-radius: 0.6rem;
    padding: 0.55rem 0.85rem;
    font-size: 0.85rem;
    color: var(--ct-text);
}

.search-input:focus {
    outline: none;
    border-color: hsl(217 91% 60%);
}

.search-select {
    background: hsl(222 44% 9%);
    border: 1px solid var(--ct-border);
    border-radius: 0.6rem;
    padding: 0.55rem 0.75rem;
    font-size: 0.85rem;
    color: var(--ct-text);
    cursor: pointer;
}

.search-select:focus {
    outline: none;
    border-color: hsl(217 91% 60%);
}

.search-count {
    font-size: 0.75rem;
    color: var(--ct-muted);
    white-space: nowrap;
}

.chamado-table__scroll {
    overflow-x: auto;
    max-height: 70vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: hsl(222 33% 22%) transparent;
}

.chamado-table__scroll::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.chamado-table__scroll::-webkit-scrollbar-track {
    background: transparent;
}

.chamado-table__scroll::-webkit-scrollbar-thumb {
    background: hsl(222 33% 22%);
    border-radius: 999px;
}

.chamado-table__scroll::-webkit-scrollbar-thumb:hover {
    background: hsl(222 33% 30%);
}

table {
    width: 100%;
    min-width: 60rem;
    table-layout: fixed;
    border-collapse: collapse;
    font-size: 0.875rem;
}

thead th {
    position: sticky;
    top: 0;
    background: var(--ct-bg);
    text-align: left;
    padding: 0.85rem 1.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ct-header-fg);
    border-bottom: 1px solid var(--ct-border);
    white-space: nowrap;
}

tbody td {
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid var(--ct-border);
    vertical-align: top;
    white-space: nowrap;
}

tbody tr:last-child td {
    border-bottom: none;
}

tbody tr:hover td {
    background: var(--ct-bg-hover);
}

.col-id {
    color: var(--ct-muted);
    width: 3rem;
}

.cell-title {
    font-weight: 500;
    color: var(--ct-text);
    white-space: normal;
    width: 22%;
}

.cell-muted {
    color: var(--ct-text);
    width: 14%;
    overflow: hidden;
    text-overflow: ellipsis;
}

.col-prazo {
    color: var(--ct-muted);
    width: 6.5rem;
}

.col-prazo.is-overdue {
    color: hsl(0 84% 63%);
    font-weight: 600;
}

.col-prioridade {
    color: var(--ct-link);
    font-weight: 500;
    width: 7rem;
}

.col-status {
    white-space: nowrap;
    width: 9rem;
}

.col-actions {
    text-align: right;
    width: 12rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge--aberto {
    background: hsl(217 91% 60% / 0.12);
    color: hsl(213 94% 68%);
    border: 1px solid hsl(217 91% 60% / 0.4);
}

.badge--em_andamento {
    background: hsl(38 92% 50% / 0.14);
    color: hsl(38 92% 60%);
}

.badge--fechado {
    background: hsl(142 71% 45% / 0.14);
    color: hsl(142 71% 55%);
}

.btn {
    border: none;
    border-radius: 0.4rem;
    padding: 0.4rem 0.8rem;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    margin-left: 0.5rem;
}

.btn--edit {
    background: hsl(217 91% 60%);
    color: white;
}

.btn--edit:hover {
    background: hsl(217 91% 54%);
}

.btn--delete {
    background: hsl(0 72% 51%);
    color: white;
}

.btn--delete:hover {
    background: hsl(0 72% 45%);
}

.empty {
    text-align: center;
    color: var(--ct-muted);
    padding: 2.5rem 1rem;
    white-space: normal;
}
</style>
