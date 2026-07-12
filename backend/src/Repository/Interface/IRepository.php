<?php 

namespace App\Repository\Interface;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\PaginatedInterface;

interface IRepository {
    public function findAll():PaginatedInterface;
    public function findById(int $id):?EntityInterface;
    public function create(EntityInterface $entity):bool;
    public function update(EntityInterface $entity):bool;
    public function delete(EntityInterface $entity):bool;
    public function patchEntity(EntityInterface $entity, array $data): EntityInterface;
}