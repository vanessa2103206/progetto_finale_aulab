<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ArticleImage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

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

        $imagePath = storage_path('app/public/' . $i->path);
        if (!file_exists($imagePath)) {
            return;
        }

        // Puntiamo al file delle credenziali protetto nel gitignore
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        $imageAnnotator = new ImageAnnotatorClient();
        $image = file_get_contents($imagePath);

        // Chiamata di sicurezza a Google Vision API
        $responseSafeSearch = $imageAnnotator->safeSearchDetection($image);
        $safe = $responseSafeSearch->getSafeSearchAnnotation();

        if ($safe) {
            $adult = $safe->getAdult();
            $spoof = $safe->getSpoof();
            $medical = $safe->getMedical();
            $violence = $safe->getViolence();
            $racy = $safe->getRacy();

            // Mappiamo le classi delle icone di Bootstrap Icons in base alla gravità richiesta dal PDF
            $likelihoodName = [
                'UNKNOWN' => 'text-secondary bi bi-circle-fill',
                'VERY_UNLIKELY' => 'text-success bi bi-circle-fill',
                'UNLIKELY' => 'text-success bi bi-circle-fill',
                'POSSIBLE' => 'text-warning bi bi-circle-fill',
                'LIKELY' => 'text-danger bi bi-circle-fill',
                'VERY_LIKELY' => 'text-danger bi bi-circle-fill'
            ];

            $i->adult = $likelihoodName[$adult];
            $i->spoof = $likelihoodName[$spoof];
            $i->medical = $likelihoodName[$medical];
            $i->violence = $likelihoodName[$violence];
            $i->racy = $likelihoodName[$racy];
        }

        $i->save();
        $imageAnnotator->close();
    }
}
