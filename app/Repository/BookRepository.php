<?php

namespace App\Repository;

use App\Iface\BookRepositoryInterface;
use App\Models\Book;

class BookRepository implements BookRepositoryInterface
{
    public function findAllBooks()
    {    
        return Book::with('author')
            ->whereHas('author', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();
    }

    public function searchBooks($keyword)
    {
        $books = Book::whereHas('author', function ($query) use ($keyword) {
            $query->where('is_active', 1)
                    ->where('name', 'like', '%' . $keyword . '%');
        })
        ->orWhere('title', 'like', '%' . $keyword . '%')
        ->with(['author:id,name'])
        ->get(['title', 'cover_image_url', 'author_id']);

        return $books;
    }
}