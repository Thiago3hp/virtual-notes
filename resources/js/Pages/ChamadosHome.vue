<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { chamadoshome } from '@/routes';
import type { BreadcrumbItem, Chamado } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ChamadoTable from '@/components/ChamadoTable.vue';
import CreateChamadoDialog from '@/components/CreateChamadoDialog.vue';
import EditChamadoDialog from '@/components/EditChamadoDialog.vue';

const props = defineProps<{
    chamado: {
        data: Chamado[];
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
        <div class="flex justify-end p-4 pb-0">
            <CreateChamadoDialog />
        </div>

        <div class="p-4">
            <ChamadoTable :chamados="props.chamado.data" @edit="openEditDialog" />
        </div>

        <EditChamadoDialog v-model:open="editDialogOpen" :chamado="editingChamado" />
    </AppLayout>
</template>
