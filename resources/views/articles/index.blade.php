<x-layout>
    <div class="container-fluid text-center bg-body-tertiary py-5 shadow-sm rounded mb-5">
        <h1 class="display-4 fw-bold">Tutti gli Annunci</h1>
        <p class="lead">Esplora le occasioni pubblicate dagli utenti di Presto.it</p>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            @forelse($articles as $article)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        
                        <!-- Immagini rimpicciolite e centrate -->
                        @if(Str::contains(strtolower($article->title), 'maglia'))
                            <img src="{{ asset('images/maglia.png') }}" class="card-img-top w-50 mx-auto pt-3" alt="Maglia nera" style="height: 180px; object-fit: contain;">
                        @elseif(Str::contains(strtolower($article->title), 'portatile') || Str::contains(strtolower($article->title), 'pc'))
                            <img src="{{ asset('images/portatile.png') }}" class="card-img-top w-50 mx-auto pt-3" alt="Computer Portatile" style="height: 180px; object-fit: contain;">
                        @else
                            <div class="bg-secondary text-white text-center py-5 fw-bold" style="height: 180px; line-height: 100px;">Nessuna Immagine</div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-1">{{ $article->title }}</h5>
                            <span class="badge bg-success align-self-start mb-3">{{ $article->category->name }}</span>
                            
                            <p class="card-text text-muted text-truncate flex-grow-1">{{ $article->description }}</p>
                            
                            <hr class="text-black-50">
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="fs-4 fw-bold text-success">{{ number_format($article->price, 2, ',', '.') }} €</span>
                                <!-- Pulsante collegato alla rotta di dettaglio -->
                                <a href="{{ route('articles.show', compact('article')) }}" class="btn btn-primary btn-sm shadow-sm fw-bold">Visualizza</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="alert alert-warning shadow-sm" role="alert">
                        <h4 class="alert-heading fw-bold">Nessun annuncio presente!</h4>
                        <p class="mb-0">Non ci sono ancora articoli pubblicati sul portale.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
