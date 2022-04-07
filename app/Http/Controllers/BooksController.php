<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->model = new Book();
    }

    public function index(Request $request)
    {
        // @TODO implement
        // Index
        $data = Book::with('authors')
        ->filter($request->only('page', 'sortColumn','sortDirection','title','authors'))
        ->paginate(15);
        // return response()->json($data);
        return BookResource::collection($data);
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        //Insert into table books
        $data = $request->validated();
        $book = new Book();

        $book->isbn = $data['isbn'];
        $book->title = $data['title'];
        $book->description = $data['description'];
        $book->published_year = $data['published_year'];
        $book->save();
        $authors = [];
        foreach ($data['authors'] as $key => $value) {
            $authors[] = \App\Author::find($value);
        }
        $book->authors()->saveMany($authors);
        return new BookResource($book);
    }
}
