<?php

namespace App\Inspections;

/**
 * Deals with spam
 */
class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * @param string $input
     * 
     * @return bool
     */
    public function detect($input)
    {
        foreach ($this->inspections as $i) {
            app($i)->detect($input);
        }

        return false;
    }
}
