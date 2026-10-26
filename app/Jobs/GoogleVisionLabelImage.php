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

class GoogleVisionLabelImage implements ShouldQueue
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

        // Recuperiamo il contenuto binario dell'immagine (Pagina 116)
        $image = file_get_contents($srcPath);

        // Impostiamo la credenziale protetta nel file json
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . base_path('google_credential.json'));

        // CONFIGURAZIONE BATCH PER LE ETICHETTE RICHIESTA DAL PROFESSORE
        $imageAnnotatorClient = new ImageAnnotatorClient();
        
        $google_image = new VisionImage();
        $google_image->setContent($image);

        $googleFeature = new Feature();
        $googleFeature->setType(Type::LABEL_DETECTION); // Tipo specifico per estrazione tag (Riga 49)

        $request = new AnnotateImageRequest();
        $request->setImage($google_image);
        $request->setFeatures([$googleFeature]);

        $batchRequest = new BatchAnnotateImagesRequest();
        $batchRequest->setRequests([$request]);

        // Chiamata formale all'IA di Google
        $responseBatch = $imageAnnotatorClient->batchAnnotateImages($batchRequest);
        $responses = $responseBatch->getResponses();
        
        $imageAnnotatorClient->close();

        // SALVATAGGIO DEI TAG ESTRATTI NEL DATABASE (PAGINE 116-117)
        if ($responses && isset($responses)) {
            // Prendiamo la prima risposta dall'array dei risultati batch
            $firstResponse = $responses[0];
            $labels = $firstResponse->getLabelAnnotations(); // Riga 61 del PDF
            
            if ($labels) {
                $result = [];
                foreach ($labels as $label) {
                    $result[] = $label->getDescription();
                }
                
                $i->labels = $result; // Salviamo l'array nella colonna (Riga 69 del PDF)
                $i->save();
            }
        }
    }
}
