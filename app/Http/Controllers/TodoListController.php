<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return TodoListResource
     */
    public function index()
    {
        return TodoListResource::collection(auth()->user()->todoLists);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param TodoListRequest $request
     * @return TodoListResource
     */
    public function store(TodoListRequest $request)
    {
        return new TodoListResource(auth()->user()->todoLists()->create($request->validated()));
    }

    /**
     * Display the specified resource.
     * 
     * @param TodoList $todo_list
     * @return TodoListResource
     */
    public function show(TodoList $todo_list)
    {
        return new TodoListResource($todo_list);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param TodoListRequest $request
     * @param TodoList $todo_list
     * @return TodoListResource
     */
    public function update(TodoListRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->validated());

        return new TodoListResource($todo_list);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param TodoList $todo_list
     * @return TodoListResource
     */
    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();

        return (new TodoListResource($todo_list))->response()->setStatusCode(204);
    }
}
