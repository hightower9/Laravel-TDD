<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\TodoList;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return TaskResource
     */
    public function index(TodoList $todo_list)
    {
        return TaskResource::collection($todo_list->tasks);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param TaskRequest $request
     * @return TaskResource
     */
    public function store(TaskRequest $request, TodoList $todo_list)
    {
        return new TaskResource($todo_list->tasks()->create($request->validated()));
    }

    /**
     * Display the specified resource.
     * 
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param TaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Task $task
     * @return TaskResource
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return (new TaskResource($task))->response()->setStatusCode(204);
    }
}
