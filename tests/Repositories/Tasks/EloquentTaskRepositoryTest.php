<?php

namespace Tests\Repositories\Tasks;

use App\DTOs\TaskDTO;
use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EloquentTaskRepositoryTest extends TestCase
{

    private $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = $this->app->make(TaskRepositoryInterface::class);
    }

    public function testMethodCreateWhenResponseSuccess(): void
    {
        //Dado
        $tempTitle = 'Title task';
        $tempDescription = 'Description Task';
        $tempStatus = 'Pending';
        $tempUserId = 1;

        //When
        $temporalTaskDTO = new TaskDTO();
        $temporalTaskDTO->setTitle($tempTitle);
        $temporalTaskDTO->setDescription($tempDescription);
        $temporalTaskDTO->setStatus($tempStatus);
        $this->taskRepository->store($temporalTaskDTO, $tempUserId);

        $this->assertDatabaseHas('tasks',
            [
                'title' => $tempTitle,
                'description' => $tempDescription,
                'status' => $tempStatus,
                'user_id' => $tempUserId
            ]);
        // vendor/bin/phpunit --filter testMethodCreateWhenResponseSuccess
    }

    public function testMethodFindByIdWhenResponseSuccess()
    {

        // Dado: Debería existir la tarea la cual podamos encontrar por su id

        // When: Buscamos al usuario por su email
        $temporalId = 1;
        $foundTask = $this->taskRepository->findById($temporalId);
        // Then: Deberíamos obtener una task con el id buscado
        $this->assertNotNull($foundTask);
        $this->assertEquals($temporalId, $foundTask->id);

        // vendor/bin/phpunit --filter testMethodFindByIdWhenResponseSuccess
    }
}
