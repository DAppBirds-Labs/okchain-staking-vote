<?php

namespace App\Services;

abstract class Service
{
    public static function instance()
    {
        return new static();
    }
}