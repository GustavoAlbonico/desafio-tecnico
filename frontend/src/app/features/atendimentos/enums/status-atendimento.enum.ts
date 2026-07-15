export enum StatusAtendimento {
  Agendado = 1,
  Confirmado = 2,
  Cancelado = 3,
}

export const STATUS_ATENDIMENTO_LABELS: Record<StatusAtendimento, string> = {
  [StatusAtendimento.Agendado]: 'Agendado',
  [StatusAtendimento.Confirmado]: 'Confirmado',
  [StatusAtendimento.Cancelado]: 'Cancelado',
};