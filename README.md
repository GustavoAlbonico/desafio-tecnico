# Desafio Técnico

Bem-vindo ao desafio técnico da Unimed.

O objetivo deste teste é avaliar conhecimentos fundamentais necessários para o desenvolvimento de aplicações modernas, desde a modelagem de dados até a construção de uma aplicação completa utilizando boas práticas de arquitetura.

---
# Avisos antes de começar
- Leia com atenção este documento todo e tente seguir ao **máximo** as instruções
- Crie um fork deste repositório no seu github **sem citar o nome da Unimed**
- Faça seus commits no seu repositório
- Quando finalizar envie o link do seu repositório para o e-mail **do recrutador responsável**
- Você poderá consultar suas ferramentas habituais e até projetos particulares
- Fique à vontade para questionar o(s) recrutador(es)
- Fique tranquilo, respire. Boa sorte!

*Corpo do e-mail com o link do repositório do desafio*

- Seu nome
- Nome do recrutador
- Link do repositório
---

# Objetivo

Desenvolver uma pequena aplicação capaz de registrar atendimentos médicos, armazenar os dados em banco de dados e disponibilizar uma interface para cadastro e consulta dessas informações.

O foco não é a complexidade do sistema, mas sim a qualidade da implementação.

---

# Conhecimentos avaliados

Durante a avaliação serão observados conhecimentos em:

- Docker
- Banco de Dados (modelagem e SQL)
- Git
- Programação Orientada a Objetos (POO)
- Arquitetura em camadas
- Git Flow (Fork, Clone e Pull Request)
- HTML
- CSS
- JavaScript
- Backend
- APIs REST
- Integração com banco de dados

---

# Requisitos

## 1. Banco de Dados

A partir do arquivo `atendimento.json`, modele um banco de dados capaz de armazenar as informações. Este arquivo pode ser usado para realizar o "seeder" do banco.

Fique à vontade para definir:

- quantidade de tabelas
- relacionamentos
- chaves primárias
- chaves estrangeiras

Desde que a modelagem faça sentido para o domínio.

Após criar o banco, importe todos os registros do arquivo JSON.

---

## 2. Backend

Desenvolva uma API REST utilizando a arquitetura abaixo:

```
Controller
    ↓
Service
    ↓
Repository
```

### Repository

Responsável por:

- acesso ao banco
- consultas
- inserções
- atualizações
- remoções

### Service

Responsável por:

- regras de negócio
- validações
- formatação
- transporte de dados
- tratamento de exceções

### Controller

Responsável por:

- receber requisições
- retornar respostas HTTP
- consumir os Services

---

## 3. Frontend

Desenvolva duas telas.

### Cadastro de Atendimento

Permitir cadastrar um novo atendimento.

Campos:

- Nome do paciente
- Nome do médico
- Data do atendimento
- Valor da consulta

---

### Listagem de Atendimentos

Exibir os atendimentos cadastrados.

A listagem deverá apresentar, no mínimo:

- Nome do paciente
- Nome do médico
- Data
- Valor da consulta

---

# Docker

Toda a aplicação deve ser executável utilizando Docker.

Fique à vontade para utilizar Docker Compose.

---

# Git

O desafio deverá ser desenvolvido utilizando Git.

Fluxo esperado:

- Fork do repositório
- Clone
- Commits durante o desenvolvimento
- Pull Request para entrega

Será observado:

- organização dos commits
- clareza das mensagens
- utilização correta do Git

---

# Diferenciais

Não são obrigatórios, mas contarão pontos positivos.

- Paginação
- Busca por paciente
- Busca por médico
- Ordenação da listagem
- Testes automatizados
- Validação de formulários
- Tratamento de erros
- Logs
- Seed para popular banco
- Migrações
- README bem documentado
- Uso de ORM
- Boas práticas de Clean Code
- Tratamento de exceções
- Documentação da API (Swagger/OpenAPI)

---

# O que será avaliado

- Organização do projeto
- Arquitetura utilizada
- Modelagem do banco
- Código limpo
- Organização das pastas
- POO
- Separação de responsabilidades
- Boas práticas
- Legibilidade do código
- Qualidade do Frontend
- Qualidade da API
- Uso correto do Git
- Funcionamento geral da aplicação

---

# Tecnologias

Utilizar o PHP e o framework de sua preferência.

Exemplos:

- Laravel
- HyperF
- Slim
- Symfony
- CakePHP

Frontend:

- HTML
- CSS
- JavaScript
- React
- Angular
- Vue

Banco:

- PostgreSQL
- MySQL
- SQL Server
- Oracle
- SQLite

---

# Entrega

O repositório deverá conter:

- Código-fonte
- Dockerfile
- docker-compose.yml (caso utilize)
- README com instruções de execução

O projeto deve subir apenas executando os comandos descritos no README.

---

Boa sorte!
