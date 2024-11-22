<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $books = Book::with(['category'=> function($query){
            $query->select('id','name');
        }])
        ->select('id','title','author','publisher','year','category_id','status')
        ->paginate(10);
        return response()->json($books, 200);     
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):JsonResponse
    {
        if(auth()->user()->role == 'user'){
          return response()->json(['error' => 'You are not allowed to create book']);  
        };
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
        ]);
        $book = Book::with('category')->create($validatedData);
        return response()->json($book,201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        if(auth()->user()->role == 'user'){
            return response()->json(['error'=>'You are not authorized to update books'],403);
        };
        
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'publisher' => 'sometimes|required|string|max:255',
            'year' => 'sometimes|required|integer|min:1000|max:' . (date('Y') + 1),
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $book->update($validatedData);
        $book->load('category');
        return response()->json($book,200);

    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book) :JsonResponse
    {
        $book->delete();
        return response()->json(null,204);
    }
}
