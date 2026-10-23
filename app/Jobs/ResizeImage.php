<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Image\Image;
use Spatie\Image\Enums\CropPosition; 
use Spatie\Image\Enums\Unit;         

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
     * Execute the job. (Taglio + Watermark come da PDF ufficiale!)
     */
    public function handle(): void
    {
        $srcPath = storage_path('app/public/' . $this->path . '/' . $this->fileName);
        $destPath = storage_path('app/public/' . $this->path . '/crop_' . $this->w . 'x' . $this->h . '_' . $this->fileName);

        // Sintassi e percorso ufficiali speculari al 100% al tuo PDF!
        Image::load($srcPath)
            ->crop($this->w, $this->h, CropPosition::Center)
            ->watermark(
                base_path('resources/img/watermark.png'), 
                width: 50,
                height: 50,
                paddingX: 10,
                paddingY: 10,
                paddingUnit: Unit::Percent
            )
            ->save($destPath);
    }
}
