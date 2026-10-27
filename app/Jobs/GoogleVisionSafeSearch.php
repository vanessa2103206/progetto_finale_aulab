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

    public function __construct($article_image_id)
    {
        $this->article_image_id = $article_image_id;
    }

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

        $image = file_get_contents($srcPath);
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

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

        $responseBatch = $imageAnnotatorClient->batchAnnotateImages($batchRequest);
        $responses = $responseBatch->getResponses();
        
        $imageAnnotatorClient->close();

        if ($responses && isset($responses[0])) {
            // Estrazione con indice zero come richiesto a pagina 112 del PDF
            $safe = $responses[0]->getSafeSearchAnnotation();
            
            if ($safe) {
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

                $i->adult = $likelihoodName[$safe->getAdult()];
                $i->spoof = $likelihoodName[$safe->getSpoof()];
                $i->medical = $likelihoodName[$safe->getMedical()];
                $i->violence = $likelihoodName[$safe->getViolence()];
                $i->racy = $likelihoodName[$safe->getRacy()];
                $i->save();
            }
        }
    }
}
