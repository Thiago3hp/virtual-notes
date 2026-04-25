<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { statistics } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Statistics',
        href: statistics(),
    },
];

</script>
<template>
    <Head title="Statistics"/>

    <AppLayout :breadcrumbs="breadcrumbs">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
            <div class="container" >
                <h1>tela de listagem</h1>
                <p>Em breve...</p>
            </div>
                <PlaceholderPattern />
            </div>
            
    </AppLayout>
</template>
