import { AtendimentoApiListModel, AtendimentoApiModel, AtendimentoListModel, AtendimentoModel } from "../models/atendimento.model";

export const mapAtendimento = (atendimentoApi: AtendimentoApiModel): AtendimentoModel => {
  return {
    id: atendimentoApi.id,
    dataAtendimento: atendimentoApi.data_atendimento,
    valorConsulta: atendimentoApi.valor_consulta,
    status: atendimentoApi.status,
    pacienteId: atendimentoApi.paciente_id,
    medicoId: atendimentoApi.medico_id,
  }
}

export const mapAtendimentoList = (atendimentoListApi: AtendimentoApiListModel): AtendimentoListModel => {
  return {
    id: atendimentoListApi.id,
    dataAtendimento: atendimentoListApi.data_atendimento,
    valorConsulta: atendimentoListApi.valor_consulta,
    status: atendimentoListApi.status,
    pacienteNome: atendimentoListApi.paciente_nome,
    medicoNome: atendimentoListApi.medico_nome,
  }
}