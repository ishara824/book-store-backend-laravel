<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    public function getBooksByAuthor()
    {
        $books = Book::where('author_id', Auth::user('api')->id)->get();
        return response()->json(['data' => $books], 200);
    }
}
