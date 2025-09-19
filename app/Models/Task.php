<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $this->validateStatusFilter($filters['status']);
            $query->whereIn('status', explode(',', $filters['status']));
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query;
    }

    private function validateStatusFilter(string $status): void
    {
        $statusArray = explode(',', $status);
        $validStatus = array_column(TaskStatus::cases(), 'value');
        
        $invalid = array_diff($statusArray, $validStatus);
        if ($invalid) {
            $validator = Validator::make([], []);
            $validator->errors()->add('status', 'Invalid status values: ' . implode(', ', $invalid));
            throw new ValidationException($validator);
        }
    }
}
