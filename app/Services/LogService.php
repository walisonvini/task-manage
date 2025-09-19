<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Log;

class LogService
{
    public function get(?string $id = null): Collection | Log
    {
        if($id) {
            return Log::findOrFail($id); 
        }

        return Log::orderBy('created_at', 'desc')->take(30)->get();
    }

    public function create(array $data): Log
    {
        return Log::create($data);
    }
}
