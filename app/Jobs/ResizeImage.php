<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image; // Facade classica di v2

class ResizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $w;
    private $h;
    private $path;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $w, $h)
    {
        $this->path = $filePath; // Salviamo il percorso relativo (es. "articles/nomefile.jpg")
        $this->w = $w;
        $this->h = $h;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ricaviamo i percorsi assoluti precisi dentro la cartella storage/app/public/
        $srcPath = storage_path('app/public/' . $this->path);
        
        $dirName = dirname($this->path);
        $baseName = basename($this->path);
        $destPath = storage_path('app/public/' . $dirName . '/crop_' . $this->w . 'x' . $this->h . '_' . $baseName);

        // Se il file originale esiste, eseguiamo il crop e salviamo
        if (file_exists($srcPath)) {
            Image::make($srcPath)->fit($this->w, $this->h)->save($destPath);
        }
    }
}
