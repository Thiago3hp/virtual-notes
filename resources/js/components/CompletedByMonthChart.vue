<script setup lang="ts">
import { computed } from 'vue';
import type { MonthlyCount } from '@/types';

const props = defineProps<{
    data: MonthlyCount[];
}>();

const max = computed(() => Math.max(1, ...props.data.map((d) => d.total)));
const total = computed(() => props.data.reduce((sum, d) => sum + d.total, 0));
</script>

<template>
    <div class="chart-card">
        <div class="chart-header">
            <div>
                <h3 class="chart-title">Chamados concluídos por mês</h3>
                <p class="chart-subtitle">Últimos {{ props.data.length }} meses</p>
            </div>
            <div class="chart-total">
                <span class="chart-total-number">{{ total }}</span>
                <span class="chart-total-label">no período</span>
            </div>
        </div>

        <div class="chart-bars">
            <div v-for="item in props.data" :key="item.month" class="chart-bar-col">
                <span class="chart-bar-value">{{ item.total }}</span>
                <div class="chart-bar-track">
                    <div
                        class="chart-bar-fill"
                        :style="{ height: `${(item.total / max) * 100}%` }"
                    />
                </div>
                <span class="chart-bar-label">{{ item.label }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chart-card {
    background: hsl(222 50% 6%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 0.9rem;
    padding: 1.5rem;
}

.chart-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.75rem;
}

.chart-title {
    font-size: 1rem;
    font-weight: 600;
    color: hsl(210 40% 96%);
}

.chart-subtitle {
    font-size: 0.75rem;
    color: hsl(215 20% 55%);
    margin-top: 0.15rem;
}

.chart-total {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.chart-total-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: hsl(142 71% 55%);
    line-height: 1;
}

.chart-total-label {
    font-size: 0.68rem;
    color: hsl(215 20% 55%);
    margin-top: 0.2rem;
}

.chart-bars {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    height: 14rem;
}

.chart-bar-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
}

.chart-bar-value {
    font-size: 0.75rem;
    font-weight: 600;
    color: hsl(210 40% 90%);
    margin-bottom: 0.4rem;
}

.chart-bar-track {
    flex: 1;
    width: 100%;
    display: flex;
    align-items: flex-end;
    background: hsl(222 40% 10%);
    border-radius: 0.4rem;
    overflow: hidden;
}

.chart-bar-fill {
    width: 100%;
    background: linear-gradient(180deg, hsl(217 91% 60%), hsl(217 91% 45%));
    border-radius: 0.4rem 0.4rem 0 0;
    min-height: 2px;
    transition: height 0.3s ease;
}

.chart-bar-label {
    font-size: 0.7rem;
    color: hsl(215 20% 55%);
    margin-top: 0.5rem;
}
</style>
