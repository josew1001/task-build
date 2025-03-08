<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\tasks;
use App\Models\User;
use App\Models\buildings;

class TasksControllerTest extends TestCase
{
    use RefreshDatabase; // Garante um banco limpo antes de cada teste
    use WithFaker; // Gera dados aleatórios

    /** @test */
    public function it_can_create_a_task()
    {
        // Criar um usuário e um prédio fictício
        $user = User::factory()->create();
        $building = buildings::factory()->create();

        // Dados da tarefa fictícia
        $taskData = [
            'title' => 'Nova Tarefa de Teste',
            'description' => 'Descrição da tarefa de teste',
            'status' => 'open',
            'building' => $building->id,
            'user_id' => $user->id,
        ];

        // Faz uma requisição POST para criar a tarefa
        $response = $this->postJson('/api/tasks', $taskData);

        // Verifica se a resposta foi 201 (Criado)
        $response->assertStatus(201);

        // Verifica se a tarefa foi salva no banco
        $this->assertDatabaseHas('tasks', [
            'title' => 'Nova Tarefa de Teste',
            'description' => 'Descrição da tarefa de teste',
            'status' => 'open',
            'building_id' => $building->id,
            'user_created_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_list_tasks()
    {
        // Criar usuário, prédio e tarefa
        $user = User::factory()->create();
        $building = buildings::factory()->create();
        tasks::factory()->count(5)->create([
            'user_created_id' => $user->id,
            'building_id' => $building->id,
        ]);

        // Faz uma requisição GET para listar as tarefas
        $response = $this->getJson('/api/tasks');

        // Verifica se a resposta foi 200 (OK)
        $response->assertStatus(200);

        // Verifica se pelo menos uma tarefa está na resposta
        $response->assertJsonStructure([
            'tasks' => [
                '*' => ['id', 'title', 'description', 'status', 'building_id', 'user_created_id'],
            ],
            'buildings',
            'users'
        ]);
    }
}
