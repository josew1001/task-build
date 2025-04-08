<?php

namespace Tests\Feature;

use App\Models\User;
// reset the database before each test
// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Tasks;

class TasksControllerTest extends TestCase
{
    // reset the database before each test
    // use RefreshDatabase;
    use DatabaseTransactions;

    /** @test */
    public function it_can_list_tasks()
    {
        // Simular requisição GET para listar as tarefas
        $response = $this->getJson('/api/task');

        // Verifica se a resposta foi 200 (OK)
        $response->assertStatus(200);

        // Verifica se a estrutura da resposta está correta (mesmo que esteja vazia)
        $response->assertJsonStructure([
            'tasks'
        ]);
    }

    /** @test */
    public function it_can_fetch_a_specific_task()
    {
        // Simulando parâmetros de consulta
        $queryParams = [
            'searchQuery' => 'teste',
            'assignedUser' => 1,
            'building' => 2,
            'startDate' => '2024-03-01',
            'endDate' => '2024-03-31'
        ];

        // Simular requisição GET passando os parâmetros
        $response = $this->getJson('/api/task', $queryParams);

        // Verifica se a resposta foi 200 (OK)
        $response->assertStatus(200);

        // Verifica se a estrutura da resposta está correta (mesmo que esteja vazia)
        $response->assertJsonStructure([
            'tasks' => [
                '*' => ['id', 'title', 'description', 'status', 'building_id', 'user_created_id']
            ]
        ]);
    }

    public function it_can_create_a_comment()
    {
        // Criar uma tarefa e um usuário para associar ao comentário
        $user = User::factory()->create();
        $task = Tasks::factory()->create();

        // Dados do comentário fictício
        $commentData = [
            'content' => 'Este é um comentário de teste.',
            'task_id' => $task->id,
            'user_id' => $user->id
        ];

        // Simular requisição POST para criar o comentário
        $response = $this->postJson('/api/taskDescription', $commentData);

        // Verifica se a resposta foi 201 (Criado)
        $response->assertStatus(201);

        // Verifica se o comentário foi salvo no banco
        $this->assertDatabaseHas('comments', [
            'content' => 'Este é um comentário de teste.',
            'task_id' => $task->id,
            'user_created_id' => $user->id
        ]);
    }
}
