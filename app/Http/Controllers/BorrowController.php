<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    //All books the user borrow
    public function userIndex(User $user): JsonResponse
    {
        $borrows = Borrow::where('user_id', $user->id)->get();
        return response()->json($borrows, 200);
    }
    public function adminIndex(): JsonResponse
    {
        $borrows = Borrow::all();
        return response()->json($borrows, 200);
    }
    public function borrowBook(Request $request): JsonResponse
    {
        if (auth()->user()->role !== 'user') {
            return response()->json(['error' => 'Only user can borrow a book'],403);
        };
        $validatedData = $request->validate([
            'book_id' => 'required|exists:books,id',
            'return_date' => 'required|date|after:today'
        ]);
        $validatedData['user_id'] = auth()->id();

        $book = Book::findOrFail($validatedData['book_id']);
        if ($book->status !== 'available') {
            return response()->json(['error' => 'Book is not available for borrowing'], 400);
        }

        DB::beginTransaction();

        try {
            $book->update(['status' => 'borrowed']);
            $borrow = Borrow::create($validatedData);

            DB::commit();
            return response()->json(['borrow' => $borrow, 'book' => $book->fresh()], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to borrow the book. Please try again.'], 500);
        }
    }
    public function return(Borrow $borrow):JsonResponse{
        if(auth()->user()->role !== 'user'){
            return response()->json(['error' => 'Only user can return a book']);
        }
        if($borrow->return_at){
            return response()->json(['error' => 'Book has been returned',400 ]);
        }
        $book = Book::findOrFail($borrow->book_id);
        $book->update(['status' => 'available']);
        return response()->json($borrow,200);
    }
}
