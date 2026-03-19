<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LibroPolicy
{
    public function update(User $user, Book $book): bool
{
    // El usuario puede actualizar SOLO si tiene el rol 'bibliotecario'
    return $user->hasRole('bibliotecario');
}

// Podemos añadir métodos similares para crear, eliminar, etc.
public function create(User $user): bool
{
    // Solo bibliotecarios pueden crear
    return $user->hasRole('bibliotecario');
}

public function delete(User $user, Book $book): bool
{
    // Solo bibliotecarios pueden eliminar
    return $user->hasRole('bibliotecario');
}
}
