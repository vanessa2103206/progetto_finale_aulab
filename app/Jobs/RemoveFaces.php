<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ArticleImage; // Manteniamo il tuo modello delle immagini
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Spatie\Image\Image as SpatieImage;
use Spatie\Image\Enums\Fit;

class RemoveFaces implements ShouldQueue
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
        
        $response = $imageAnnotator->faceDetection($imageContents);
        $faces = $response->getFaceAnnotations();

        if (count($faces) > 0) {
            // Carichiamo l'immagine con Spatie Image come da riga 42 del PDF
            $image = SpatieImage::load($srcPath);

            foreach ($faces as $face) {
                $vertices = $face->getBoundingPoly()->getVertices();
                
                $bounds = [];
                foreach ($vertices as $vertex) {
                    $bounds[] = [$vertex->getX(), $vertex->getY()];
                }

                // Calcolo esatto di larghezza e altezza del volto (Righe 35-37)
                $w = $bounds[2][0] - $bounds[0][0];
                $h = $bounds[2][1] - $bounds[0][1];

                // Applichiamo la censura sopra il volto usando un'immagine o un watermark coprente di sistema
                // Usiamo un file di placeholder temporaneo o una toppa nera
                $image->watermark(
                    base_path('public/favicon.ico'), // Usiamo la favicon del sito come toppa di censura della privacy!
                    paddingX: $bounds[0][0],
                    paddingY: $bounds[0][1],
                    width: $w,
                    height: $h,
                    fit: Fit::Stretch
                );
            }

            // Salviamo l'immagine sovrascrivendola (Riga 56)
            $image->save($srcPath);
        }

        $imageAnnotator->close();
    }
}
