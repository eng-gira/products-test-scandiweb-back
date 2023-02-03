<?php

namespace Inc;

class Utils
{
    public static function onlyFirstCharacterIsCapital(string $str): string
    {
        return strtoupper(substr($str, 0, 1)) . (strlen($str) > 1 ? strtolower(substr($str, 1)) : '');
    }
}
