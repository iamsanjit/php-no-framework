<?php declare (strict_types = 1);

namespace App\Framework\Csrf;

interface TokenStorage
{
    public function store(string $key, Token $token);
    public function retrieve(string $key) : ?Token; 
}