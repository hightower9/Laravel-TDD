<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $list;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->user = $this->authUser();
        $this->list = $this->createTodoList(['user_id' => $this->user->id]);
    }

    public function test_get_todo_lists(): void
    {
        // Preparation
        // TodoList::factory()->create();
        $this->createTodoList();

        // action
        $res = $this->getJson(route('todo-lists.index'))
            ->assertOk()->json('data');

        // assertion
        $this->assertEquals(1, count($res));
    }

    public function test_get_a_todo_list(): void
    {
        $res = $this->getJson(route('todo-lists.show', $this->list->id))
            ->assertOk()->json('data.name');

        $this->assertEquals($this->list->name, $res);
    }

    public function test_store_new_todo_list(): void
    {
        // Make doesn't save it in DB
        $list = TodoList::factory()->make(['user_id' => $this->user->id]);
        
        $res = $this->postJson(route('todo-lists.store'), [
            'user_id' => $list->user_id, 
            'name' => $list->name
        ])->assertCreated()->json('data');

        $this->assertEquals($list->name, $res['name']);
            
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
    }

    public function test_while_storing_todo_list_fields_is_required(): void
    {
        $this->withExceptionHandling();

        $this->postJson(route('todo-lists.store'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_a_todo_list(): void
    {
        $this->deleteJson(route('todo-lists.destroy', $this->list->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists', ['name' => $this->list->name]);
    }
    
    public function test_update_a_todo_list(): void
    {
        $this->patchJson(route('todo-lists.update', $this->list->id), ['name' => 'new name'])
            ->assertOk();

        $this->assertDatabaseHas('todo_lists', ['id' => $this->list->id, 'name' => 'new name']);
    }

    public function test_while_updating_todo_list_fields_is_required(): void
    {
        $this->withExceptionHandling();

        $this->patchJson(route('todo-lists.update', $this->list->id))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
