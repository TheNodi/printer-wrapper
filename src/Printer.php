<?php

namespace TheNodi\PrinterWrapper;

use TheNodi\PrinterWrapper\Exceptions\FileNotFoundException;
use TheNodi\PrinterWrapper\Exceptions\PrinterCommandException;

class Printer
{
    const MEDIA_LETTER = 'Letter'; // US Letter (8.5x11 inches, or 216x279mm)
    const MEDIA_LEGAL = 'Legal'; // US Legal (8.5x14 inches, or 216x356mm)
    const MEDIA_A4 = 'A4'; // ISO A4 (8.27x11.69 inches, or 210x297mm)
    const MEDIA_COM10 = 'COM10'; // US #10 Envelope (9.5x4.125 inches, or 241x105mm)
    const MEDIA_DL = 'DL'; // ISO DL Envelope (8.66x4.33 inches, or 220x110mm)
    const MEDIA_TRANSPARENCY = 'Transparency'; // Transparency media type or source
    const MEDIA_UPPER = 'Upper'; // Upper paper tray
    const MEDIA_LOWER = 'Lower'; // Lower paper tray
    const MEDIA_MULTIPURPOSE = 'MultiPurpose'; // Multi-purpose paper tray
    const MEDIA_LARGE_CAPACITY = 'LargeCapacity'; // Large capacity paper tray

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

            $value = is_array($value) ? implode(',', $value) : $value;
            $options[] = $value === true ? $name : "{$name}={$value}";
        }

        return $options;
    }

    /**
     * Set printer media type.
     * Multiple media are supported and can be achieved
     * passing multiple arguments or an array.
     *
     * Use Printer::MEDIA_* constants for quick reference.
     *
     * @params
     * @return $this
     */
    public function media()
    {
        $type = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

        return $this->setOption('media', $type);
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
