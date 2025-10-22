<?php

namespace App\Models;

use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'task_type' => TaskType::class
        ];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);

    }

    public function application()
    {
        return $this->hasMany(Application::class);

    }
}
