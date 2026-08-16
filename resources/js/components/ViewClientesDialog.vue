<script setup lang="ts">
import { computed, ref } from 'vue';
import { DialogRoot, DialogTitle, DialogTrigger, DialogContent, Button } from '@/imports';
import type { Chamado, ChamadoStatus, Cliente } from '@/types';

const props = defineProps<{
    clientes: Cliente[];
    chamados: Chamado[];
}>();

const open = ref(false);
const selecionadoId = ref<number | null>(null);

const statusLabel: Record<ChamadoStatus, string> = {
    aberto: 'Aberta',
    em_andamento: 'Em Andamento',
    fechado: 'Concluída',
};

const clienteSelecionado = computed(() => props.clientes.find((c) => c.id === selecionadoId.value) ?? null);

const chamadosDoCliente = computed(() => {
    if (!clienteSelecionado.value) return [];
    return props.chamados.filter((c) => c.setor === clienteSelecionado.value!.nome);
});

function toggle(cliente: Cliente) {
    selecionadoId.value = selecionadoId.value === cliente.id ? null : cliente.id;
}

function fechar() {
    open.value = false;
    selecionadoId.value = null;
}
</script>

<template>
    <DialogRoot v-model:open="open" @update:open="(v) => !v && fechar()">
        <DialogTrigger>
            <Button variant="outline" size="sm">Ver clientes</Button>
        </DialogTrigger>
        <DialogContent class="vc-dialog">
            <DialogTitle class="vc-heading">Clientes</DialogTitle>
            <p class="vc-subtitle">Chamados por status, por cliente</p>

            <div class="vc-scroll">
                <div v-for="cliente in props.clientes" :key="cliente.id" class="vc-item">
                    <button type="button" class="vc-row" @click="toggle(cliente)">
                        <span class="vc-name">{{ cliente.nome }}</span>
                        <span class="vc-badges">
                            <span class="vc-badge vc-badge--aberto" title="Abertas">{{ cliente.abertos_count }}</span>
                            <span class="vc-badge vc-badge--em_andamento" title="Em andamento">{{ cliente.em_andamento_count }}</span>
                            <span class="vc-badge vc-badge--fechado" title="Concluídas">{{ cliente.fechados_count }}</span>
                        </span>
                        <span class="vc-toggle">{{ selecionadoId === cliente.id ? 'Ocultar chamados' : 'Ver chamados' }}</span>
                    </button>

                    <div v-if="selecionadoId === cliente.id" class="vc-card">
                        <div v-if="!chamadosDoCliente.length" class="vc-card-empty">
                            Nenhum chamado registrado para esse cliente ainda.
                        </div>
                        <div v-for="chamado in chamadosDoCliente" :key="chamado.id" class="vc-card-row">
                            <span class="vc-card-id">#{{ chamado.id }}</span>
                            <span class="vc-card-title">{{ chamado.problema }}</span>
                            <span class="vc-card-status" :class="`vc-card-status--${chamado.status}`">
                                {{ statusLabel[chamado.status] }}
                            </span>
                        </div>
                    </div>
                </div>

                <p v-if="!props.clientes.length" class="vc-empty">Nenhum cliente cadastrado ainda.</p>
            </div>
        </DialogContent>
    </DialogRoot>
</template>

<style scoped>
.vc-dialog {
    background: hsl(222 50% 6%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 1rem;
    max-width: 30rem;
    max-height: 82vh;
    display: block !important;
    overflow: hidden;
    padding: 1.5rem;
}

.vc-heading {
    font-size: 1.25rem;
    font-weight: 700;
    color: hsl(210 40% 96%);
}

.vc-subtitle {
    font-size: 0.8rem;
    color: hsl(215 20% 55%);
    margin: 0.15rem 0 1.1rem;
}

.vc-scroll {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    overflow-y: auto;
    max-height: calc(82vh - 6rem);
    padding-right: 0.4rem;
    scrollbar-width: thin;
    scrollbar-color: hsl(222 33% 22%) transparent;
}

.vc-item {
    border: 1px solid hsl(222 33% 16%);
    border-radius: 0.75rem;
    overflow: hidden;
}

.vc-row {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: hsl(222 44% 9%);
    border: none;
    padding: 0.6rem 0.9rem;
    cursor: pointer;
    text-align: left;
}

.vc-row:hover {
    background: hsl(222 44% 12%);
}

.vc-name {
    flex: 1;
    font-size: 0.85rem;
    color: hsl(210 40% 94%);
}

.vc-badges {
    display: flex;
    gap: 0.35rem;
}

.vc-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.5rem;
    height: 1.5rem;
    padding: 0 0.35rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
}

.vc-badge--aberto {
    background: hsl(217 91% 60% / 0.16);
    color: hsl(213 94% 68%);
}

.vc-badge--em_andamento {
    background: hsl(38 92% 50% / 0.16);
    color: hsl(38 92% 62%);
}

.vc-badge--fechado {
    background: hsl(142 71% 45% / 0.16);
    color: hsl(142 71% 58%);
}

.vc-toggle {
    font-size: 0.7rem;
    color: hsl(213 60% 60%);
    white-space: nowrap;
}

.vc-card {
    background: hsl(222 50% 5%);
    border-top: 1px solid hsl(222 33% 16%);
    padding: 0.6rem 0.9rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.vc-card-empty {
    font-size: 0.78rem;
    color: hsl(215 20% 55%);
}

.vc-card-row {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.vc-card-id {
    font-size: 0.75rem;
    color: hsl(215 20% 55%);
    flex-shrink: 0;
}

.vc-card-title {
    flex: 1;
    font-size: 0.8rem;
    color: hsl(210 40% 92%);
}

.vc-card-status {
    font-size: 0.68rem;
    font-weight: 700;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    white-space: nowrap;
}

.vc-card-status--aberto {
    background: hsl(217 91% 60% / 0.16);
    color: hsl(213 94% 68%);
}

.vc-card-status--em_andamento {
    background: hsl(38 92% 50% / 0.16);
    color: hsl(38 92% 62%);
}

.vc-card-status--fechado {
    background: hsl(142 71% 45% / 0.16);
    color: hsl(142 71% 58%);
}

.vc-empty {
    color: hsl(215 20% 55%);
    font-size: 0.85rem;
}
</style>
