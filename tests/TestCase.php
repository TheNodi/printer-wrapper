<?php

namespace TheNodi\PrinterWrapper\Tests;


use TheNodi\PrinterWrapper\CommandLine;
use TheNodi\PrinterWrapper\PrinterManager;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * CommandLine mock
     *
     * @var CommandLine|\Mockery\MockInterface
     */
    protected $cli;

    /**
     * PrinterManager instance to test
     *
     * @var PrinterManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->cli = \Mockery::mock(CommandLine::class);
        $this->manager = new PrinterManager($this->cli);
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        \Mockery::close();
    }
}
