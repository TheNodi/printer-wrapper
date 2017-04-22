<?php

namespace TheNodi\PrinterWrapper;


class Printer
{
    /**
     * Printer identifier
     *
     * @var string
     */
    protected $id;

    /**
     * CommandLine Wrapper
     *
     * @var CommandLine
     */
    protected $cli;

    /**
     * Printer constructor.
     * @param string $id
     * @param CommandLine $cli
     */
    public function __construct($id, $cli = null)
    {
        $this->id = $id;
        $this->cli = $cli ?: new CommandLine();
    }

    /**
     * Get printer identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
