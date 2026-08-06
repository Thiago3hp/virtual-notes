export type ChamadoStatus = 'aberto' | 'em_andamento' | 'fechado';
export type ChamadoPrioridade = 'Baixa' | 'Normal' | 'Alta' | 'Urgente';

export type Chamado = {
    id: number;
    solicitante_nome: string | null;
    setor: string;
    problema: string;
    descricao: string | null;
    sala: string | null;
    solicitante_numero: string;
    numero_adicional: string | null;
    status: ChamadoStatus;
    prazo: string | null;
    prioridade: ChamadoPrioridade;
    tecnico_nome: string | null;
    laudo_tecnico: string | null;
    avaliacao: number | null;
    criado_em: string | null;
    fechado_em: string | null;
};

// Campos que o painel pode de fato editar (solicitante_* vem do bot do
// WhatsApp e fica travado por aqui).
export type ChamadoFormData = {
    setor: string;
    problema: string;
    descricao: string | null;
    sala: string | null;
    status: ChamadoStatus;
    prazo: string | null;
    prioridade: ChamadoPrioridade;
    tecnico_nome: string | null;
    laudo_tecnico: string | null;
};

// Usado só quando o técnico cria um chamado manualmente pelo painel (não
// veio do WhatsApp) -- aqui sim solicitante_nome/numero são obrigatórios,
// porque a coluna solicitante_numero é NOT NULL no banco.
export type ChamadoCreateData = ChamadoFormData & {
    solicitante_nome: string;
    solicitante_numero: string;
};
