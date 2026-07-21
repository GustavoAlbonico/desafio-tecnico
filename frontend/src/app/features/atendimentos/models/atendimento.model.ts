import { StatusAtendimento } from "../enums/status-atendimento.enum";

export interface AtendimentoModel {
  dataAtendimento: string,
  valorConsulta: string,
  status:StatusAtendimento,
  pacienteId: number,
  medicoId: number,
}

export interface AtendimentoListModel {
  id: number,
  dataAtendimento: string,
  valorConsulta: string,
  status:StatusAtendimento,
  pacienteNome: string,
  medicoNome: string,
}

export interface AtendimentoApiModel {
  data_atendimento: string,
  valor_consulta: string,
  status:StatusAtendimento,
  paciente_id: number,
  medico_id: number,
}

export interface AtendimentoApiListModel {
  id: number,
  data_atendimento: string,
  valor_consulta: string,
  status:StatusAtendimento,
  paciente_nome: string,
  medico_nome: string,
}

