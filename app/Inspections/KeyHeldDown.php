<?php

namespace App\Inspections;

use Exception;

class KeyHeldDown
{
    public function detect($input)
    {
        if(preg_match('/(.)\\1{4,}/', $input)) {
            throw new Exception('Your reply contains spam');
        }
    }
}
