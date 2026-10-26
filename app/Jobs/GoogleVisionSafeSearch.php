<?php

namespace App\Jobs;

use App\Models\ArticleImage;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image as VisionImage;

class GoogleVisionSafeSearch implements ShouldQueue
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

        // Recuperiamo il contenuto binario dell'immagine (Pagina 110 - Riga 39)
        $image = file_get_contents($srcPath);

        // Impostiamo la credenziale protetta nel file json (Pagina 110 - Riga 40)
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        // CONFIGURAZIONE BATCH RICHIESTA DAL PROFESSORE (RIGHE 42-60)
        $imageAnnotatorClient = new ImageAnnotatorClient();
        
        $google_image = new VisionImage();
        $google_image->setContent($image);

        $googleFeature = new Feature();
        $googleFeature->setType(Type::SAFE_SEARCH_DETECTION);

        $request = new AnnotateImageRequest();
        $request->setImage($google_image);
        $request->setFeatures([$googleFeature]);

        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$request]);

        // Chiamata formale all'IA di Google
        $responseBatch = $imageAnnotatorClient->batchAnnotateImages($batchRequest);
        $responses = $responseBatch->getResponses();
        
        $imageAnnotatorClient->close();

        // MAPPATURA DEI SEMAFORI GRAFICI (PAGINE 111-112)
        if ($responses && isset($responses[0])) {
            $safe = $responses[0]->getSafeSearchAnnotation();
            
            if ($safe) {
                $adult = $safe->getAdult();
                $spoof = $safe->getSpoof();
                $medical = $safe->getMedical();
                $violence = $safe->getViolence();
                $racy = $safe->getRacy();

                // Array di mappatura flessibile per le icone di Bootstrap
                $likelihoodName = [
                    'UNKNOWN' => 'text-secondary bi bi-circle-fill',
                    'VERY_UNLIKELY' => 'text-success bi bi-circle-fill',
                    'UNLIKELY' => 'text-success bi bi-circle-fill',
                    'POSSIBLE' => 'text-warning bi bi-circle-fill',
                    'LIKELY' => 'text-danger bi bi-circle-fill',
                    'VERY_LIKELY' => 'text-danger bi bi-circle-fill',
                    0 => 'text-secondary bi bi-circle-fill',
                    1 => 'text-success bi bi-circle-fill',
                    2 => 'text-success bi bi-circle-fill',
                    3 => 'text-warning bi bi-circle-fill',
                    4 => 'text-danger bi bi-circle-fill',
                    5 => 'text-danger bi bi-circle-fill'
                ];

                $i->adult = $likelihoodName[$adult];
                $i->spoof = $likelihoodName[$spoof];
                $i->medical = $likelihoodName[$medical];
                $i->violence = $likelihoodName[$violence];
                $i->racy = $likelihoodName[$racy];
                $i->save();
            }
        }
    }
}
