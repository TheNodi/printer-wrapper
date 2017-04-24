<?php

namespace TheNodi\PrinterWrapper\Tests;

use TheNodi\PrinterWrapper\Printer;

class PrintTest extends TestCase
{
    /** @test */
    function it_can_print_a_file()
    {
        $this->cli->shouldReceive('run')
            ->withArgs(['lp -d PrinterA /tmp/randomfile.txt'])
            ->once()
            ->andReturn('request id is PrinterA-1 (1 file(s))');

        $printer = new Printer('PrinterA', $this->cli);

        $printer->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }
}
