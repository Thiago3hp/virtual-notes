<template>
    
    <div class="container" >
        <h1>Minhas Tarefas</h1>
        <div class="task-list">
            <div class="task-item" v-for="task in tasks" :key="task.id">
                <h2>{{ task.title }}</h2>
                <p>{{ task.description }} </p>
                <p>Status: {{ task.status }}</p>
            </div>
        </div>    
    </div>

</template>
<script setup>

import { ref } from 'vue';


</script>