<script setup lang="ts">
import { 
    Head,router
} from '@inertiajs/vue3';
import { taskshome } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { DialogRoot } from 'reka-ui';
import Button from '@/components/ui/button/Button.vue';
import { usePage } from '@inertiajs/vue3';
import Input from '@/components/ui/input/Input.vue';
import {
    DialogTitle,
    DialogTrigger,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog';
import{
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';
import {
    Alert,
    AlertTitle,
    AlertDescription,
} from '@/components/ui/alert';

const page =usePage()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: taskshome(),    
    },
];

const props = defineProps<{
    task: {
        data:Task[]
    }
}>()

type Task = {
    id: number,
    title: string,
    description: string,
    status: 'in_pending' | 'in_progress' | 'done'
}

const task = ref({
    title: '',
    description: '',
    status: 'status' as Task['status'],
})

function addTask() {
  if (!task.value.title) return
  router.post('/tasks', task.value,{
    onSuccess: () => {
      task.value = {
        title: '',
        description: '',
        status: 'status' as Task['status'],
      }
    }
  })
}
function updateTask() {
  if (!task_edit.value.title) return
  router.post('/tasks_edit', task_edit.value,{
    onSuccess: () => {
      task_edit.value = {
        title: '',
        description: '',
        status: 'status' as Task['status'],
      }
    }
  })
}
const task_edit = ref({
    title: '',
    description: '',
    status: 'in_pending' as Task['status'],
})

</script>

<template>
    <Head title="Tasks"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <DialogRoot>
                <DialogTrigger class="w-full">
                    <Button variant="outline" size="sm">
                        Add your new task
                    </Button>
                </DialogTrigger>
                <DialogContent class="w-64 p-4 space-y-3" :close-on-select="false">
                    <DialogTitle> create your new task!!</DialogTitle>
                    <DialogDescription>
                        Add your new task
                    </DialogDescription>
                    <DialogTitle>
                        Add new task
                    </DialogTitle>    
                    <Input
                        id="task"
                        v-model="task.title" 
                        class="w-full rounded-lg border p-2"
                        placeholder="What is your task??"
                    />
                    <textarea
                        v-model="task.description"
                        placeholder="describe your task"
                        class="min-h-[100px]"
                    />
                    <Select v-model="task.status">
                        <SelectTrigger class="w-full rounded-lg border p-2">   
                            <SelectValue placeholder="Select task status" /> 
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="in_pending">Pending</SelectItem>
                            <SelectItem value="in_progress">In Progress</SelectItem>
                            <SelectItem value="done">Done</SelectItem>
                        </SelectContent>
                    </Select>
                    <Button
                        @click.stop="addTask"
                        class ="w-full  bg-black text-white rounded-lg p-2" 
                        >
                        create task
                    </Button>
                    <Alert v-if="(page.props.flash as any)">
                        <AlertTitle>
                            Sucess  
                        </AlertTitle>
                        <AlertDescription>
                            {{ (page.props.flash as any )}}
                        </AlertDescription>
                    </Alert>
                </DialogContent>
            </DialogRoot>
                <DialogRoot>
                   <DialogContent>
                    <Select v-model="task_edit.status">
                        <SelectTrigger class="w-full rounded-lg border p-2">   
                            <SelectValue placeholder="Select your task" />
                        </SelectTrigger>    
                        <SelectContent class="w-full rounded-lg border p-2">
                            <SelectItem 
                                v-for="item in props.task?.data || []"  
                                :key="item.id"
                                :value="item"
                                >
                                {{ item.title }}
                            </SelectItem>
                        </SelectContent>
                    </Select>     
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
