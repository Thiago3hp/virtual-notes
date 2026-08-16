<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { chamadoshome } from '@/routes';
import type { BreadcrumbItem, Chamado, Cliente } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ChamadoTable from '@/components/ChamadoTable.vue';
import CreateChamadoDialog from '@/components/CreateChamadoDialog.vue';
import EditChamadoDialog from '@/components/EditChamadoDialog.vue';
import ViewClientesDialog from '@/components/ViewClientesDialog.vue';

const props = defineProps<{
    chamado: {
        data: Chamado[];
    };
    cliente: {
        data: Cliente[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Chamados',
        href: chamadoshome(),
    },
];

const editingChamado = ref<Chamado | null>(null);
const editDialogOpen = ref(false);

function openEditDialog(chamado: Chamado) {
    editingChamado.value = chamado;
    editDialogOpen.value = true;
}
</script>

<template>
    <Head title="Chamados" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex justify-end gap-2 p-4 pb-0">
            <ViewClientesDialog :clientes="props.cliente.data" :chamados="props.chamado.data" />
            <CreateChamadoDialog :clientes="props.cliente.data" />
        </div>

        <div class="p-4">
            <ChamadoTable :chamados="props.chamado.data" @edit="openEditDialog" />
        </div>

        <EditChamadoDialog v-model:open="editDialogOpen" :chamado="editingChamado" :clientes="props.cliente.data" />
    </AppLayout>
</template>
