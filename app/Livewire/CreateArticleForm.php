<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Livewire\WithFileUploads; // Abilita il caricamento dei file!
use Illuminate\Support\Facades\Auth;

class CreateArticleForm extends Component
{
    use WithFileUploads; // Attiviamo il modulo dei file multimediali

    // Proprietà del form
    public $title;
    public $description;
    public $price;
    public $category_id;

    // Proprietà per la gestione delle immagini (User Story 5)
    public $images = [];
    public $temporary_images = [];

    // Regole di validazione
    protected $rules = [
        'title' => 'required|min:4',
        'description' => 'required|min:10',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'temporary_images.*' => 'image|max:2048', // Ogni file deve essere un'immagine max 2MB
    ];

    /**
     * Ascolta in tempo reale quando l'utente seleziona i file dal computer (In blocco!)
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
     * CRITERIO RICHIESTO: Rimuove una singola immagine dall'anteprima prima di salvare
     */
    public function removeImage($key)
    {
        if (array_key_exists($key, $this->images)) {
            unset($this->images[$key]);
        }
        // Resettiamo le chiavi dell'array per evitare buchi negli indici
        $this->images = array_values($this->images);
    }

    /**
     * Salva l'annuncio e le sue immagini associate nel Database e nello Storage
     */
    public function store()
    {
        $this->validate();

        // 1. Creiamo l'articolo (nasce in sospeso, is_accepted = null come vuole il prof!)
        $article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'user_id' => Auth::id(),
            'is_accepted' => null, 
        ]);

        // 2. Se l'utente ha inserito delle immagini, le salviamo fisicamente
        if (count($this->images)) {
            foreach ($this->images as $image) {
                // Salviamo il file nella cartella privata "articles" dentro lo storage pubblico
                $path = $image->store('articles', 'public');
                
                // Salviamo il percorso nel database collegandolo all'articolo appena nato
                $article->images()->create(['path' => $path]);
            }
        }

        session()->flash('successMessage', 'Annuncio inserito con successo! Verrà pubblicato dopo la revisione.');
        
        $this->cleanForm();
    }

    /**
     * Svuota i campi del form dopo il salvataggio
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
