<script setup lang="ts">
import { 
    Head,
} from '@inertiajs/vue3';
import { taskshome } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import CreateTask from '@/components/CreateTask.vue';
import SelectList from '@/components/SelectList.vue';
import { DialogRoot } from 'reka-ui';
import Button from '@/components/ui/button/Button.vue';
import {
    DialogTitle,
    DialogTrigger,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: taskshome(),    
    },
];

const props = defineProps<{
    tasks: {
        data:Task[]
    }
}>()

type Task = {
    id: number,
    title: String,
    description: String,
    status: 'in_pending' | 'in_progress' | 'done'
}

</script>

<template>
    <Head title="Tasks"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <CreateTask :tasks="props.tasks"/>
            <DialogRoot>
                <DialogTrigger class="w-full">
                    <Button variant="outline" size="sm">
                        Edit your new task
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogDescription>
                        Edit your new task
                    </DialogDescription>
                    <DialogTitle>
                        Edit your task e add new information
                    </DialogTitle>
                <SelectList :tasks="props.tasks"/>
                </DialogContent>
            </DialogRoot>                            
            </div>
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border"
            >
                <div class="container" >
                    <label class="mb-2 block text-sm font-medium">Tarefas
                        <h1>tela de texto</h1>
                        <p>Em breve...</p>  
                    </label> 
                </div>
                <PlaceholderPattern />
            </div>
    </AppLayout>
</template>
