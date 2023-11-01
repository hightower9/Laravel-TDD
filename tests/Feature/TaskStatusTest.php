<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->authUser();
    }

    public function test_task_status_can_be_changed(): void
    {
        $list = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);

        $res = $this->putJson(route('tasks.update', $task->id), [
            'title' => $task->title, 
            'status' => Task::STARTED
        ])->json('data');

        $this->assertDatabaseHas('tasks', ['status' => $res['status']]);
    }
}
