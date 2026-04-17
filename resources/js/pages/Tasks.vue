<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { tasks } from '@/routes';
import type { BreadcrumbItem } from '@/types';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: tasks(),
    },
];
</script>
<template>
<Head title="Tasks"/>
    <div class="container" >
        <h1>Minhas Tarefas</h1>
        <div class="task-list">
        </div>    
    </div>

</template>
<style scoped>  </style>