<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'ISBN' => 'required',
        ]);

        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $book->update($request->all());

        return response()->json($book, 200);
    }
}