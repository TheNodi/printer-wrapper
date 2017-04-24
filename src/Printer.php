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
     * Print options
     *
     * @var array
     */
    protected $options = [];

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
     * Set print option
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setOption($name, $value = true)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Reset print options
     *
     * @return $this
     */
    public function resetOptions()
    {
        $this->options = [];

        return $this;
    }

    /**
     * Build lp options
     *
     * @return array
     */
    protected function buildOptions()
    {
        $options = [];

        foreach ($this->options as $name => $value) {
            $options[] = '-o';

            $options[] = $value === true ? $name : "{$name}={$value}";
        }

        return $options;
    }

    /**
     * Print a file
     *
     * @param string $path
     * @return $this
     */
    public function printFile($path)
    {
        $args = array_merge(['-d', $this->getId()], $this->buildOptions(), [$path]);

        $this->cli->run("lp", $args, function ($code, $output) use ($path) {
            if (strpos($output, 'No such file or directory') !== false) {
                throw new FileNotFoundException("File not found: {$path}");
            }

            throw new PrinterCommandException("Print command returned with status code {$code}");
        });

        return $this;
    }
}
