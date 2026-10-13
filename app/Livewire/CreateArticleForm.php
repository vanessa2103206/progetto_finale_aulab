<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CreateArticleForm extends Component
{
    public $title;
    public $price;
    public $description;
    public $category_id;

    protected $rules = [
        'title' => 'required|min:4',
        'price' => 'required|numeric|min:0.01',
        'description' => 'required|min:10',
        'category_id' => 'required',
    ];

    protected $messages = [
        'required' => 'Il campo è obbligatorio.',
        'min' => 'Il testo inserito è troppo corto.',
        'numeric' => 'Inserisci un valore numerico valido.',
    ];

    public function store()
    {
        $this->validate();

        Auth::user()->articles()->create([
            'title' => $this->title,
            'price' => $this->price,
            'description' => $this->description,
            'category_id' => $this->category_id,
        ]);

        session()->flash('successMessage', 'Annuncio inserito con successo!');

        $this->cleanForm();
    }

    public function cleanForm()
    {
        $this->title = '';
        $this->price = '';
        $this->description = '';
        $this->category_id = '';
    }

    public function render()
    {
        return view('livewire.create-article-form', [
            'categories' => Category::all()
        ]);
    }
}
