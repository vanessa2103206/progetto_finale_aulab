<x-layout>
    <div class="container-fluid text-center bg-body-tertiary py-5 shadow-sm rounded mb-5">
        <h1 class="display-4 fw-bold">Dettaglio Annuncio</h1>
        <p class="lead">Informazioni complete sull'articolo selezionato</p>
    </div>

    <div class="container bg-white p-5 rounded shadow-sm">
        <div class="row align-items-center">
            <!-- Colonna Immagine -->
            <div class="col-12 col-md-6 text-center mb-4 mb-md-0">
                @if(Str::contains(strtolower($article->title), 'maglia'))
                    <img src="{{ asset('images/maglia.png') }}" class="img-fluid rounded shadow-sm w-50 mx-auto" alt="Maglia nera" style="max-height: 350px; object-fit: contain;">
                @elseif(Str::contains(strtolower($article->title), 'portatile') || Str::contains(strtolower($article->title), 'pc'))
                    <img src="{{ asset('images/portatile.png') }}" class="img-fluid rounded shadow-sm w-50 mx-auto" alt="Computer Portatile" style="max-height: 350px; object-fit: contain;">
                @else
                    <div class="bg-secondary text-white text-center py-5 rounded fw-bold" style="height: 300px; line-height: 200px;">Nessuna Immagine</div>
                @endif
            </div>

            <!-- Colonna Testi e Dettagli -->
            <div class="col-12 col-md-6">
                <span class="badge bg-success mb-2 px-3 py-2 fs-6">{{ $article->category->name }}</span>
                <h2 class="display-5 fw-bold text-dark mb-3">{{ $article->title }}</h2>
                
                <h3 class="text-success fw-bold display-6 mb-4">{{ number_format($article->price, 2, ',', '.') }} €</h3>
                
                <div class="mb-4">
                    <h5 class="fw-bold text-muted">Descrizione dell'articolo:</h5>
                    <p class="lead text-dark" style="white-space: pre-line;">{{ $article->description }}</p>
                </div>
                
                <hr class="my-4">
                
                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                    Torna a tutti gli annunci
                </a>
            </div>
        </div>
    </div>
</x-layout>
