<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import chamados from '@/routes/chamados';
import {
    DialogRoot,
    DialogTitle,
    DialogTrigger,
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
import type { ChamadoCreateData } from '@/types';

const page = usePage();
const flash = () => page.props.flash as { success?: string; error?: string };

const open = ref(false);

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
    tecnico_nome: '',
    laudo_tecnico: '',
});

const form = ref<ChamadoCreateData>(emptyForm());

function createChamado() {
    if (!form.value.solicitante_nome || !form.value.solicitante_numero || !form.value.setor || !form.value.problema) {
        return;
    }

    router.post(chamados.store().url, form.value, {
        onSuccess: () => {
            form.value = emptyForm();
            open.value = false;
        },
    });
}
</script>

<template>
    <DialogRoot v-model:open="open">
        <DialogTrigger>
            <Button size="sm">
                Criar chamado
            </Button>
        </DialogTrigger>
        <DialogContent class="w-80 space-y-3 p-4">
            <DialogTitle>Criar chamado</DialogTitle>
            <DialogDescription>
                Para quando o chamado precisa ser aberto manualmente pelo técnico (não veio do WhatsApp).
            </DialogDescription>

            <label class="text-xs text-muted-foreground">Solicitante</label>
            <Input v-model="form.solicitante_nome" placeholder="Nome de quem pediu" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Número de contato</label>
            <Input v-model="form.solicitante_numero" placeholder="Ex: 5586999999999" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Título / problema</label>
            <Input v-model="form.problema" placeholder="O que está acontecendo?" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Cliente / setor</label>
            <Input v-model="form.setor" placeholder="Ex: Farmácia - CES" class="w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Descrição</label>
            <textarea v-model="form.descricao" class="min-h-[80px] w-full rounded-lg border p-2" />

            <label class="text-xs text-muted-foreground">Sala / local</label>
            <Input v-model="form.sala" class="w-full rounded-lg border p-2" />

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-muted-foreground">Prazo</label>
                    <input v-model="form.prazo" type="date" class="w-full rounded-lg border p-2 text-sm" />
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

            <label class="text-xs text-muted-foreground">Técnico responsável (opcional)</label>
            <Input v-model="form.tecnico_nome" class="w-full rounded-lg border p-2" />

            <Button
                @click.stop="createChamado"
                class="w-full rounded-lg bg-black p-2 text-white"
            >
                Criar chamado
            </Button>

            <Alert v-if="flash().success">
                <AlertTitle>Sucesso</AlertTitle>
                <AlertDescription>{{ flash().success }}</AlertDescription>
            </Alert>
        </DialogContent>
    </DialogRoot>
</template>
