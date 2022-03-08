<?php

namespace App;

class Product
{
    public function __construct(public string $title, public int $costInCents)
    {
    }
}