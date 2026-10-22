<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Image\Image; // IMPORTAZIONE UFFICIALE DI SPATIE CHIENDA DAL PROF!

class ResizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $w;
    private $h;
    private $fileName;
    private $path;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $w, $h)
    {
        $this->path = dirname($filePath);
        $this->fileName = basename($filePath);
        $this->w = $w;
        $this->h = $h;
    }

    /**
     * Execute the job. (Taglio automatico con Spatie Image!)
     */
    public function handle(): void
    {
        $srcPath = storage_path('app/public/' . $this->path . '/' . $this->fileName);
        $destPath = storage_path('app/public/' . $this->path . '/crop_' . $this->w . 'x' . $this->h . '_' . $this->fileName);

        // Sintassi ufficiale Spatie Image v3 richiesta dai criteri Aulab
        Image::load($srcPath)
            ->crop($this->w, $this->h)
            ->save($destPath);
    }
}
