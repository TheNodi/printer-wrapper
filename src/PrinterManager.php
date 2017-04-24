<?php

namespace TheNodi\PrinterWrapper;

/**
 * Manage all printers
 *
 * @package TheNodi\PrinterWrapper
 *
 * @mixin Printer
 */
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
     * Proxy commands to default printer
     *
     * @param string $name
     * @param array $arguments
     * @return $this|Printer
     */
    function __call($name, $arguments)
    {
        $printer = $this->default();

        if (!is_null($printer) && method_exists($printer, $name)) {
            return call_user_func_array([$printer, $name], $arguments);
        }

        throw new \RuntimeException("Method {$name} not found.");
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
        $output = $this->cli->run('lpstat', '-a');

        $printers = [];

        foreach (explode("\n", $output) as $line) {
            if (substr($line, 0, 1) == "\t") {
                continue;
            }

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
        $output = $this->cli->run('lpstat', '-d');
        $id = trim(substr($output, strrpos($output, " ") + 1));

        foreach ($this->printers() as $printer) {
            if ($printer->getId() == $id) {
                return $printer;
            }
        }

        return null;
    }
}
