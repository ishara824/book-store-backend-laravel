<?php

namespace App\Iface;

interface BookRepositoryInterface
{
    public function findAllBooks();

    public function searchBooks($keyword);

}