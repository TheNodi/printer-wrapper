<?php

namespace TheNodi\PrinterWrapper;

use Symfony\Component\Process\Process;
use TheNodi\PrinterWrapper\Exceptions\PrinterCommandException;

class CommandLine
{
    /**
     * Run the given command and return the output.
     *
     * @param  string $command
     * @param  string|array $args
     * @param  null|callable $onError
     * @return string
     */
    public function run($command, $args = [], callable $onError = null)
    {
        $process = $this->buildProcess($command, $args);

        return $this->runProcess($process, $onError);
    }

    /**
     * Build process from array
     *
     * @param string $command
     * @param string|array $args
     * @return Process
     */
    protected function buildProcess($command, $args = [])
    {
        $command = is_array($command) ? $command : [$command];
        $args = is_array($args) ? $args : [$args];

        return new Process(
            array_merge($command, $args),
            null,
            ['LANG' => 'en']
        );
    }

    /**
     * Run process and return the output.
     *
     * @param Process $process
     * @param null|callable $onError
     * @return string
     */
    protected function runProcess(Process $process, callable $onError = null)
    {
        $onError = $onError ?: function ($code, $output) use ($process) {
            throw new PrinterCommandException("\"{$process->getCommandLine()}\" returned with status code {$code}");
        };

        $processOutput = '';
        $process->setTimeout(null)->run(function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        });

        if ($process->getExitCode() > 0) {
            $onError($process->getExitCode(), $processOutput);
        }

        return $processOutput;
    }
}
