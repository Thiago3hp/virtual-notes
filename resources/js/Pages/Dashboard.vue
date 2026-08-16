<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import HeaderHero from '@/components/HeaderHero.vue';
import CompletedByMonthChart from '@/components/CompletedByMonthChart.vue';
import { dashboard } from '@/routes';
import type {
    BreadcrumbItem,
    ChamadosSummary,
    ClientesSummary,
    ClienteTop,
    EquipamentosSummary,
    MonthlyCount,
} from '@/types';

const props = defineProps<{
    chamadosSummary: ChamadosSummary;
    clientesSummary: ClientesSummary;
    clienteTop: ClienteTop;
    equipamentosSummary: EquipamentosSummary;
    concluidosPorMes: MonthlyCount[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Virtual notes',
        href: dashboard(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <HeaderHero />

        <div class="grid auto-rows-min gap-4 p-4 md:grid-cols-3">
            <Link href="/chamadoshome" class="summary-card">
                <span class="summary-label">Chamados</span>
                <span class="summary-value">{{ props.chamadosSummary.total }}</span>
                <div class="summary-breakdown">
                    <span class="summary-pill summary-pill--aberto">{{ props.chamadosSummary.abertos }} abertas</span>
                    <span class="summary-pill summary-pill--em_andamento">{{ props.chamadosSummary.em_andamento }} em andamento</span>
                    <span class="summary-pill summary-pill--fechado">{{ props.chamadosSummary.fechados }} concluídas</span>
                </div>
            </Link>

            <Link href="/clientes" class="summary-card">
                <span class="summary-label">Clientes</span>
                <span class="summary-value">{{ props.clientesSummary.total }}</span>
                <p v-if="props.clienteTop" class="summary-hint">
                    {{ props.clienteTop.nome }} tem mais chamados ({{ props.clienteTop.total }})
                </p>
                <p v-else class="summary-hint">Nenhum chamado registrado ainda</p>
            </Link>

            <Link href="/equipamentos" class="summary-card">
                <span class="summary-label">Equipamentos</span>
                <span class="summary-value">{{ props.equipamentosSummary.total }}</span>
                <p class="summary-hint">
                    {{ props.equipamentosSummary.quantidade_total }} unidades no total ·
                    {{ props.equipamentosSummary.vinculados_a_os }} vinculados a uma OS
                </p>
            </Link>
        </div>

        <div class="p-4 pt-0">
            <CompletedByMonthChart :data="props.concluidosPorMes" />
        </div>
    </AppLayout>
</template>

<style scoped>
.summary-card {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    background: hsl(222 50% 6%);
    border: 1px solid hsl(222 33% 14%);
    border-radius: 0.9rem;
    padding: 1.25rem;
    text-decoration: none;
    transition: border-color 0.15s ease, background 0.15s ease;
}

.summary-card:hover {
    border-color: hsl(217 91% 60% / 0.5);
    background: hsl(222 46% 8%);
}

.summary-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: hsl(215 20% 55%);
}

.summary-value {
    font-size: 2rem;
    font-weight: 700;
    color: hsl(210 40% 96%);
    line-height: 1;
}

.summary-hint {
    font-size: 0.78rem;
    color: hsl(215 20% 55%);
}

.summary-breakdown {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin-top: 0.15rem;
}

.summary-pill {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
    white-space: nowrap;
}

.summary-pill--aberto {
    background: hsl(217 91% 60% / 0.14);
    color: hsl(213 94% 68%);
}

.summary-pill--em_andamento {
    background: hsl(38 92% 50% / 0.16);
    color: hsl(38 92% 62%);
}

.summary-pill--fechado {
    background: hsl(142 71% 45% / 0.16);
    color: hsl(142 71% 58%);
}
</style>
