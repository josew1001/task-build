<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Tests\TestCase;
use App\Models\Tasks;
use App\Models\User;
use App\Models\Comments;


class CommentsControllerTest extends TestCase
{
    // use RefreshDatabase; 
    use DatabaseTransactions;

    /** @test */
    public function it_returns_task_and_comments_for_a_valid_task_id()
    {
        // Criando um usuário fictício
        $user = User::factory()->create();

        // Criando uma task fictícia
        $task = Tasks::factory()->create([
            'user_created_id' => $user->id,
        ]);

        // Criando um comentário para essa task
        $comment = Comments::factory()->create([
            'task_id' => $task->id,
            'user_created_id' => $user->id,
        ]);

        // Fazendo a requisição para a rota index com um task_id válido
        $response = $this->getJson(route('comments.index', ['talkId' => $task->id]));
        // $response = $this->getJson(route('/api/taskDescription', ['talkId' => $task->id]));        
        // $response = $this->getJson('/api/task', $queryParams);
        // let response = await axios.post('/api/taskDescription', formComment);

        // Verificando se a resposta está correta
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'task',
                     'comments',
                     'users'
                 ])
                 ->assertJsonFragment([
                     'id' => $task->id,
                     'user_created_id' => $user->id
                 ]);
    }

    /** @test */
    public function it_returns_404_if_task_not_found()
    {
        // Testando um ID de tarefa que não existe
        $response = $this->getJson(route('comments.index', ['talkId' => 999]));

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Task not found'
                 ]);
    }

    /** @test */
    public function it_stores_a_new_comment_and_updates_task()
    {
        // Criando um usuário e uma tarefa fictícia
        $user = User::factory()->create();
        $task = Tasks::factory()->create();

        // Dados da requisição simulada
        $requestData = [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'task_status' => 'completed',
            'content' => 'Este é um novo comentário de teste'
        ];

        // Fazendo a requisição POST para armazenar um novo comentário
        // $response = $this->postJson(route('comments.store'), $requestData);
        $response = $this->getJson(route('/api/taskDescription', ['formComment' => $requestData]));
        // $response = $this->getJson('/api/task', $queryParams);

        // Verificando se a resposta está correta
        $response->assertStatus(200);

        // Verificando se o comentário foi criado no banco de dados
        $this->assertDatabaseHas('comments', [
            'task_id' => $task->id,
            'user_created_id' => $user->id,
            'content' => 'Este é um novo comentário de teste'
        ]);

        // Verificando se a tarefa foi atualizada corretamente
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_updated_id' => $user->id,
            'status' => 'completed'
        ]);
    }




    // _____----------->>>>FUNFUNDO
     /** @test */
    // public function it_returns_task_comments_and_users_for_a_valid_task_id()
    // {
    //     // Criar um usuário fictício
    //     $user = User::factory()->create();

    //     // Criar uma tarefa fictícia
    //     $task = Tasks::factory()->create([
    //         'user_created_id' => $user->id,
    //     ]);
      
    //     // Criar um comentário associado à tarefa
    //     $comment = Comments::factory()->create([
    //         'task_id' => $task->id,
    //         'user_created_id' => $user->id,
    //     ]);
      
    //     // Fazer a requisição para o método index passando um task_id válido
    //     $response = $this->getJson('/api/taskDescription?talkId=' . $task->id);
      
    //     // Verificar se a resposta tem status 200 (OK)
    //     $response->assertStatus(200)
    //         ->assertJsonStructure([
    //             'task',
    //             'comments',
    //             'users'
    //         ])
    //         ->assertJsonFragment([
    //             'id' => $task->id,
    //             'user_created_id' => $user->id
    //         ]);
    // }

}
