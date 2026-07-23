<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\I18n\Date;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class AtendimentosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures necessárias para preparar o banco de dados de teste
     */
    protected array $fixtures = [
        'app.Atendimentos',
        'app.Medicos',
        'app.Pacientes',
    ];

    /**
     * Helper para configurar os cabeçalhos das requisições JSON da API
     */
    private function setupJsonHeaders(?array $payload = null): void
    {
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'input' => json_encode($payload)
        ]);
    }

    // =========================================================================
    // 1. GET /api/atendimentos (index)
    // =========================================================================

    public function testIndexReturnsPaginatedData(): void
    {
        $this->setupJsonHeaders();
        $this->get('/api/atendimentos');

        $this->assertResponseOk();
        $this->assertHeader('Content-Type', 'application/json');

        $response = json_decode((string)$this->_response->getBody(), true);

        // Valida estrutura da resposta com paginação
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
        $this->assertArrayHasKey('current_page', $response['pagination']);
    }

    // =========================================================================
    // 2. GET /api/atendimentos/{id} (view)
    // =========================================================================

    public function testViewSuccess(): void
    {
        $this->setupJsonHeaders();
        // ID 1 deve existir no seu fixture
        $this->get('/api/atendimentos/1');

        $this->assertResponseOk();
        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertTrue($response['success']);
        $this->assertEquals(1, $response['data']['id']);
    }

    public function testViewNotFound(): void
    {
        $this->setupJsonHeaders();
        $this->get('/api/atendimentos/99999'); // ID inexistente

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('message', $response);
    }

    // =========================================================================
    // 3. POST /api/atendimentos (add) - Validações do validarEntidade
    // =========================================================================

    public function testAddSuccess(): void
    {

        $payload = [
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => Date::now()->format('Y-m-d'), // Hoje
            'valor_consulta' => 150.00,
            'status' => 1
        ];

        $this->setupJsonHeaders($payload);

        $this->post('/api/atendimentos');

        $response = json_decode((string)$this->_response->getBody(), true);
        
        $this->assertResponseOk(); // ou 201 dependendo do seu controller
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        
    }

    public function testAddValidationErrorDateInPast(): void
    {
      
        $payload = [
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => Date::now()->subDays(1)->format('Y-m-d'), // Ontem
            'valor_consulta' => 100.00
        ];

        $this->setupJsonHeaders($payload);

        $this->post('/api/atendimentos');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('data_atendimento', $response['errors']);
        $this->assertEquals('Data precisar ser igual ou maior que hoje!', $response['errors']['data_atendimento']['periodo']);
    }

    public function testAddValidationErrorNegativeValue(): void
    {
    
        $payload = [
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => Date::now()->format('Y-m-d'),
            'valor_consulta' => -50.00 // Inválido
        ];

        $this->setupJsonHeaders($payload);

        $this->post('/api/atendimentos');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('valor_consulta', $response['errors']);
        $this->assertEquals('Valor precisa ser maior que zero!', $response['errors']['valor_consulta']['valor']);
    }

    public function testAddValidationErrorPacienteNotFound(): void
    {
        $payload = [
            'medico_id' => 1,
            'paciente_id' => 99999, // Não existe
            'data_atendimento' => Date::now()->format('Y-m-d'),
            'valor_consulta' => 100.00
        ];

        $this->setupJsonHeaders($payload);
        $this->post('/api/atendimentos');

        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('paciente_id', $response['errors']);
    }

    public function testAddValidationErrorDoctorDailyLimitExceeded(): void
    {
        // Supondo que a data X já tenha 15 ou mais agendamentos no seu Fixture para o medico_id = 1
        $payload = [
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => '2026-10-10', // Data onde o fixture já lotou o limite (15+)
            'valor_consulta' => 100.00
        ];

        $this->setupJsonHeaders($payload);
        $this->post('/api/atendimentos');

        $response = json_decode((string)$this->_response->getBody(), true);
        
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('medico_id', $response['errors']);
        $this->assertArrayHasKey('data_atendimento', $response['errors']);
    }

    // =========================================================================
    // 4. PUT /api/atendimentos/{id} (edit)
    // =========================================================================

    public function testEditSuccess(): void
    {

        $payload = [
            'valor_consulta' => 200.00,
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => Date::now()->format('Y-m-d')
        ];

        $this->setupJsonHeaders($payload);

        $this->put("/api/atendimentos/1");

        $response = json_decode((string)$this->_response->getBody(), true);
        
        $this->assertResponseOk();
        $this->assertTrue($response['success']);
    }

    public function testEditValidationErrorWhenStatusIsConcluidoOrCancelado(): void
    {

        $payload = [
            'status' => 2, // Ou Cancelado
            'valor_consulta' => 100.00,
            'medico_id' => 1,
            'paciente_id' => 1,
            'data_atendimento' => Date::now()->format('Y-m-d')
        ];

        $this->setupJsonHeaders($payload);

        // ID 2 deve ter o status Concluido ou Cancelado no fixture
        $this->put('/api/atendimentos/2');

        $response = json_decode((string)$this->_response->getBody(), true);
        
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('status', $response['errors']);
        $this->assertEquals(
            'Não é possivel alterar status de agendamento Concluído ou Cancelado!',
            $response['errors']['status']['invalido']
        );
    }

    // =========================================================================
    // 5. DELETE /api/atendimentos/{id} (delete)
    // =========================================================================

    public function testDeleteSuccess(): void
    {
        $this->setupJsonHeaders();

        $this->delete('/api/atendimentos/1');

        $this->assertResponseOk();
        $response = json_decode((string)$this->_response->getBody(), true);

        $this->assertTrue($response['success']);
    }
}