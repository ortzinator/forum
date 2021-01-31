<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $keywords = [
        'yahoo customer support',
    ];

    public function detect($input)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($input, $keyword) !== false) {
                throw new Exception(__('Your reply contains spam.'));
            }
        }
    }
}
