<?php

namespace Database\Factories;

use App\Models\{Label, Task, TodoList};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'todo_list_id' => TodoList::factory(),
            'label_id'     => Label::factory(),
            'title'        => fake()->sentence(),
            'description'  => fake()->paragraph(),
            'status'       => fake()->randomElement([
                Task::NOT_STARTED, 
                Task::STARTED, 
                Task::COMPLETED
            ]),
        ];
    }
}
