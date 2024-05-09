<?php

namespace Tests\Repositories\Auth;

use App\Libraries\Auth\AuthUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EloquentUserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
    }

    use DatabaseMigrations;

    public function testMethodRegisterWhenResponseSuccess(): void
    {
        //Dado
        $tempName = 'jhon';
        $tempEmail = 'jhon@gmail.com';
        $tempPassword = 'Magapaca21.';

        //When
        $temporalAuthUserDTO = new AuthUserDTO();
        $temporalAuthUserDTO->setName($tempName);
        $temporalAuthUserDTO->setEmail($tempEmail);
        $temporalAuthUserDTO->setPassword($tempPassword);
        $this->userRepository->create($temporalAuthUserDTO);

        $this->assertDatabaseHas('users',
            [
                'name' => $tempName,
                'email' => $tempEmail,
            ]);
        // vendor/bin/phpunit --filter testMethodRegisterWhenResponseSuccess
    }

    public function testMethodFindByEmailWhenResponseSuccess()
    {

        // Dado: Debería existir un usuario el cual podamos encontrar por su email
        $email = 'jasper@gmail.com';
        User::create([
            'name' => 'Jasper',
            'email' => $email,
            'password' => bcrypt('Magapaca21.'),
        ]);

        // When: Buscamos al usuario por su email

        $foundUser = $this->userRepository->FindByEmail($email);
        // Then: Deberíamos obtener un usuario con el email buscado
        $this->assertNotNull($foundUser);
        $this->assertEquals($email, $foundUser->email);

        // vendor/bin/phpunit --filter testMethodFindByEmailWhenResponseSuccess
    }
}
