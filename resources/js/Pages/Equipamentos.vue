<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem, Chamado, Equipamento } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import EquipamentoQueue from '@/components/EquipamentoQueue.vue';

const props = defineProps<{
    equipamento: {
        data: Equipamento[];
    };
    chamado: {
        data: Chamado[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Equipamentos',
        href: '/equipamentos',
    },
];
</script>

<template>
    <Head title="Equipamentos" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <EquipamentoQueue :equipamentos="props.equipamento.data" :chamados="props.chamado.data" />
        </div>
    </AppLayout>
</template>
