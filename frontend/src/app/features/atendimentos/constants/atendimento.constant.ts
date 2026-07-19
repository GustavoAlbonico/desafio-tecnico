import { StatusAtendimento } from "../enums/status-atendimento.enum";


export const STATUS_ATENDIMENTO_LABELS: Record<StatusAtendimento, string> = {
  [StatusAtendimento.Agendado]: 'Agendado',
  [StatusAtendimento.Confirmado]: 'Confirmado',
  [StatusAtendimento.Cancelado]: 'Cancelado',
};

export const ATENDIMENTO_SORT_FIELD_MAP: Record<string, string> = {
  id: 'id',
  dataAtendimento: 'data_atendimento',
  valorConsulta: 'valor_consulta',
  status: 'status',
  paciente: 'paciente_nome',
  medico: 'medico_nome'
};