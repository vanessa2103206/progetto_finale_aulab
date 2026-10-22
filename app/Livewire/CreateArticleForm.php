<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ResizeImage;
use App\Jobs\GoogleVisionSafeSearch;
use App\Jobs\GoogleVisionLabelImage;
use App\Jobs\RemoveFaces; // Importiamo il nuovo Job ufficiale della guida!

class CreateArticleForm extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    public $price;
    public $category_id;
    public $images = [];
    public $temporary_images = [];

    protected $rules = [
        'title' => 'required|min:4',
        'description' => 'required|min:10',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'temporary_images.*' => 'image|max:2048',
    ];

    public function updatedTemporaryImages()
    {
        if ($this->validate(['temporary_images.*' => 'image|max:2048'])) {
            foreach ($this->temporary_images as $image) {
                $this->images[] = $image;
            }
        }
    }

    public function removeImage($key)
    {
        if (array_key_exists($key, $this->images)) {
            unset($this->images[$key]);
        }
        $this->images = array_values($this->images);
    }

    public function store()
    {
        $this->validate();

        $article = Article::create([
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'user_id' => Auth::id(),
            'is_accepted' => null, 
        ]);

        if (count($this->images)) {
            foreach ($this->images as $image) {
                $path = $image->store('articles', 'public');
                $newImage = $article->images()->create(['path' => $path]);

                // METODO WITHCHAIN() ESATTO DELLA GUIDA UFFICIALE DI AULAB!
                // Mette in coda sequenziale ordinata tutti i lavori delle immagini
                ResizeImage::dispatch($newImage->path, 300, 300)
                    ->withChain([
                        new GoogleVisionSafeSearch($newImage->id),
                        new GoogleVisionLabelImage($newImage->id),
                        new RemoveFaces($newImage->id)
                    ]);
            }
        }

        session()->flash('successMessage', 'Annuncio inserito con successo! Verrà pubblicato dopo la revisione.');
        $this->cleanForm();
    }

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
