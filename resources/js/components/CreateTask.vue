<script setup lang="ts">
import {
    DialogTitle,
    DialogTrigger,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import{
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';
import Button from '@/components/ui/button/Button.vue';
import {ref} from 'vue';
import { router } from '@inertiajs/vue3';
import {
    Alert,
    AlertTitle,
    AlertDescription,
} from '@/components/ui/alert';
import { usePage } from '@inertiajs/vue3';

const page =usePage()

type Task = {
    id: number,
    title: string,
    description: string,
    status: 'in_pending' | 'in_progress' | 'done'
}

const props = defineProps<{
    tasks: {
        data:Task[]
    }
}>()

const task = ref({
    title: '',
    description: '',
    status: 'in_pending' as Task['status'],
})

function addTask() {
  if (!task.value.title) return
  router.post('/tasks', task.value,{
    onSuccess: () => {
      task.value = {
        title: '',
        description: '',
        status: 'in_pending',
      }
    }
  })
}

</script>

<template>
    <DialogRoot children>
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
            <Alert v-if="(page.props.flash as any).success">
                <AlertTitle>
                    Sucess  
                </AlertTitle>
                <AlertDescription>
                   {{ (page.props.flash as any ).success }}
                        </AlertDescription>
            </Alert>
        </DialogContent>    
    </DialogRoot>
</template>