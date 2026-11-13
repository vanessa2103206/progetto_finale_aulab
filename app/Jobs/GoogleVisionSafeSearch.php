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
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
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

        $googleVisionClient = new ImageAnnotatorClient();
        $google_image = new VisionImage();
        $google_image->setContent($image);

        $googleFeature = new Feature();
        $googleFeature->setType(Type::SAFE_SEARCH_DETECTION);

        $request = new AnnotateImageRequest();
        $request->setImage($google_image);
        $request->setFeatures([$googleFeature]);

        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$request]);

        $responseBatch = $googleVisionClient->batchAnnotateImages($batchRequest);
        $responses = $responseBatch->getResponses();
        $googleVisionClient->close();

if ($responses && isset($responses[0])) {
$response = $responses[0];
    $safeSearchAnnotation = $response->getSafeSearchAnnotation();


            if ($safeSearchAnnotation) {
                $adult = $safeSearchAnnotation->getAdult();
                $spoof = $safeSearchAnnotation->getSpoof();
                $medical = $safeSearchAnnotation->getMedical();
                $violence = $safeSearchAnnotation->getViolence();
                $racy = $safeSearchAnnotation->getRacy();

                $likelihoodName = [
                    'text-secondary bi bi-circle-fill',
                    'text-success bi bi-circle-fill',
                    'text-success bi bi-circle-fill',
                    'text-warning bi bi-circle-fill',
                    'text-warning bi bi-circle-fill',
                    'text-danger bi bi-circle-fill',
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
