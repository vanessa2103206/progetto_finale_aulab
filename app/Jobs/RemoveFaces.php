<?php

namespace App\Jobs;

use App\Models\ArticleImage;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\Image as VisionImage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Spatie\Image\Enums\AlignPosition;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image as SpatieImage;

class RemoveFaces implements ShouldQueue
{
    use Queueable;

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
        $image = file_get_contents($srcPath);

        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        $googleVisionClient = new ImageAnnotatorClient();
        $google_image = new VisionImage();
        $google_image->setContent($image);

        $googleFeature = new Feature();
        $googleFeature->setType(Type::FACE_DETECTION);

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
            $faces = $response->getFaceAnnotations();

            foreach ($faces as $face) {
                $vertices = $face->getBoundingPoly()->getVertices();
                $bounds = [];

                foreach ($vertices as $vertex) {
                    $bounds[] = [$vertex->getX(), $vertex->getY()];
                }

                $w = $bounds[2][0] - $bounds[0][0];
                $h = $bounds[2][1] - $bounds[0][1];

                $image = SpatieImage::load($srcPath);
                $image->watermark(
                    base_path('resources/img/watermark.png'),
                    AlignPosition::TopLeft,
                    paddingX: $bounds[0][0],
                    paddingY: $bounds[0][1],
                    width: $w,
                    height: $h,
                    fit: Fit::Stretch
                );
                $image->save($srcPath);
            }
        }
    }
}
