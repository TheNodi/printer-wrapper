<?php

namespace TheNodi\PrinterWrapper\Tests;

use TheNodi\PrinterWrapper\Printer;

class PrintTest extends TestCase
{
    /**
     * Mock the run method with given options
     *
     * @param array $options
     */
    protected function mockRunMethod($options = [])
    {
        $args = array_merge([
            '-d',
            'PrinterA',
        ], $options, ['/tmp/randomfile.txt']);

        $this->cli->shouldReceive('run')
            ->with('lp', $args, \Mockery::type('callable'))
            ->once()
            ->andReturn('request id is PrinterA-1 (1 file(s))');
    }

    /** @test */
    function it_can_print_a_file()
    {
        $this->mockRunMethod();

        (new Printer('PrinterA', $this->cli))
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_an_option()
    {
        $this->mockRunMethod([
            '-o',
            'something',
            '-o',
            'some=else',
        ]);

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
        $this->mockRunMethod();

        (new Printer('PrinterA', $this->cli))
            ->setOption('something')
            ->setOption('some', 'else')
            ->resetOptions()
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_A4_media_type()
    {
        $this->mockRunMethod([
            '-o',
            'media=A4',
        ]);

        (new Printer('PrinterA', $this->cli))
            ->media(Printer::MEDIA_A4)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_letter_media_type()
    {
        $this->mockRunMethod([
            '-o',
            'media=Letter',
        ]);

        (new Printer('PrinterA', $this->cli))
            ->media(Printer::MEDIA_LETTER)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_legal_or_upper_media_type()
    {
        $this->mockRunMethod([
            '-o',
            'media=Legal,Upper',
        ]);

        (new Printer('PrinterA', $this->cli))
            ->media(Printer::MEDIA_LEGAL, Printer::MEDIA_UPPER)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }
}
