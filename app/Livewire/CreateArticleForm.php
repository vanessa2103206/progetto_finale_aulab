<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ResizeImage;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\GoogleVisionLabelImage; // IMPORTAZIONE UFFICIALE RICHIESTA A PAGINA 119!
use App\Jobs\RemoveFaces;

class CreateArticleForm extends Component
{
    use WithFileUploads;

    // Proprietà del form
    public $title;
    public $description;
    public $price;
    public $category_id;

    // Proprietà per la gestione delle immagini
    public $images = [];
    public $temporary_images = [];

    // Regole di validazione
    protected $rules = [
        'title' => 'required|min:4',
        'description' => 'required|min:10',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'temporary_images.*' => 'image|max:2048',
    ];

    /**
     * Ascolta in tempo reale il caricamento delle immagini in blocco
     */
    public function updatedTemporaryImages()
    {
        if ($this->validate(['temporary_images.*' => 'image|max:2048'])) {
            foreach ($this->temporary_images as $image) {
                $this->images[] = $image;
            }
        }
    }

    /**
     * Rimuove una singola immagine dall'anteprima prima del salvataggio
     */
    public function removeImage($key)
    {
        if (array_key_exists($key, $this->images)) {
            unset($this->images[$key]);
        }
        $this->images = array_values($this->images);
    }

    /**
     * Salva l'articolo e lancia i singoli dispatch sequenziali (Pagina 119 PDF)
     */
    public function store()
    {
        $this->validate();

        // 1. Creiamo l'articolo nel database
        $article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'user_id' => Auth::id(),
            'is_accepted' => null, 
        ]);

        // 2. Se ci sono immagini caricate, le salviamo ed eseguiamo i dispatch singoli richiesti dal prof
        if (count($this->images)) {
            foreach ($this->images as $image) {
                $path = $image->store('articles', 'public');
                $newImage = $article->images()->create(['path' => $path]);

                // STRUTTURA SEQUENZIALE SEPARATA CON DISPATCH SINGOLI COME DA DOCUMENTAZIONE AULAB
                dispatch(new ResizeImage($newImage->path, 300, 300));
                dispatch(new GoogleVisionSafeSearch($newImage->id));
                dispatch(new GoogleVisionLabelImage($newImage->id));
                dispatch(new RemoveFaces($newImage->id)); 
            }
        }

        session()->flash('successMessage', 'Annuncio inserito con successo! Verrà pubblicato dopo la revisione.');
        
        $this->cleanForm();
    }

    /**
     * Svuota i campi del modulo dopo il salvataggio
     */
    public function cleanForm()
    {
        $this->title = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
        $this->images = [];
        $this->temporary_images = [];
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.create-article-form', compact('categories'));
    }
}
