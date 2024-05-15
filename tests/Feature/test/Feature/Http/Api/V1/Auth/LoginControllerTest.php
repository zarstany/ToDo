<?php

namespace Tests\Feature\test\Feature\Http\Api\V1\Auth;

use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    public function testLoginControllerWhenResponseSuccess()
    {
        //Dado
        $userData = [
            'email' => 'johndoe@example.com',
            'password' => 'Todo!2024.',
        ];
        //Api
        $response = $this->postJson('/api/login', $userData);
        //Esperamos que responda
        $content = json_decode($response->getContent(), true);
        $token = $content['data']['token'];

        $response->assertStatus(200) // Esperamos que la solicitud sea exitosa y retorne un 200 creado
            ->assertJson([
                'data' => [
                    'token' => $token,
                    'name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                ],
            ]);

        // Además, verificamos que el usuario se haya creado en la base de datos
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
        // vendor/bin/phpunit --filter testLoginControllerWhenResponseSuccess

    }

    public function testLoginControllerWhenEmailNotFoundResponseFail()
    {
        //Dado
        $userData = [
            'email' => 'johndoe22@example.com',
            'password' => 'Todo!2024.',
        ];
        //Api
        $response = $this->postJson('/api/login', $userData);
        //Esperamos que responda

        $response->assertStatus(280) // Esperamos que haya un error y retorne un estado 400 de error
            ->assertJsonFragment([
                'status' => 280,
                'title' => 'Account Not Found',
                'detail' => "No account found with that email. Haven't signed up yet? Register on our site!",
                'code' => 'ERROR_CODE_ACCOUNT_NOT_FOUND',

            ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@gmail.com',
        ]);
        // vendor/bin/phpunit --filter testLoginControllerWhenEmailNotFoundResponseFail

    }

    public function testLoginControllerWhenEmailIsNullResponseFail()
    {
        //Dado
        $userData = [
            'email' => '',
            'password' => '!Doctime.com21',
        ];
        //Api
        $response = $this->postJson('/api/login', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
            ->assertJsonFragment([
                'status' => 400,
                'title' => 'Error in Field',
                'detail' => 'The email field is required.',
                'code' => 'FORM_ERROR',
                'source' => [
                    'parameter' => 'email',
                ],
            ]);
        // Además, verificamos que el usuario no se encuentre guardado ya que el email es nulo
        $this->assertDatabaseMissing('users', [
            'email' => '',
        ]);
        // vendor/bin/phpunit --filter testLoginControllerWhenEmailIsNullResponseFail

    }

    public function testLoginControllerWhenPasswordIsNullResponseFail()
    {
        //Dado
        $userData = [
            'email' => 'johndoe@example.com',
            'password' => '',
        ];
        //Api
        $response = $this->postJson('/api/login', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
            ->assertJsonFragment([
                'status' => 400,
                'title' => 'Error in Field',
                'detail' => 'The password field is required.',
                'code' => 'FORM_ERROR',
                'source' => [
                    'parameter' => 'password',
                ],
            ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@gmail.com',
        ]);
        // vendor/bin/phpunit --filter testLoginControllerWhenPasswordIsNullResponseFail

    }

    public function testLoginControllerWhenPasswordIsWrongResponseFail()
    {
        //Dado
        $userData = [
            'email' => 'johndoe@example.com',
            'password' => '',
        ];
        //Api
        $response = $this->postJson('/api/login', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
            ->assertJsonFragment([
                'status' => 400,
                'title' => 'Error in Field',
                'detail' => 'The password field is required.',
                'code' => 'FORM_ERROR',
                'source' => [
                    'parameter' => 'password',
                ],
            ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@gmail.com',
        ]);
        // vendor/bin/phpunit --filter testLoginControllerWhenPasswordIsWrongResponseFail

    }
}
