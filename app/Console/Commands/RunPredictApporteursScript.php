<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunPythonScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'python:run-predict-apporteurs-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the custom Python script';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Adjust the path to your python binary and script location as needed
        $process = new Process(['python3', '/var/www/myproject/scripts/your_script.py']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Optional: log or output the result
        $this->info($process->getOutput());
    }
}
