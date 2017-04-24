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
     * @param  callable $onError
     * @return string
     */
    public function run($command, callable $onError = null)
    {
        // Manually set lang to english so we can parse command output reliably
        $command = 'LANG=en ' . $command;

        $onError = $onError ?: function ($code) use ($command) {
            throw new PrinterCommandException("\"{$command}\" returned with status code {$code}");
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
