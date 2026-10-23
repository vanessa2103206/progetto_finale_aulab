<x-layout>
    <!-- Hero Section dell'applicazione Presto.it -->
    <div class="container-fluid text-center bg-dark text-white py-5 shadow-sm rounded mb-5">
        <h1 class="display-3 fw-bold">Presto.it</h1>
        <p class="lead text-muted">Il portale di annunci più veloce d'Italia</p>
        @if (session()->has('successMessage'))
            <div class="alert alert-success d-inline-block shadow-sm mt-3 px-5" role="alert">
                {{ session('successMessage') }}
            </div>
        @endif
    </div>

    <!-- Sezione degli ultimi annunci approvati -->
    <div class="container my-5">
        <h2 class="text-center fw-bold text-dark mb-4">Ultimi Annunci Inseriti</h2>
        <div class="row justify-content-center">
            @forelse($articles as $article)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        
                        <!-- CODICE AGGIORNATO E CORRETTO PER IMMAGINE REALE (USER STORY 3) -->
                        <div class="text-center bg-light" style="height: 200px;">
                            @if($article->images->count())
                                <!-- Estrae la prima immagine reale caricata per l'articolo nello storage -->
                                <img src="{{ Storage::url($article->images->first()->path) }}" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="{{ $article->title }}">
                            @else
                                <!-- Fallback standard se l'articolo non ha immagini associate -->
                                <div class="bg-secondary text-white text-center py-5 fw-bold h-100" style="line-height: 150px;">Nessuna Immagine</div>
                            @endif
                        </div>
                        
                        <!-- Corpo dei dettagli della card dell'annuncio -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-1">{{ $article->title }}</h5>
                            <span class="badge bg-success align-self-start mb-3">{{ $article->category->name }}</span>
                            <p class="card-text text-muted text-truncate flex-grow-1">{{ $article->description }}</p>
                            <hr class="text-black-50">
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="fs-4 fw-bold text-success">{{ number_format($article->price, 2, ',', '.') }} €</span>
                                <a href="{{ route('articles.show', compact('article')) }}" class="btn btn-primary btn-sm shadow-sm fw-bold">Visualizza</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Messaggio nel caso in cui non ci siano prodotti in elenco -->
                <div class="col-12 text-center py-5">
                    <p class="text-muted lead">Non ci sono ancora annunci disponibili in questa sezione.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
