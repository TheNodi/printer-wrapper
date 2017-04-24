<?php

namespace TheNodi\PrinterWrapper\Tests;


use TheNodi\PrinterWrapper\Printer;

class ManagerTest extends TestCase
{
    /**
     * Mock printers
     *
     * @return $this
     */
    protected function mockPrinters()
    {
        $this->cli->shouldReceive('run')
            ->with('lpstat', '-a')
            ->once()
            ->andReturn(implode("\n", [
                'PrinterA accepting requests since Thu Jan 01 00:00:00 1970',
                'PrinterB not accepting requests since Thu Jan 01 00:00:00 1970 -',
                '   Rejecting Jobs',
                'PrinterC accepting requests since Thu Jan 01 00:00:00 1970',
            ]));

        return $this;
    }

    /**
     * Mock default printer
     *
     * @param string $printer Printer name
     * @return $this
     */
    protected function mockDefaultPrinter($printer)
    {
        $this->cli->shouldReceive('run')
            ->with('lpstat', '-d')
            ->once()
            ->andReturn('system default destination: ' . $printer);

        return $this;
    }

    /** @test */
    function it_can_retrieve_all_printers()
    {
        $this->mockPrinters();

        $printers = $this->manager->printers();

        $this->assertCount(3, $printers);
        $this->assertEquals('PrinterA', $printers[0]->getId());
        $this->assertEquals('PrinterB', $printers[1]->getId());
        $this->assertEquals('PrinterC', $printers[2]->getId());
    }

    /** @test */
    function it_does_not_run_fetch_multiple_times()
    {
        $this->cli->shouldReceive('run')
            ->with('lpstat', '-a')
            ->once()
            ->andReturn('');

        $first = $this->manager->printers();
        $second = $this->manager->printers();

        $this->assertEmpty($first);
        $this->assertEmpty($second);
    }

    /** @test */
    function it_can_find_default_printer()
    {
        $this->mockPrinters();
        $this->mockDefaultPrinter('PrinterB');

        $printer = $this->manager->default();

        $this->assertInstanceOf(Printer::class, $printer);
        $this->assertEquals('PrinterB', $printer->getId());
    }

    /** @test */
    function it_may_not_find_default_printer()
    {
        $this->mockPrinters();
        $this->mockDefaultPrinter('PrinterD');

        $printer = $this->manager->default();

        $this->assertNull($printer);
    }

    /** @test */
    function it_proxies_commands_to_default_printer()
    {
        $this->mockPrinters();
        $this->mockDefaultPrinter('PrinterB');

        $this->assertInstanceOf(Printer::class, $this->manager->landscape());
    }
}
