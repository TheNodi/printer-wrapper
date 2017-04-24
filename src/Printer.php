<?php

namespace TheNodi\PrinterWrapper;


use TheNodi\PrinterWrapper\Exceptions\FileNotFoundException;
use TheNodi\PrinterWrapper\Exceptions\PrinterCommandException;

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

    /**
     * Print a file
     *
     * @param string $path
     * @return $this
     */
    public function printFile($path)
    {
        $this->cli->run("lp",
            [
                '-d',
                $this->getId(),
                $path
            ],
            function ($code, $output) use ($path) {
                if (strpos($output, 'No such file or directory') !== false) {
                    throw new FileNotFoundException("File not found: {$path}");
                }

                throw new PrinterCommandException("Print command returned with status code {$code}");
            });

        return $this;
    }
}
