<?php

namespace Tests;

use App\Models\{Label, Task, TodoList, User};
use App\Models\Service\ExternalService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function authUser(): User
    {
        return Sanctum::actingAs($this->createUser());
    }

    public function createUser(array $args = []): User
    {
        return User::factory()->create($args);
    }

    public function createTodoList(array $args = []): TodoList
    {// $args = ['name' => 'test'];
        return TodoList::factory()->create($args);
    }

    public function createTask(array $args = []): Task
    {
        return Task::factory()->create($args);
    }

    public function createLabel(array $args = []): Label
    {
        return Label::factory()->create($args);
    }

    public function createExternalService(array $args = []): ExternalService
    {
        return ExternalService::factory()->create($args);
    }
}
