<?php

namespace TheNodi\PrinterWrapper\Tests;

use TheNodi\PrinterWrapper\Printer;

class PrintTest extends TestCase
{
    /** @test */
    function it_can_print_a_file()
    {
        $this->cli->shouldReceive('run')
            ->with('lp',
                [
                    '-d',
                    'PrinterA',
                    '/tmp/randomfile.txt'
                ],
                \Mockery::type('callable')
            )
            ->once()
            ->andReturn('request id is PrinterA-1 (1 file(s))');

        (new Printer('PrinterA', $this->cli))
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_an_option()
    {
        $this->cli->shouldReceive('run')
            ->with('lp',
                [
                    '-d',
                    'PrinterA',
                    '-o',
                    'something',
                    '-o',
                    'some=else',
                    '/tmp/randomfile.txt'
                ],
                \Mockery::type('callable')
            )
            ->once()
            ->andReturn('request id is PrinterA-1 (1 file(s))');

        (new Printer('PrinterA', $this->cli))
            ->setOption('something')
            ->setOption('some', 'else')
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_reset_options()
    {
        $this->cli->shouldReceive('run')
            ->with('lp',
                [
                    '-d',
                    'PrinterA',
                    '/tmp/randomfile.txt'
                ],
                \Mockery::type('callable')
            )
            ->once()
            ->andReturn('request id is PrinterA-1 (1 file(s))');

        (new Printer('PrinterA', $this->cli))
            ->setOption('something')
            ->setOption('some', 'else')
            ->resetOptions()
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }
}
