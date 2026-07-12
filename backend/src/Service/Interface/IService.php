<?php 

namespace App\Service\Interface;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Paging\PaginatedInterface;

interface IService {
    public function list(): PaginatedInterface;
    public function findById(int $id): ?EntityInterface;
    public function create(array $data): EntityInterface | bool;
    public function update(int $id, array $data): EntityInterface | bool;
    public function delete(int $id): bool;
}