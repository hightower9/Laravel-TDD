<?php

namespace Tests\Unit\Auth;

use App\Models\TodoList;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_lists(): void
    {
        $user = $this->createUser();
        $this->createTodoList(['user_id' => $user->id]);

        $this->assertInstanceOf(Collection::class, $user->todoLists);
        $this->assertInstanceOf(TodoList::class, $user->todoLists->first());
    }
}
