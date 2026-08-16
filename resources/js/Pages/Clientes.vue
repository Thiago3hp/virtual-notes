<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import type { BreadcrumbItem, Cliente } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import ClienteQueue from '@/components/ClienteQueue.vue';

const props = defineProps<{
    cliente: {
        data: Cliente[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clientes',
        href: '/clientes',
    },
];
</script>

<template>
    <Head title="Clientes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <ClienteQueue :clientes="props.cliente.data" />
        </div>
    </AppLayout>
</template>
