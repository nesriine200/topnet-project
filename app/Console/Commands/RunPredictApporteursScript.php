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
    protected $description = 'Run the Python script that predicts apporteurs validation state';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // If 'predict_apporteur.py' is in the main Laravel directory, use base_path()
        $pythonPath = 'python3';
        $scriptPath = base_path('predict_apporteur.py');

        $process = new Process([$pythonPath, $scriptPath]);
        $process->setTimeout(300); // Optional timeout

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->info("Python script executed successfully.");
        $this->line($process->getOutput());
    }
}