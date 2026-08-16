export type ChamadosSummary = {
    total: number;
    abertos: number;
    em_andamento: number;
    fechados: number;
};

export type ClientesSummary = {
    total: number;
};

export type ClienteTop = {
    nome: string;
    total: number;
} | null;

export type EquipamentosSummary = {
    total: number;
    quantidade_total: number;
    vinculados_a_os: number;
};
