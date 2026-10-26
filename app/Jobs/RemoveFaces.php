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

        $imageContents = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        $imageAnnotatorClient = new ImageAnnotatorClient();
        
        $google_image = new VisionImage();
        $google_image->setContent($imageContents);

        $googleFeature = new Feature();
        $googleFeature->setType(Type::FACE_DETECTION); // Rilevamento dei volti

        $request = new AnnotateImageRequest();
        $request->setImage($google_image);
        $request->setFeatures([$googleFeature]);

        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$request]);

        $responseBatch = $imageAnnotatorClient->batchAnnotateImages($batchRequest);
        $responses = $responseBatch->getResponses();
        
        $imageAnnotatorClient->close();

        if ($responses && isset($responses)) {
            $faces = $responses->getFaceAnnotations();
            
            if ($faces && count($faces) > 0) {
                $spatieImage = SpatieImage::load($srcPath);

                foreach ($faces as $face) {
                    $vertices = $face->getBoundingPoly()->getVertices();
                    
                    $bounds = [];
                    foreach ($vertices as $vertex) {
                        $bounds[] = [$vertex->getX(), $vertex->getY()];
                    }

                    $w = $bounds[2][0] - $bounds[0][0];
                    $h = $bounds[2][1] - $bounds[0][1];

                    // Applichiamo una filigrana coprente sopra il volto per la privacy
                    $spatieImage->watermark(
                        base_path('resources/img/watermark.png'),
                        paddingX: $bounds[0][0],
                        paddingY: $bounds[0][1],
                        width: $w,
                        height: $h,
                        fit: Fit::Stretch
                    );
                }

                $spatieImage->save($srcPath);
            }
        }
    }
}
