<?php

namespace test\Feature\Http\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterControllerWhenResponseSuccess()
    {
        //Dado
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'Magapaca21.'
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(200) // Esperamos que la solicitud sea exitosa y retorne un 201 creado
        ->assertJson([
            'data' => [
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com'
            ]
        ]);

        // Además, verificamos que el usuario se haya creado en la base de datos
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@gmail.com'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenResponseSuccess

    }


    public function testRegisterControllerWhenAllParametersIsNullResponseFail()
    {
        //Dado
        $userData = [
            'name' => '',
            'email' => '',
            'password' => ''
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The email field is required.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "email"
            ]
        ])
            ->assertJsonFragment([
                "status" => 400,
                "title" => "Error in Field",
                "detail" => "The password field is required.",
                "code" => "FORM_ERROR",
                "source" => [
                    "parameter" => "password"
                ]
            ])
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The name field is required.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "name"
            ]
        ]);

        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenAllParametersIsNullResponseFail
    }

    public function testRegisterControllerWhenEmailIsInvalidResponseFail()
    {
        //Dado
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe',
            'password' => '!Doctime.com21'
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The email field must be a valid email address.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "email"
            ]
        ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenEmailIsInvalidResponseFail

    }
    public function testRegisterControllerWhenNameIsNullResponseFail()
    {
        //Dado
        $userData = [
            'name' => '',
            'email' => 'johndoe@gmail.com',
            'password' => '!Doctime.com21'
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The name field is required.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "name"
            ]
        ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenEmailIsNullResponseFail

    }

    public function testRegisterControllerWhenEmailIsNullResponseFail()
    {
        //Dado
        $userData = [
            'name' => 'John Doe',
            'email' => '',
            'password' => '!Doctime.com21'
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The email field is required.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "email"
            ]
        ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenEmailIsNullResponseFail

    }

    public function testRegisterControllerWhenPasswordIsInvalidResponseFail()
    {
        //Dado
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'sasa'
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
        ->assertJsonFragment([
            "status" => 400,
            "title" => "Error in Field",
            "detail" => "The password field must be at least 8 characters.",
            "code" => "FORM_ERROR",
            "source" => [
                "parameter" => "password"
            ]
        ])
            ->assertJsonFragment([
                "status" => 400,
                "title" => "Error in Field",
                "detail" => "The password field format is invalid.",
                "code" => "FORM_ERROR",
                "source" => [
                    "parameter" => "password"
                ]
            ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@gmail.com'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenEmailIsInvalidResponseFail

    }
    public function testRegisterControllerWhenPasswordIsNullResponseFail()
    {
        //Dado
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => ''
        ];
        //Api
        $response = $this->postJson('/api/register', $userData);
        //Esperamos que responda
        $response->assertStatus(400) // Esperamos que haya un error y retorne un estado 400 de error
            ->assertJsonFragment([
                "status" => 400,
                "title" => "Error in Field",
                "detail" => "The password field is required.",
                "code" => "FORM_ERROR",
                "source" => [
                    "parameter" => "password"
                ]
            ]);
        // Además, verificamos que el usuario no se haya guardado
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@gmail.com'
        ]);
        // vendor/bin/phpunit --filter testRegisterControllerWhenPasswordIsNullResponseFail

    }
}
