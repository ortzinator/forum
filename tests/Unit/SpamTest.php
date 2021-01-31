<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    public function test_it_detects_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');
        
        $spam->detect('yahoo customer support');
    }
    
    public function test_it_detects_a_key_being_held_down()
    {
        $spam = new Spam();
        
        $this->expectException('Exception');

        $spam->detect('aaaaaaaaaaaaa');
    }
}
