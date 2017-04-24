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

    /**
     * Get Printer object to test
     *
     * @return Printer
     */
    protected function getPrinter()
    {
        return new Printer('PrinterA', $this->cli);
    }

    /** @test */
    function it_can_print_a_file()
    {
        $this->mockRunMethod();

        $this->getPrinter()
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

        $this->getPrinter()
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

        $this->getPrinter()
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

        $this->getPrinter()
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

        $this->getPrinter()
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

        $this->getPrinter()
            ->media(Printer::MEDIA_LEGAL, Printer::MEDIA_UPPER)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_custom_media_type()
    {
        $this->mockRunMethod([
            '-o',
            'media=Custom.100x200',
        ]);

        $this->getPrinter()
            ->customMedia(100, 200)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_landscape()
    {
        $this->mockRunMethod([
            '-o',
            'landscape',
        ]);

        $this->getPrinter()
            ->orientation(Printer::ORIENTATION_LANDSCAPE)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_portrait()
    {
        $this->mockRunMethod();

        $this->getPrinter()
            ->orientation(Printer::ORIENTATION_LANDSCAPE)
            ->orientation(Printer::ORIENTATION_PORTRAIT)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_set_rotation_degrees()
    {
        $this->mockRunMethod([
            '-o',
            'orientation-requested=5',
        ]);

        $this->getPrinter()
            ->orientation(Printer::ORIENTATION_270_DEGREES)
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_print_two_sided()
    {
        $this->mockRunMethod([
            '-o',
            'sides=two-sided-short-edge',
        ]);

        $this->getPrinter()
            ->setTwoSided('short')
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_print_two_sided_on_portrait()
    {
        $this->mockRunMethod([
            '-o',
            'sides=two-sided-long-edge',
        ]);

        $this->getPrinter()
            ->orientation(Printer::ORIENTATION_PORTRAIT)
            ->twoSided()
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_print_two_sided_on_landscape()
    {
        $this->mockRunMethod([
            '-o',
            'landscape',
            '-o',
            'sides=two-sided-short-edge',
        ]);

        $this->getPrinter()
            ->orientation(Printer::ORIENTATION_LANDSCAPE)
            ->twoSided()
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }

    /** @test */
    function it_can_print_one_sided()
    {
        $this->mockRunMethod([
            '-o',
            'sides=one-sided',
        ]);

        $this->getPrinter()
            ->oneSided()
            ->printFile('/tmp/randomfile.txt');

        // Assertion is done on mocking, avoid phpunit warning
        $this->assertTrue(true);
    }
}
