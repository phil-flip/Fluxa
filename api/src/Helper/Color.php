<?php

namespace App\Helper;

class Color
{
    public static function createRandomColor(): string
    {
        $min = 0xCCCCCC;
        $max = 0xFFFFFF;

        return sprintf("#%06x", rand($min, $max));
    }
}
