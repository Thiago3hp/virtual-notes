<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { statistics } from '@/routes';
import type { BreadcrumbItem } from '@/types';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Statistics',
        href: statistics(),
    },
];

</script>
<template>
    <Head title="Statistics"/>
        <div class="container" >
            <h1>Statistics</h1>
            <p>Em breve...</p>
        </div>
</template>
