#!/bin/bash
set -e

echo "=== [1/4] Aguardando inicialização do banco de dados ==="
sleep 5

echo "=== [2/4] Rodando as Migrations ==="
php bin/cake.php migrations migrate

echo "=== [3/4] Rodando os Seeds ==="
php bin/cake.php migrations seed

echo "=== [4/4] Iniciando o Servidor CakePHP ==="
exec php bin/cake.php server -H 0.0.0.0 -p 8765