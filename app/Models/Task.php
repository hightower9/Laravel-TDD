<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    public CONST NOT_STARTED = 'Not Started';
    public CONST STARTED     = 'Started';
    public CONST COMPLETED   = 'Completed';

    protected $fillable = [
        'todo_list_id',
        'label_id',
        'title',
        'description',
        'status',
    ];

    public function todoList(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class);
    }
}
