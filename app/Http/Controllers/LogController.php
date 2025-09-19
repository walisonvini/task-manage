<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\LogService;

use App\Traits\ApiResponse;

class LogController extends Controller
{
    use ApiResponse;

    public function __construct(
        private LogService $logService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $logs = $this->logService->get($request->id);
        return $this->successResponse($logs, 'Logs fetched successfully');
    }
}
