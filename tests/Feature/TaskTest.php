<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->authUser();
    }

    public function test_fetch_all_tasks_of_a_todo_item(): void
    {
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);

        $response = $this->getJson(route('todo-lists.tasks.index', $list->id))->assertOk()->json('data');

        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($response[0]['todo_list_id'], $list->id);
    }

    public function test_store_a_task_for_todo_list(): void
    {
        $list = $this->createTodoList();
        // Make doesn't save it in DB
        $task = Task::factory()->make();
        $label = $this->createLabel();

        $res = $this->postJson(route('todo-lists.tasks.store', $list->id), [
            'label_id' => $label->id,
            'title'    => $task->title,
        ])->assertCreated()->json('data');

        $this->assertEquals($task->title, $res['title']);

        $this->assertDatabaseHas('tasks', [
            'todo_list_id' => $list->id,
            'label_id' => $label->id,
            'title' => $task->title,
        ]);
    }

    public function test_store_a_task_for_todo_list_without_label(): void
    {
        $list = $this->createTodoList();
        // Make doesn't save it in DB
        $task = Task::factory()->make();

        $res = $this->postJson(route('todo-lists.tasks.store', $list->id), [
            'title' => $task->title,
        ])->assertCreated()->json('data');

        $this->assertEquals($task->title, $res['title']);

        $this->assertDatabaseHas('tasks', [
            'todo_list_id' => $list->id,
            'label_id' => NULL,
            'title' => $task->title,
        ]);
    }

    public function test_update_a_task_from_a_todo_list(): void
    {
        $task = $this->createTask();

        $this->patchJson(route('tasks.update', $task->id), ['title' => 'updated title'])
            ->assertOk()->json('data');

        $this->assertDatabaseHas('tasks', ['title' => 'updated title']);
    }

    public function test_delete_a_task_from_a_todo_list(): void
    {
        $task = $this->createTask();

        $this->deleteJson(route('tasks.destroy', $task->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }
}
