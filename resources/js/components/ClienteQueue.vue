<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import type { Cliente } from '@/types';

const props = defineProps<{
    clientes: Cliente[];
}>();

const page = usePage();
const flash = () => page.props.flash as { success?: string; error?: string };
const errors = () => page.props.errors as Record<string, string>;

const novoNome = ref('');
const adding = ref(false);
const camposVazio = ref(false);
const editingId = ref<number | null>(null);
const editingNome = ref('');

function adicionar() {
    if (adding.value) return;

    if (!novoNome.value.trim()) {
        camposVazio.value = true;
        setTimeout(() => (camposVazio.value = false), 1500);
        return;
    }

    adding.value = true;

    router.post('/clientes', { nome: novoNome.value.trim() }, {
        onSuccess: () => {
            novoNome.value = '';
        },
        onFinish: () => {
            adding.value = false;
        },
    });
}

function comecarEdicao(cliente: Cliente) {
    editingId.value = cliente.id;
    editingNome.value = cliente.nome;
}

function cancelarEdicao() {
    editingId.value = null;
    editingNome.value = '';
}

function salvarEdicao(id: number) {
    if (!editingNome.value.trim()) return;

    router.put(`/clientes/${id}`, { nome: editingNome.value.trim() }, {
        onSuccess: () => {
            editingId.value = null;
        },
    });
}

function excluir(cliente: Cliente) {
    if (!confirm(`Remover o cliente "${cliente.nome}"?`)) return;
    router.delete(`/clientes/${cliente.id}`);
}
</script>

<template>
    <div class="queue-card">
        <div class="queue-add">
            <input
                v-model="novoNome"
                class="queue-input"
                :class="{ 'queue-input-error': camposVazio }"
                placeholder="Nome do cliente (ex: Farmácia - CES)"
                @keyup.enter="adicionar"
                @input="camposVazio = false"
            />
            <button type="button" class="queue-add-btn" :disabled="adding" @click="adicionar">
                {{ adding ? 'Adicionando...' : 'Adicionar' }}
            </button>
        </div>

        <p v-if="camposVazio" class="queue-feedback queue-feedback-error">Digite um nome antes de adicionar.</p>
        <p v-else-if="errors().nome" class="queue-feedback queue-feedback-error">{{ errors().nome }}</p>
        <p v-else-if="flash().success" class="queue-feedback queue-feedback-success">{{ flash().success }}</p>
        <p v-else-if="flash().error" class="queue-feedback queue-feedback-error">{{ flash().error }}</p>

        <ol class="queue-list">
            <li v-for="(cliente, index) in props.clientes" :key="cliente.id" class="queue-row">
                <span class="queue-position">{{ index + 1 }}</span>

                <template v-if="editingId === cliente.id">
                    <input
                        v-model="editingNome"
                        class="queue-input queue-input-inline"
                        @keyup.enter="salvarEdicao(cliente.id)"
                        @keyup.esc="cancelarEdicao"
                        autofocus
                    />
                    <div class="queue-actions">
                        <button type="button" class="queue-btn queue-btn-save" @click="salvarEdicao(cliente.id)">Salvar</button>
                        <button type="button" class="queue-btn queue-btn-cancel" @click="cancelarEdicao">Cancelar</button>
                    </div>
                </template>

                <template v-else>
                    <span class="queue-name">{{ cliente.nome }}</span>
                    <div class="queue-actions">
                        <button type="button" class="queue-btn queue-btn-edit" @click="comecarEdicao(cliente)">Editar</button>
                        <button type="button" class="queue-btn queue-btn-delete" @click="excluir(cliente)">Excluir</button>
                    </div>
                </template>
            </li>

            <li v-if="!props.clientes.length" class="queue-empty">
                Nenhum cliente cadastrado ainda.
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
