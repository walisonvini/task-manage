<?php

namespace Tests\Feature;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use Tests\TestCase;

use App\Models\Task;
use App\Models\Log;

class TaskControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_can_get_all_tasks()
    {
        Task::factory(10)->create(['status' => 'pending']);
        Task::factory(10)->create(['status' => 'in_progress']);
        Task::factory(10)->create(['status' => 'done']);

        $response = $this->get('/api/tasks');

        $response->assertResponseStatus(200);
        $response->seeJson([
            'success' => true,
            'message' => 'Tasks fetched successfully'
        ]);
        $response->seeJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'title', 'description', 'status']
            ]
        ]);

        $this->assertCount(30, $response->response->getData()->data);
    }

    public function test_can_get_tasks_by_status()
    {
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'done']);

        $response = $this->get('/api/tasks?status=pending');

        $response->assertResponseStatus(200);
        $response->seeJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => ['id', 'title', 'description', 'status']
            ]
        ]);

        $response->seeJson(['status' => 'pending']);
        $response->dontSeeJson(['status' => 'done']);
    }

    public function test_cannot_get_tasks_by_invalid_status()
    {
        $response = $this->get('/api/tasks?status=invalid');

        $response->assertResponseStatus(422);
        $response->seeJson(['message' => 'Validation failed']);
        $response->seeJsonStructure([
            'errors' => ['status']
        ]);
    }

    public function test_can_get_task_by_id()
    {
        $task = Task::factory()->create();

        $response = $this->get('/api/tasks/' . $task->id);

        $response->assertResponseStatus(200);
        $response->seeJson(['id' => $task->id]);
    }

    public function test_cannot_get_task_by_invalid_id()
    {
        $response = $this->get('/api/tasks/999');

        $response->assertResponseStatus(404);
        $response->seeJson(['message' => 'Resource not found']);
    }

    public function test_can_create_task()
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'status' => 'pending',
        ]);

        $response->assertResponseStatus(201);
        $response->seeJsonContains([
            'success' => true,
            'message' => 'Task created successfully'
        ]);
        $response->seeJsonContains(['title' => 'Test Task']);
        $response->seeJsonContains(['status' => 'pending']);
    }

    public function test_cannot_create_task_without_title_and_status()
    {
        $response = $this->post('/api/tasks', [
            'description' => 'Test Description',
        ]);

        $response->assertResponseStatus(422);
        $response->seeJsonContains([
            'success' => false,
            'message' => 'Validation failed'
        ]);
        $response->seeJsonStructure([
            'errors' => ['title', 'status']
        ]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->put('/api/tasks/' . $task->id, [
            'status' => 'done',
        ]);

        $response->assertResponseStatus(200);
        $response->seeJsonContains(['message' => 'Task updated successfully']);

        $updatedTask = Task::find($task->id);
        $this->assertEquals('done', $updatedTask->status);
    }

    public function test_cannot_update_task_by_invalid_id()
    {
        $response = $this->put('/api/tasks/999', [
            'status' => 'done',
        ]);

        $response->assertResponseStatus(404);
        $response->seeJsonContains(['message' => 'Resource not found']);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->delete('/api/tasks/' . $task->id);

        $response->assertResponseStatus(200);
        $response->seeJsonContains(['message' => 'Task deleted successfully']);

        $this->assertFalse(Task::where('id', $task->id)->exists());
    }

    public function test_cannot_delete_task_by_invalid_id()
    {
        $response = $this->delete('/api/tasks/999');

        $response->assertResponseStatus(404);
        $response->seeJsonContains(['message' => 'Resource not found']);
    }

    public function test_can_create_log_when_task_is_created()
    {
        $response = $this->post('/api/tasks', [
            'title' => 'Test Task',
            'status' => 'pending',
        ]);

        $response->assertResponseStatus(201);

        $log = Log::orderBy('created_at', 'desc')->first();

        $this->assertStringContainsString('Create task Test Task', $log->action);
        $this->assertNotNull($log->id);
    }

    public function test_can_create_log_when_task_is_updated()
    {
        $task = Task::factory()->create();

        $response = $this->put('/api/tasks/' . $task->id, [
            'status' => 'done',
        ]);

        $response->assertResponseStatus(200);

        $log = Log::orderBy('created_at', 'desc')->first();

        $this->assertStringContainsString('Update task ' . $task->title, $log->action);
        $this->assertNotNull($log->id);
    }

    public function test_can_create_log_when_task_is_deleted()
    {
        $task = Task::factory()->create();

        $response = $this->delete('/api/tasks/' . $task->id);

        $response->assertResponseStatus(200);

        $log = Log::orderBy('created_at', 'desc')->first();

        $this->assertStringContainsString('Delete task ' . $task->title, $log->action);
        $this->assertNotNull($log->id);
    }
}
