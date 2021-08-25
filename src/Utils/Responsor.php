<?php

declare(strict_types=1);

namespace Refactoring\Utils;

class Responsor
{
    public static function createResponse($string, $int) {
        $data = [
            $string,
            $int
        ];

        if($int != 200) {
            return $string;
        }

        return $data[0];
    }
}