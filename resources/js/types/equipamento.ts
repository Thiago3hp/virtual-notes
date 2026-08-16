export type Equipamento = {
    id: number;
    nome: string;
    descricao: string | null;
    quantidade: number;
    chamado_id: number | null;
    chamado: { id: number; problema: string } | null;
};
