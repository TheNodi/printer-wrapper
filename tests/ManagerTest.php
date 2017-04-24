<?php

namespace TheNodi\PrinterWrapper\Tests;


use TheNodi\PrinterWrapper\Printer;

class ManagerTest extends TestCase
{
    /** @test */
    function it_can_retrieve_all_printers()
    {
        $this->cli->shouldReceive('run')
            ->withArgs(['lpstat -a'])
            ->once()
            ->andReturn(implode("\n", [
                'PrinterA accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterB accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterC accepting requests since Thu Apr 20 21:26:29 2017',
            ]));

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
            ->withArgs(['lpstat -a'])
            ->once()
            ->andReturn('');

        $first = $this->manager->printers();
        $second = $this->manager->printers();

        $this->assertEmpty($first);
        $this->assertEmpty($second);
    }

    /** @test */
    function it_can_find_default_printer() {
        $this->cli->shouldReceive('run')
            ->withArgs(['lpstat -a'])
            ->once()
            ->andReturn(implode("\n", [
                'PrinterA accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterB accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterC accepting requests since Thu Apr 20 21:26:29 2017',
            ]));
        $this->cli->shouldReceive('run')
            ->withArgs(['lpstat -d'])
            ->once()
            ->andReturn('system default destination: PrinterB');

        $printer = $this->manager->default();

        $this->assertInstanceOf(Printer::class, $printer);
        $this->assertEquals('PrinterB', $printer->getId());
    }

    /** @test */
    function it_may_not_find_default_printer() {
        $this->cli->shouldReceive('run')
            ->withArgs(['lpstat -a'])
            ->once()
            ->andReturn(implode("\n", [
                'PrinterA accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterB accepting requests since Thu Apr 20 21:26:29 2017',
                'PrinterC accepting requests since Thu Apr 20 21:26:29 2017',
            ]));
        $this->cli->shouldReceive('run')
            ->withArgs(['lpstat -d'])
            ->once()
            ->andReturn('system default destination: PrinterD');

        $printer = $this->manager->default();

        $this->assertNull($printer);
    }
}
