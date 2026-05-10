<script setup lang="ts">
import { ref } from 'vue';

import {
    Select,
    SelectItem,
    SelectValue,
    SelectTrigger,
    SelectContent,
} from '@/components/ui/select';

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

</script>

<template>
<Select v-model="task_edit">
    <SelectTrigger class="w-full rounded-lg border p-2">   
        <SelectValue placeholder="Select your task" />
    </SelectTrigger>    
        <SelectContent class="w-full rounded-lg border p-2">
            <SelectItem 
                v-for="task in props.tasks.data"  
                :key="task.id"
                :value="task"
                >
                {{ task.title }}
            </SelectItem>
        </SelectContent>
</Select>     
</template> 