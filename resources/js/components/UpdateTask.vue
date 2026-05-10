<script setup lang="ts">
import { DialogRoot } from 'reka-ui';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Button from './ui/button/Button.vue';
import Input from './ui/input/Input.vue';
import { usePage } from '@inertiajs/vue3';
import {
    Alert,
    AlertTitle,
    AlertDescription,
} from '@/components/ui/alert';
import { 
    SelectTrigger,
    SelectItem,
    SelectContent,
    SelectValue,
    Select,
} from '@/components/ui/select';
import {
    DialogTitle,
    DialogContent,
    DialogDescription,
} from '@/components/ui/dialog';

const page = usePage()

const props = defineProps<{
    tasks: {
        data:Task[]
    }
}>()

type Task = {
    id: number,
    title: string,
    description: string,
    status: 'in_pending' | 'in_progress' | 'done'
}

const task_edit = ref({
    title: '',
    description: '',
    status: 'in_pending' as Task['status'],
})
const update_task = ref<Task>({
    id: 0,
    title: '',
    description: '',
    status: 'in_pending'
})

const selectedTask = ref<Task | null>(null)

watch(selectedTask, (task) => {
    if (!task) return
    update_task.value = { ...task }
})

const Task_update = () => {
    if (!update_task.value.id) return
    router.put(`/tasks_update ${update_task.value.id}`, update_task.value, {
        onSuccess: () => {
            update_task.value = {
            id: 0,
            title: '',
            description: '',
            status: 'in_pending',
            }
        }
    })
}
</script>

<template>
    <DialogRoot>
        <DialogTitle>
            Update your Task
        </DialogTitle>
        <DialogContent v-if = "task_edit.title">
            <DialogDescription>
                <p>Update your task details below:</p>
            </DialogDescription>
            <Input 
                v-model="task_edit.title" 
            />
            <textarea 
                v-model="task_edit.description"
                class="min-h-[100px]"
            />
            <Select v-model="task_edit.title">
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
                @click.stop="Task_update"
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






