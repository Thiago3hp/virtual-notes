<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import chamados from '@/routes/chamados';
import { DialogRoot, DialogTitle, DialogTrigger, DialogContent, Button, AlertTitle, AlertDescription } from '@/imports';
import { Alert } from '@/components/ui/alert';
import type { ChamadoCreateData } from '@/types';

const page = usePage();
const flash = () => page.props.flash as { success?: string; error?: string };

const open = ref(false);
const editingPrazo = ref(false);
const editingColeta = ref(false);

const emptyForm = (): ChamadoCreateData => ({
    solicitante_nome: '',
    solicitante_numero: '',
    setor: '',
    problema: '',
    descricao: '',
    sala: '',
    status: 'aberto',
    prazo: '',
    prioridade: 'Normal',
    data_coleta: '',
    tecnico_nome: '',
    laudo_tecnico: '',
});

const form = ref<ChamadoCreateData>(emptyForm());

function formatDate(value: string | null) {
    if (!value) return null;
    const [year, month, day] = value.split('-');
    return `${day}/${month}/${year}`;
}

function createChamado() {
    if (!form.value.solicitante_nome || !form.value.solicitante_numero || !form.value.setor || !form.value.problema) {
        return;
    }

    router.post(chamados.store().url, form.value, {
        onSuccess: () => {
            form.value = emptyForm();
            editingPrazo.value = false;
            editingColeta.value = false;
            open.value = false;
        },
    });
}
</script>

<template>
    <DialogRoot v-model:open="open">
        <DialogTrigger>
            <Button size="sm">Criar chamado</Button>
        </DialogTrigger>
        <DialogContent class="os-dialog">
            <DialogTitle class="os-heading">Criar Ordem de Serviço</DialogTitle>

            <div class="os-scroll">
            <div class="os-row2">
                <div>
                    <label class="os-label">Solicitante *</label>
                    <input v-model="form.solicitante_nome" class="os-field" placeholder="Nome de quem pediu" />
                </div>
                <div>
                    <label class="os-label">Contato *</label>
                    <input v-model="form.solicitante_numero" class="os-field" placeholder="Ex: 5586999999999" />
                </div>
            </div>

            <label class="os-label">Título *</label>
            <input v-model="form.problema" class="os-field" placeholder="O que está acontecendo?" />

            <label class="os-label">Descrição</label>
            <textarea v-model="form.descricao" class="os-field os-textarea" />

            <div class="os-row2">
                <div>
                    <label class="os-label">Cliente *</label>
                    <input v-model="form.setor" class="os-field" placeholder="Ex: Farmácia - CES" />
                </div>
                <div>
                    <label class="os-label">Sala / local</label>
                    <input v-model="form.sala" class="os-field" placeholder="Opcional" />
                </div>
            </div>

            <div class="os-row3">
                <div>
                    <label class="os-label">Prioridade</label>
                    <select v-model="form.prioridade" class="os-field os-select">
                        <option value="Baixa">Baixa</option>
                        <option value="Normal">Normal</option>
                        <option value="Alta">Alta</option>
                        <option value="Urgente">Urgente</option>
                    </select>
                </div>
                <div>
                    <label class="os-label">Status</label>
                    <select v-model="form.status" class="os-field os-select">
                        <option value="aberto">Aberta</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="fechado">Concluída</option>
                    </select>
                </div>
                <div>
                    <label class="os-label">Prazo</label>
                    <div class="os-date-box">
                        <input v-if="editingPrazo" v-model="form.prazo" type="date" class="os-date-input" @blur="editingPrazo = false" autofocus />
                        <template v-else>
                            <span class="os-date-value">{{ formatDate(form.prazo) || 'Sem prazo' }}</span>
                            <button type="button" class="os-date-toggle" @click="editingPrazo = true">Definir prazo</button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="os-row2">
                <div>
                    <label class="os-label">Data da coleta</label>
                    <div class="os-date-box">
                        <input v-if="editingColeta" v-model="form.data_coleta" type="date" class="os-date-input" @blur="editingColeta = false" autofocus />
                        <template v-else>
                            <span class="os-date-value">{{ formatDate(form.data_coleta) || 'Sem data definida' }}</span>
                            <button type="button" class="os-date-toggle" @click="editingColeta = true">Definir data da coleta</button>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="os-label">Técnico responsável</label>
                    <input v-model="form.tecnico_nome" class="os-field" placeholder="Opcional, pode atribuir depois" />
                </div>
            </div>

            <div class="os-actions">
                <button type="button" class="os-btn os-btn-cancel" @click="open = false">Cancelar</button>
                <button type="button" class="os-btn os-btn-save" @click="createChamado">Criar Chamado</button>
            </div>

            <Alert v-if="flash().success">
                <AlertTitle>Sucesso</AlertTitle>
                <AlertDescription>{{ flash().success }}</AlertDescription>
            </Alert>
            </div>
        </DialogContent>
    </DialogRoot>
</template>

<style scoped>
.os-dialog {
    background: hsl(222 50% 6%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 1rem;
    max-width: 36rem;
    max-height: 80vh;
    height: auto;
    display: block !important;
    overflow: hidden;
    padding: 1.5rem 1.5rem 0;
}

.os-scroll {
    display: block;
    overflow-y: auto;
    overscroll-behavior: contain;
    max-height: calc(80vh - 5rem);
    padding-bottom: 1.5rem;
    padding-right: 0.6rem;
    scrollbar-width: thin;
    scrollbar-color: hsl(222 33% 22%) transparent;
}

.os-scroll::-webkit-scrollbar {
    width: 6px;
}

.os-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.os-scroll::-webkit-scrollbar-thumb {
    background: hsl(222 33% 22%);
    border-radius: 999px;
}

.os-scroll::-webkit-scrollbar-thumb:hover {
    background: hsl(222 33% 30%);
}

.os-heading {
    font-size: 1.375rem;
    font-weight: 700;
    color: hsl(210 40% 96%);
    margin-bottom: 0.25rem;
}

.os-label {
    display: block;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: hsl(213 90% 68%);
    margin-top: 1.35rem;
    margin-bottom: 0.55rem;
}

.os-field {
    width: 100%;
    background: hsl(222 44% 9%);
    border: 1px solid hsl(222 33% 16%);
    border-radius: 0.65rem;
    padding: 0.6rem 0.75rem;
    font-size: 0.875rem;
    color: hsl(210 40% 94%);
}

.os-field:focus {
    outline: none;
    border-color: hsl(213 94% 60%);
}

.os-textarea {
    min-height: 5.5rem;
    resize: vertical;
}

.os-select {
    appearance: none;
    cursor: pointer;
}

.os-row2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.os-row3 {
    display: grid;
    grid-template-columns: 1fr 1fr 1.1fr;
    gap: 1rem;
}

.os-date-box {
    background: hsl(222 44% 9%);
    border: 1px solid hsl(222 33% 16%);
    border-radius: 0.65rem;
    padding: 0.55rem 0.75rem;
    min-height: 3.3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0.2rem;
}

.os-date-value {
    font-size: 0.85rem;
    color: hsl(210 40% 94%);
}

.os-date-toggle {
    background: none;
    border: none;
    padding: 0;
    text-align: left;
    font-size: 0.7rem;
    color: hsl(213 60% 55%);
    cursor: pointer;
}

.os-date-toggle:hover {
    color: hsl(213 90% 68%);
}

.os-date-input {
    background: transparent;
    border: none;
    color: hsl(210 40% 94%);
    font-size: 0.85rem;
    outline: none;
    width: 100%;
}

.os-actions {
    display: grid;
    grid-template-columns: 1fr 1.4fr;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

.os-btn {
    border-radius: 0.65rem;
    padding: 0.65rem;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
}

.os-btn-cancel {
    background: transparent;
    border: 1px solid hsl(222 33% 18%);
    color: hsl(215 20% 65%);
}

.os-btn-cancel:hover {
    background: hsl(222 33% 12%);
}

.os-btn-save {
    background: hsl(217 91% 60%);
    border: none;
    color: white;
}

.os-btn-save:hover {
    background: hsl(217 91% 54%);
}
</style>
