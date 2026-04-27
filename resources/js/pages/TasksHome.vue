<script setup lang="ts">
import { 
    Head,
    router,
} from '@inertiajs/vue3';
import { taskshome } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { DialogRoot} from 'reka-ui';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import { ref } from 'vue';
import {
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';
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

const task = ref({
    title: '',
    description: '',
    status: 'pending',
})
const successMessage = ref('')

function addTask() {
  if (!task.value.title) return
  router.post('/tasks', task.value,{
    onSuccess: () => {
      task.value = {
        title: '',
        description: '',
        status: 'in_pending,in_progress,done',
      }
      successMessage.value ='Task created successfully!'
    }
  })
}
</script>

<template>
    <Head title="Tasks"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <DialogRoot>
            <DialogTitle> create your new task!!</DialogTitle>
                <DialogTrigger class="w-full">
                    <Button variant="outline" size="sm">
                        Add your new task
                    </Button>
                </DialogTrigger>
                    <DialogContent class="w-64 p-4 space-y-3":close-on-select="false">
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
                        <Select
                            v-model="task.status">
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
