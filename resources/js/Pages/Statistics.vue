<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { statistics } from '@/routes';
import type { BreadcrumbItem, MonthlyCount } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import CompletedByMonthChart from '@/components/CompletedByMonthChart.vue';

const props = defineProps<{
    concluidosPorMes: MonthlyCount[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Statistics',
        href: statistics(),
    },
];
</script>

<template>
    <Head title="Statistics" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <CompletedByMonthChart :data="props.concluidosPorMes" />
        </div>
    </AppLayout>
</template>
