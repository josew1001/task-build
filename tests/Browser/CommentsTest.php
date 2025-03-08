<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\tasks;
use App\Models\comments;

class CommentsTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Laravel');
        });
    }
    public function it_can_create_a_comment()
    {
        // Criar um usuário e uma tarefa de teste
        $user = User::factory()->create();
        $task = tasks::factory()->create([
            'user_created_id' => $user->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $task) {
            $browser->loginAs($user) // Simula login do usuário
                ->visit("/tasks/{$task->id}") // Acessa a página da tarefa
                ->type('#comment', 'Este é um comentário de teste') // Digita no campo de comentário
                ->press('New Comment') // Clica no botão
                ->waitForText('Este é um comentário de teste') // Aguarda o comentário aparecer
                ->assertSee('Este é um comentário de teste'); // Verifica se foi salvo
        });
    }
}
