<?php

namespace App\Http\Controllers;

use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BooksReviewController extends Controller
{
    public function __construct()
    {

    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        // Insert into table book_review
        $book = \App\Book::find($bookId);
        if ($book == null) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }
        $data = $request->validated();
        $bookReview = new BookReview();

        $bookReview->book_id = $bookId;
        $bookReview->user_id = Auth::user()->id;
        $bookReview->review = $data['review'];
        $bookReview->comment = $data['comment'];
        $bookReview->save();
        return new BookReviewResource($bookReview);
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        //Delete data from Table book_review
        $book = \App\Book::find($bookId);
        if ($book == null) {
            return response()->json([
                'message' => 'not found',
            ], 404);
        }
        $review = BookReview::find($reviewId);
        $review->delete();
        return response()->json([], 204);
    }
}
