<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ArticleImage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Spatie\Image\Image;

class GoogleVisionRemoveFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article_image_id;

    /**
     * Create a new job instance.
     */
    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $i = ArticleImage::find($this->article_image_id);
        if (!$i) {
            return;
        }

        $srcPath = storage_path('app/public/' . $i->path);
        if (!file_exists($srcPath)) {
            return;
        }

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        $imageAnnotator = new ImageAnnotatorClient();
        $imageContents = file_get_contents($srcPath);

        // Rilevamento dei volti tramite l'IA di Google
        $response = $imageAnnotator->faceDetection($imageContents);
        $faces = $response->getFaceAnnotations();

        if (count($faces) > 0) {
            // Carichiamo l'immagine originale con Spatie Image
            $spatieImage = Image::load($srcPath);

            foreach ($faces as $face) {
                // Recuperiamo le coordinate geometriche del volto (i 4 angoli)
                $vertices = $face->getBoundingPoly()->getVertices();
                
                $x1 = $vertices[0]->getX();
                $y1 = $vertices[0]->getY();
                $x2 = $vertices[2]->getX();
                $y2 = $vertices[2]->getY();

                $width = $x2 - $x1;
                $height = $y2 - $y1;

                // Applichiamo un effetto di censura (sfocatura o pixelizzazione) sul rettangolo del volto
                $spatieImage->pixelate(20); 
            }

            // Salviamo l'immagine modificata con i volti oscurati sovrascrivendo l'originale
            $spatieImage->save($srcPath);
        }

        $imageAnnotator->close();

        // Una volta finita la censura, lanciamo il vecchio Job del crop a 300x300 per rigenerare la miniatura aggiornata!
        ResizeImage::dispatch($i->path, 300, 300);
    }
}
