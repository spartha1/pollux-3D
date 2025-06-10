<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class StlMetadataService
{
    protected $pythonVenv;

    public function __construct()
    {
        $this->pythonVenv = base_path('python/venv/Scripts/python');
        if (PHP_OS !== 'WINNT') {
            $this->pythonVenv = base_path('python/venv/bin/python');
        }
    }

    public function extract(UploadedFile $file)
    {
        $pythonScript = base_path('python/stl_analyzer.py');
        $filePath = $file->getRealPath();

        $process = new Process([$this->pythonVenv, $pythonScript, $filePath]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $pythonOutput = $process->getOutput();
        $stlData = json_decode($pythonOutput, true);

        return array_merge([
            'fileSize' => $file->getSize(),
            'fileName' => $file->getClientOriginalName(),
            'fileType' => $file->getMimeType(),
        ], $stlData ?? []);
    }
}
