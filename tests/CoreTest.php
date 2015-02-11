<?php

class CoreTest extends PHPUnit_Framework_TestCase {
    
    /** @test */
    public function a_simple_test()
    {
        $i = 'D';
        $this->assertEquals('D', $i);
    }

    /** @test */
    public function another_simple_test()
    {
        $i = 'R';
        $this->assertEquals('D', $i);
    }
}