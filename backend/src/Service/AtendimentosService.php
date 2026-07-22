<?php 

namespace App\Service;

use App\Error\Exception\EntityValidationException;
use App\Model\Entity\Atendimento;
use App\Model\Enum\StatusAtendimento;
use App\Repository\AtendimentosRepository;
use App\Repository\MedicosRepository;
use App\Repository\PacientesRepository;
use App\Service\Interface\IService;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Date;

class AtendimentosService implements IService {

    private array $paginate;
    private array $filters;

    private const DAILY_DOCTOR_APPOINTMENT_LIMIT = 15;

    public function __construct(
        private AtendimentosRepository $atendimentosRepository,
        private MedicosRepository $medicosRepository,
        private PacientesRepository $pacientesRepository,
    ) {
    }

    public function list(): PaginatedInterface{
        return $this->atendimentosRepository
            ->filters($this->filters)
            ->paginate($this->paginate)
            ->findAll();
    }

    public function findById(int $id): ?Atendimento{
        return $this->atendimentosRepository->findById($id);
    }

    public function create(array $data): Atendimento | bool{
        $atendimentoEntity = $this->atendimentosRepository->patchEntity(null,$data);

        $this->validarEntidade($atendimentoEntity);

        if($atendimentoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Atendimento está inválida, favor verificar!",$atendimentoEntity->getErrors());
        }

        return $this->atendimentosRepository->create($atendimentoEntity);
    }

    public function update(int $id, array $data): Atendimento | bool{
        $atendimento = $this->atendimentosRepository->findById($id);

        if(!$atendimento){
            throw new NotFoundException("Atendimento com id {$id} não encontrado, favor verificar!", 404);
        }

        $atendimentoEntity = $this->atendimentosRepository->patchEntity($atendimento, $data);

        $this->validarEntidade($atendimentoEntity,isEdit: true);

        if($atendimentoEntity->hasErrors()){
            throw new EntityValidationException("Entidade Atendimento está inválida, favor verificar!",$atendimentoEntity->getErrors());
        }

        return $this->atendimentosRepository->update($atendimentoEntity);
    }

    public function delete(int $id): bool{
        $atendimentoEntity = $this->atendimentosRepository->findById($id);

        if(!$atendimentoEntity){
            throw new NotFoundException("Atendimento com id {$id} não encontrado, favor verificar!", 404);
        }

        return $this->atendimentosRepository->delete($atendimentoEntity);
    }

    public function paginate(array $paginate):self{
        $this->paginate = $paginate;
        return $this;
    }

    public function filters(array $filters):self{
        $this->filters = $filters;
        return $this;
    }

    private function validarEntidade(Atendimento $atendimentoEntity, bool $isEdit = false):void {
 
        /* validando data de atendimento */
        $date = Date::parse($atendimentoEntity->data_atendimento);
        $today = Date::now();

        /* Só pode agendar atendimentos para o mesmo dia ou maior */
        if ($date->lessThan($today)) {
            $atendimentoEntity->setError("data_atendimento",[ "periodo" => "Data precisar ser igual ou maior que hoje!"]);
        }

        /* validando valor da consulta */
        if((float) $atendimentoEntity->valor_consulta < 0){
            $atendimentoEntity->setError("valor_consulta",[ "valor" => "Valor precisa ser maior que zero!"]);
        }

        /* valida se o medico_id existe */
        $medico = $this->medicosRepository->findById($atendimentoEntity->medico_id);
        if(!$medico){
            $atendimentoEntity->setError("paciente_id",[ "existe" => "Paciente com id {$atendimentoEntity->paciente_id} não existe!"]);
        }

        /* valida se o paciente_id existe */
        $paciente = $this->pacientesRepository->findById($atendimentoEntity->paciente_id);
        if(!$paciente){
            $atendimentoEntity->setError("paciente_id",[ "existe" => "Paciente com id {$atendimentoEntity->paciente_id} não existe!"]);
        }

        $countAgendamentos = $this->atendimentosRepository->countByMedicoIdAndDataAtendimento(
            $atendimentoEntity->medico_id,
            $atendimentoEntity->data_atendimento
        );

        /* Não pode possuir mais de 15 agendamentos para o mesmo medico no mesmo dia */
        if($countAgendamentos > self::DAILY_DOCTOR_APPOINTMENT_LIMIT){
            $atendimentoEntity
                ->setError("medico_id",[ "regraNegocio" =>  'Limite máximo de ' . self::DAILY_DOCTOR_APPOINTMENT_LIMIT . ' agendamentos por dia e mesmo médico.'])
                ->setError("data_atendimento",[ "regraNegocio" =>  'Limite máximo de ' . self::DAILY_DOCTOR_APPOINTMENT_LIMIT . ' agendamentos por dia e mesmo médico.']);
        }

        if($isEdit){
            /* Só pode alterar status de agendamento agendado */
            switch($atendimentoEntity->getOriginal('status')){
                case StatusAtendimento::Concluido->value:
                case StatusAtendimento::Cancelado->value:
                    $atendimentoEntity->setError("status",[ "invalido" => "Não é possivel alterar status de agendamento Concluído ou Cancelado!"]);
                break;
            }

        }
    }
}