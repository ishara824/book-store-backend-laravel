<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Iface\BookRepositoryInterface;
use App\Models\Book;
use App\Repository\BookRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    private $bookRepositoryInterface;

    public function __construct(BookRepositoryInterface $bookRepositoryInterface)
    {
        $this->bookRepositoryInterface = $bookRepositoryInterface;
    }
    public function index()
    {
        $books = $this->bookRepositoryInterface->findAllBooks();
        return response()->json(['data' => $books], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        try {

            $request->merge(['author_id' => Auth::user('api')->id]);
            $book = Book::create($request->only(['title', 'description', 'author_id']));

            if($request->hasFile('coverImage')){
                $diskName = 'public';
			    $disk = Storage::disk($diskName);
                $filename = pathinfo($request->coverImage->getClientOriginalName(), PATHINFO_FILENAME);
    
                $filename = $filename.'_'.time().'.'.request()->coverImage->getClientOriginalExtension();
                $path = $request->coverImage->storeAs('image', $filename, $diskName);
    
                $book['cover_image_url'] = $disk->url($path);
                $book->save();
            }
            return response()->json("Book added successfully!", 200);

        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function searchBooks(Request $request)
    {
        try {

            $books = $this->bookRepositoryInterface->searchBooks($request->keyword);
            return response()->json(['data' => $books], 200);
            
        } catch (Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
