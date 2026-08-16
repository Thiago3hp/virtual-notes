<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import type { Chamado, Equipamento } from '@/types';

const props = defineProps<{
    equipamentos: Equipamento[];
    chamados: Chamado[];
}>();

const page = usePage();
const flash = () => page.props.flash as { success?: string; error?: string };
const errors = () => page.props.errors as Record<string, string>;

const novoNome = ref('');
const novaDescricao = ref('');
const novaQuantidade = ref(1);
const novoChamadoId = ref<number | ''>('');
const adding = ref(false);
const camposVazio = ref(false);

const editingId = ref<number | null>(null);
const editingNome = ref('');
const editingDescricao = ref('');
const editingQuantidade = ref(0);
const editingChamadoId = ref<number | ''>('');

function adicionar() {
    if (adding.value) return;

    if (!novoNome.value.trim()) {
        camposVazio.value = true;
        setTimeout(() => (camposVazio.value = false), 1500);
        return;
    }

    adding.value = true;

    router.post('/equipamentos', {
        nome: novoNome.value.trim(),
        descricao: novaDescricao.value.trim() || null,
        quantidade: novaQuantidade.value,
        chamado_id: novoChamadoId.value || null,
    }, {
        onSuccess: () => {
            novoNome.value = '';
            novaDescricao.value = '';
            novaQuantidade.value = 1;
            novoChamadoId.value = '';
        },
        onFinish: () => {
            adding.value = false;
        },
    });
}

function comecarEdicao(equipamento: Equipamento) {
    editingId.value = equipamento.id;
    editingNome.value = equipamento.nome;
    editingDescricao.value = equipamento.descricao ?? '';
    editingQuantidade.value = equipamento.quantidade;
    editingChamadoId.value = equipamento.chamado_id ?? '';
}

function cancelarEdicao() {
    editingId.value = null;
}

function salvarEdicao(id: number) {
    if (!editingNome.value.trim()) return;

    router.put(`/equipamentos/${id}`, {
        nome: editingNome.value.trim(),
        descricao: editingDescricao.value.trim() || null,
        quantidade: editingQuantidade.value,
        chamado_id: editingChamadoId.value || null,
    }, {
        onSuccess: () => {
            editingId.value = null;
        },
    });
}

function excluir(equipamento: Equipamento) {
    if (!confirm(`Remover o equipamento "${equipamento.nome}"?`)) return;
    router.delete(`/equipamentos/${equipamento.id}`);
}
</script>

<template>
    <div class="queue-card">
        <div class="queue-add">
            <input
                v-model="novoNome"
                class="queue-input"
                :class="{ 'queue-input-error': camposVazio }"
                placeholder="Nome do equipamento"
                @keyup.enter="adicionar"
                @input="camposVazio = false"
            />
            <input v-model.number="novaQuantidade" type="number" min="0" class="queue-input queue-qty" @keyup.enter="adicionar" />
            <select v-model="novoChamadoId" class="queue-input queue-select">
                <option value="">Sem ordem de chamado</option>
                <option v-for="c in props.chamados" :key="c.id" :value="c.id">#{{ c.id }} — {{ c.problema }}</option>
            </select>
            <button type="button" class="queue-add-btn" :disabled="adding" @click="adicionar">
                {{ adding ? 'Adicionando...' : 'Adicionar' }}
            </button>
        </div>
        <div class="queue-add queue-add-desc">
            <input v-model="novaDescricao" class="queue-input" placeholder="Descrição (opcional)" @keyup.enter="adicionar" />
        </div>

        <p v-if="camposVazio" class="queue-feedback queue-feedback-error">Digite um nome antes de adicionar.</p>
        <p v-else-if="errors().nome" class="queue-feedback queue-feedback-error">{{ errors().nome }}</p>
        <p v-else-if="flash().success" class="queue-feedback queue-feedback-success">{{ flash().success }}</p>
        <p v-else-if="flash().error" class="queue-feedback queue-feedback-error">{{ flash().error }}</p>

        <ol class="queue-list">
            <li v-for="(equipamento, index) in props.equipamentos" :key="equipamento.id" class="queue-row">
                <span class="queue-position">{{ index + 1 }}</span>

                <template v-if="editingId === equipamento.id">
                    <input v-model="editingNome" class="queue-input queue-input-inline" autofocus />
                    <input v-model="editingDescricao" class="queue-input queue-input-inline" placeholder="Descrição" />
                    <input v-model.number="editingQuantidade" type="number" min="0" class="queue-input queue-qty" />
                    <select v-model="editingChamadoId" class="queue-input queue-select">
                        <option value="">Sem ordem de chamado</option>
                        <option v-for="c in props.chamados" :key="c.id" :value="c.id">#{{ c.id }} — {{ c.problema }}</option>
                    </select>
                    <div class="queue-actions">
                        <button type="button" class="queue-btn queue-btn-save" @click="salvarEdicao(equipamento.id)">Salvar</button>
                        <button type="button" class="queue-btn queue-btn-cancel" @click="cancelarEdicao">Cancelar</button>
                    </div>
                </template>

                <template v-else>
                    <span class="queue-name">{{ equipamento.nome }}</span>
                    <span class="queue-desc">{{ equipamento.descricao || '—' }}</span>
                    <span class="queue-qty-badge">{{ equipamento.quantidade }}</span>
                    <span v-if="equipamento.chamado" class="queue-os-badge">
                        OS #{{ equipamento.chamado.id }} — {{ equipamento.chamado.problema }}
                    </span>
                    <span v-else class="queue-os-badge queue-os-badge-empty">Sem OS</span>
                    <div class="queue-actions">
                        <button type="button" class="queue-btn queue-btn-edit" @click="comecarEdicao(equipamento)">Editar</button>
                        <button type="button" class="queue-btn queue-btn-delete" @click="excluir(equipamento)">Excluir</button>
                    </div>
                </template>
            </li>

            <li v-if="!props.equipamentos.length" class="queue-empty">
                Nenhum equipamento cadastrado ainda.
            </li>
        </ol>
    </div>
</template>

<style scoped>
.queue-card {
    background: hsl(222 50% 6%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 0.9rem;
    padding: 1.25rem;
}

.queue-add {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.queue-add-desc {
    margin-bottom: 1.25rem;
}

.queue-input {
    flex: 1;
    background: hsl(222 44% 9%);
    border: 1px solid hsl(222 33% 16%);
    border-radius: 0.65rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.875rem;
    color: hsl(210 40% 94%);
}

.queue-input:focus {
    outline: none;
    border-color: hsl(213 94% 60%);
}

.queue-input-error {
    border-color: hsl(0 84% 60%) !important;
    animation: queue-shake 0.35s ease;
}

@keyframes queue-shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}

.queue-qty {
    flex: 0 0 4.5rem;
}

.queue-select {
    flex: 1.4;
    cursor: pointer;
}

.queue-add-btn {
    background: hsl(217 91% 60%);
    color: white;
    border: none;
    border-radius: 0.65rem;
    padding: 0 1.1rem;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
}

.queue-add-btn:hover {
    background: hsl(217 91% 54%);
}

.queue-add-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.queue-feedback {
    font-size: 0.8rem;
    margin: -0.75rem 0 1rem;
}

.queue-feedback-error {
    color: hsl(0 84% 65%);
}

.queue-feedback-success {
    color: hsl(142 71% 55%);
}

.queue-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.queue-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    background: hsl(222 44% 8%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 0.65rem;
    padding: 0.65rem 0.9rem;
    flex-wrap: wrap;
}

.queue-position {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: 999px;
    background: hsl(222 33% 14%);
    color: hsl(213 90% 68%);
    font-size: 0.75rem;
    font-weight: 700;
    flex-shrink: 0;
}

.queue-name {
    flex: 1;
    font-size: 0.9rem;
    color: hsl(210 40% 94%);
}

.queue-desc {
    flex: 1.5;
    font-size: 0.85rem;
    color: hsl(215 20% 60%);
}

.queue-qty-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.2rem;
    padding: 0.25rem 0.5rem;
    border-radius: 999px;
    background: hsl(217 91% 60% / 0.14);
    color: hsl(213 94% 68%);
    font-size: 0.75rem;
    font-weight: 700;
}

.queue-os-badge {
    font-size: 0.72rem;
    color: hsl(142 71% 60%);
    background: hsl(142 71% 45% / 0.12);
    border-radius: 999px;
    padding: 0.25rem 0.6rem;
    white-space: nowrap;
}

.queue-os-badge-empty {
    color: hsl(215 20% 50%);
    background: hsl(222 33% 12%);
}

.queue-input-inline {
    flex: 1;
}

.queue-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.queue-btn {
    border: none;
    border-radius: 0.5rem;
    padding: 0.4rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
}

.queue-btn-edit {
    background: hsl(217 91% 60%);
    color: white;
}

.queue-btn-edit:hover {
    background: hsl(217 91% 54%);
}

.queue-btn-delete {
    background: hsl(0 72% 51%);
    color: white;
}

.queue-btn-delete:hover {
    background: hsl(0 72% 45%);
}

.queue-btn-save {
    background: hsl(142 71% 45%);
    color: white;
}

.queue-btn-save:hover {
    background: hsl(142 71% 39%);
}

.queue-btn-cancel {
    background: transparent;
    border: 1px solid hsl(222 33% 20%);
    color: hsl(215 20% 65%);
}

.queue-btn-cancel:hover {
    background: hsl(222 33% 14%);
}

.queue-empty {
    text-align: center;
    color: hsl(215 20% 55%);
    padding: 2rem 1rem;
    font-size: 0.85rem;
}
</style>
