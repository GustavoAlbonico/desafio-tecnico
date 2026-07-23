#!/bin/bash
set -e

echo "=== [1/5] Aguardando inicialização do banco de dados ==="
sleep 5

echo "=== [2/5] Rodando as Migrations ==="
php bin/cake.php migrations migrate

echo "=== [3/5] Rodando os Seeds ==="
php bin/cake.php seeds run --quiet

echo "=== [4/5] Rodando os Migrations no banco de TESTE ==="
php bin/cake.php migrations migrate --connection=test

echo "=== [5/5] Iniciando o Servidor CakePHP ==="
exec php bin/cake.php server -H 0.0.0.0 -p 8765