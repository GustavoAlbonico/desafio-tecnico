import { StatusAtendimento } from "../enums/status-atendimento.enum";
import { EntidadeResumoModel } from "./entidade-resumo.model";

export interface AtendimentoApiModel {
  id: number,
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
  paciente: EntidadeResumoModel,
  medico: EntidadeResumoModel,
}

export interface AtendimentoModel {
  id: number,
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
  paciente: EntidadeResumoModel,
  medico: EntidadeResumoModel,
}

