<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LibroTest extends TestCase
{
    use RefreshDatabase; // Esto reinicia la BD después de cada prueba

    // Helper para crear roles y asignarlos fácilmente
    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder de roles antes de cada prueba
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_un_bibliotecario_puede_crear_un_libro()
    {
        // 1. Crear un usuario con rol 'bibliotecario'
        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        // 2. Datos del nuevo libro
        $libroData = [
            'title' => 'El Principito',
            'author' => 'Antoine de Saint-Exupéry',
            'ISBN' => '1234567890',
        ];

        // 3. Hacer la petición POST como ese usuario
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', $libroData);;

        // 4. Afirmar (Assert) que la respuesta fue exitosa (201 Created)
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', ['title' => 'El Principito']);
    }

    public function test_un_estudiante_NO_puede_crear_un_libro()
    {
        // 1. Crear un usuario con rol 'estudiante'
        $user = User::factory()->create();
        $user->assignRole('estudiante');

        $libroData = [
            'title' => 'Cien Años de Soledad',
            'author' => 'Gabriel García Márquez',
            'ISBN' => '0987654321',
        ];

        // 2. Hacer la petición POST
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/books', $libroData);;

        // 3. Afirmar que el acceso NO está autorizado (403 Forbidden)
        $response->assertStatus(403);
        $this->assertDatabaseMissing('books', ['title' => 'Cien Años de Soledad']);
    }

    /** @test */
    public function test_un_bibliotecario_puede_actualizar_un_libro()
    {
        $user = User::factory()->create();
        $user->assignRole('bibliotecario');

        $libro = Book::factory()->create(['title' => 'Título Viejo']);

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/v1/books/{$libro->id}", [
            'title' => 'Título Nuevo'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('books', ['id' => $libro->id, 'title' => 'Título Nuevo']);
    }

    // ... Aquí deberías crear TODAS las pruebas para:
    // - Autenticación (login, logout, perfil)
    // - CRUD de libros (listar, ver detalle, crear, actualizar, eliminar) probando con diferentes roles.
    // - Préstamos (prestar, devolver, historial) probando con diferentes roles.
}