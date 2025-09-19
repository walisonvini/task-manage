<?php

namespace Tests\Feature;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use Tests\TestCase;

use App\Models\Log;

class LogControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_get_all_logs()
    {
        Log::factory(50)->create();

        $response = $this->get('/api/logs');
        
        $response->assertResponseStatus(200);
        $response->seeJson([
            'success' => true,
            'message' => 'Logs fetched successfully'
        ]);
        $response->seeJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'action', 'model', 'model_id', 'created_at']
            ]
        ]);

        $this->assertCount(30, $response->response->getData()->data);
    }

    public function test_can_get_log_by_id()
    {
        $log = Log::factory()->create();

        $response = $this->get('/api/logs?id=' . $log->id);

        $response->assertResponseStatus(200);
        $response->seeJson([
            'success' => true,
            'message' => 'Logs fetched successfully'
        ]);

        $response->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'id', 'action', 'model', 'model_id', 'created_at'
            ]
        ]);

        $response->seeJson(['id' => $log->id]);
    }

    public function test_cannot_get_log_by_invalid_id()
    {
        $response = $this->get('/api/logs?id=999');

        $response->assertResponseStatus(404);
        $response->seeJson(['message' => 'Resource not found']);
    }
}
