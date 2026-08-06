<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import chamados from '@/routes/chamados';
import {
    DialogRoot,
    DialogTitle,
    DialogContent,
    DialogDescription,
    Input,
    Button,
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
    AlertTitle,
    AlertDescription,
} from '@/imports';
import { Alert } from '@/components/ui/alert';
import type { Chamado, ChamadoFormData } from '@/types';

const props = defineProps<{
    chamado: Chamado | null;
}>();

const open = defineModel<boolean>('open', { default: false });

const page = usePage();
const flash = () => page.props.flash as { success?: string; error?: string };

const emptyForm = (): ChamadoFormData => ({
    setor: '',
    problema: '',
    descricao: '',
    sala: '',
    status: 'aberto',
    prazo: '',
    prioridade: 'Normal',
    tecnico_nome: '',
    laudo_tecnico: '',
});

const form = ref<ChamadoFormData>(emptyForm());

// Sempre que uma linha diferente for aberta pra edição, recarrega o form.
watch(
    () => props.chamado,
    (chamado) => {
        form.value = chamado
            ? {
                  setor: chamado.setor,
                  problema: chamado.problema,
                  descricao: chamado.descricao,
                  sala: chamado.sala,
                  status: chamado.status,
                  prazo: chamado.prazo,
                  prioridade: chamado.prioridade,
                  tecnico_nome: chamado.tecnico_nome,
                  laudo_tecnico: chamado.laudo_tecnico,
              }
            : emptyForm();
    },
    { immediate: true },
);

function updateChamado() {
    if (!props.chamado || !form.value.setor || !form.value.problema) return;

    router.put(chamados.update(props.chamado.id).url, form.value, {
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <DialogRoot v-model:open="open">
        <DialogContent class="w-80 space-y-3 p-4">
            <DialogTitle>Editar chamado #{{ props.chamado?.id }}</DialogTitle>
            <DialogDescription>
                Solicitante e contato vêm do WhatsApp e não são editáveis aqui.
            </DialogDescription>

            <label class="text-xs text-muted-foreground">Título / problema</label>
            <Input v-model="form.problema" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Cliente / setor</label>
            <Input v-model="form.setor" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Descrição</label>
            <textarea v-model="form.descricao" class="min-h-[80px] w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Sala / local</label>
            <Input v-model="form.sala" class="w-full rounded-lg border p-2" />

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-muted-foreground">Prazo</label>
                    <input
                        v-model="form.prazo"
                        type="date"
                        class="w-full rounded-lg border p-2 text-sm"
                    />
                </div>
                <div>
                    <label class="text-xs text-muted-foreground">Prioridade</label>
                    <Select v-model="form.prioridade">
                        <SelectTrigger class="w-full rounded-lg border p-2">
                            <SelectValue placeholder="Prioridade" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="Baixa">Baixa</SelectItem>
                            <SelectItem value="Normal">Normal</SelectItem>
                            <SelectItem value="Alta">Alta</SelectItem>
                            <SelectItem value="Urgente">Urgente</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>

            <label class="text-xs text-muted-foreground">Status</label>
            <Select v-model="form.status">
                <SelectTrigger class="w-full rounded-lg border p-2">
                    <SelectValue placeholder="Status" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="aberto">Aberta</SelectItem>
                    <SelectItem value="em_andamento">Em Andamento</SelectItem>
                    <SelectItem value="fechado">Concluída</SelectItem>
                </SelectContent>
            </Select>

            <label class="text-xs text-muted-foreground">Técnico responsável</label>
            <Input v-model="form.tecnico_nome" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Laudo técnico</label>
            <textarea v-model="form.laudo_tecnico" class="min-h-[80px] w-full rounded-lg border p-2" />

            <Button
                @click.stop="updateChamado"
                class="w-full rounded-lg bg-black p-2 text-white"
            >
                Salvar alterações
            </Button>

            <Alert v-if="flash().success">
                <AlertTitle>Sucesso</AlertTitle>
                <AlertDescription>{{ flash().success }}</AlertDescription>
            </Alert>
        </DialogContent>
    </DialogRoot>
</template>
