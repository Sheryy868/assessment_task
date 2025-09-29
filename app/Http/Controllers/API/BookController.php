<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // GET /api/books
    public function index()
    {
        $books = Book::latest()->paginate(10);
        // resource collection with pagination metadata
        return BookResource::collection($books)->additional([
            'meta' => [
                'current_page' => $books->currentPage(),
                'last_page' => $books->lastPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
            ],
            'message' => 'Books retrieved'
        ]);
    }

    // POST /api/books
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        return (new BookResource($book))->response()->setStatusCode(201);
    }

    // GET /api/books/{book}
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    // PUT/PATCH /api/books/{book}
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        return new BookResource($book);
    }

    // DELETE /api/books/{book}
  public function destroy($id)
{
    $book = Book::find($id);

    if (! $book) {
        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }

    $book->delete();

    return response()->json([
        'message' => 'Book record deleted successfully'
    ], 200);
}

}
