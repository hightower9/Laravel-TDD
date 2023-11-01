<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_list_has_many_tasks(): void
    {
        $list = $this->createTodoList();
        $this->createTask(['todo_list_id' => $list->id]);

        $this->assertInstanceOf(Collection::class, $list->tasks);
        $this->assertInstanceOf(Task::class, $list->tasks->first());
    }

    public function test_if_todo_list_is_deleted_all_tasks_are_deleted(): void
    {
        $list1 = $this->createTodoList();
        $task1 = $this->createTask(['todo_list_id' => $list1->id]);

        $list2 = $this->createTodoList();
        $this->createTask(['todo_list_id' => $list2->id]);

        $list1->delete();

        $this->assertDatabaseMissing('todo_lists', ['id' => $list1->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task1->id]);

        $this->assertDatabaseHas('todo_lists', ['id' => $list2->id]);
    }
}
