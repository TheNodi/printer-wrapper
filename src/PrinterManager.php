<?php

namespace TheNodi\PrinterWrapper;

class PrinterManager
{
    /**
     * Command Line wrapper
     *
     * @var CommandLine
     */
    protected $cli;

    /**
     * Available printers
     *
     * @var Printer[]
     */
    protected $printers;

    /**
     * PrinterManager constructor.
     *
     * @param CommandLine $cli
     */
    public function __construct($cli = null)
    {
        $this->cli = $cli ?: new CommandLine();
    }

    /**
     * Array of available printers
     *
     * @return Printer[]
     */
    public function printers()
    {
        if (is_null($this->printers)) {
            $this->printers = $this->fetchPrinters();
        }

        return $this->printers;
    }

    /**
     * Fetch printers from system
     *
     * @return array
     */
    protected function fetchPrinters()
    {
        $output = $this->cli->run('LANG=en lpstat -a');

        $printers = [];

        foreach (explode("\n", $output) as $line) {
            $id = substr($line, 0, strpos($line, " "));

            if (!empty($id)) {
                $printers[] = $this->buildPrinter(trim($id));
            }
        }

        return $printers;
    }

    /**
     * Build printer object from id
     *
     * @param string $id
     * @return Printer
     */
    protected function buildPrinter($id)
    {
        return new Printer($id, $this->cli);
    }

    /**
     * Get the default printer
     *
     * @return null|Printer
     */
    public function default()
    {
        $output = $this->cli->run('LANG=en lpstat -d');
        $id = trim(substr($output, strrpos($output, " ") + 1));

        foreach ($this->printers() as $printer) {
            if ($printer->getId() == $id) {
                return $printer;
            }
        }

        return null;
    }
}
