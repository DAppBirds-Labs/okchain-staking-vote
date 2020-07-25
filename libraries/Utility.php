<?php
use Illuminate\Support\Str;

class Utility
{
    public static function name2uri($name)
    {
        // format
        $name = preg_replace('/[#$^&*+=\/?\.]/', '-', strtolower($name));

        return $name;
    }
}
