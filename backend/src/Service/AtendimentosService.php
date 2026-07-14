<?php 

namespace App\Service;

use App\Error\Exception\EntityValidationException;
use App\Model\Entity\Atendimento;
use App\Repository\AtendimentosRepository;
use App\Service\Interface\IService;
use Cake\Datasource\Paging\PaginatedInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Date;

class AtendimentosService implements IService {

    private array $paginate;
    private array $filters;

    public function __construct(private AtendimentosRepository $atendimentosRepository) {
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

        $this->validarEntidade($atendimentoEntity,isEdit:true);

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
 
        /* validando valor da consulta */
        if((float) $atendimentoEntity->valor_consulta < 0){
            $atendimentoEntity->setError("valor_consulta",[ "valor" => "Valor precisa ser maior que zero!"]);
        }

        /* validando data de atendimento */
        $data = Date::parse($atendimentoEntity->data_atendimento);
        $hoje = Date::now();

        if ($data->lessThan($hoje) && !$isEdit) { /* apenas se for create vai validar */
            $atendimentoEntity->setError("data_atendimento",[ "periodo" => "Data Inválida!"]);
        }

    }
}