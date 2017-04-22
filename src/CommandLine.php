<?php

namespace TheNodi\PrinterWrapper;

use Symfony\Component\Process\Process;

class CommandLine
{
    /**
     * Run the given command and return the output.
     *
     * @param  string $command
     * @param  callable $onError
     * @return string
     */
    public function run($command, callable $onError = null)
    {
        $onError = $onError ?: function () {
        };

        $process = new Process($command);

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
