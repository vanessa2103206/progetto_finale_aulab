<x-layout>
    <div class="container-fluid text-center bg-body-tertiary py-5 rounded shadow-sm mb-5">
        <h1 class="display-4 fw-bold">Dettaglio Annuncio</h1>
        <p class="lead text-muted">Informazioni complete sull'articolo selezionato</p>
    </div>

    <div class="container my-5">
        <div class="card shadow border-0 p-5 bg-white rounded">
            <div class="row align-items-center">
                
                <!-- SEZIONE IMMAGINE REALE AGGIORNATA (USER STORY 3 / 6) -->
                <div class="col-12 col-md-6 text-center mb-4 mb-md-0">
                    @if($article->images->count())
                        <div id="carouselExample" class="carousel slide shadow-sm rounded overflow-hidden">
                            <div class="carousel-inner">
                                @foreach($article->images as $key => $image)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img src="{{ Storage::url($image->path) }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;" alt="{{ $article->title }}">
                                    </div>
                                @endforeach
                            </div>
                            @if($article->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <!-- Fallback se non ci sono immagini nel database -->
                        <div class="bg-secondary text-white text-center py-5 rounded fw-bold" style="height: 300px; line-height: 200px;">
                            Nessuna Immagine
                        </div>
                    @endif
                </div>

                <!-- Dettagli testuali dell'articolo -->
                <div class="col-12 col-md-6">
                    <span class="badge bg-success mb-2 px-3 py-2 fs-6">{{ $article->category->name }}</span>
                    <h2 class="fw-bold text-dark mb-2">{{ $article->title }}</h2>
                    <h4 class="text-success fw-bold mb-3">{{ number_format($article->price, 2, ',', '.') }} €</h4>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted">Descrizione dell'articolo:</h6>
                        <p class="text-dark bg-light p-3 rounded" style="white-space: pre-line;">{{ $article->description }}</p>
                    </div>

                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary px-4 py-2 shadow-sm">Torna a tutti gli annunci</a>
                </div>

            </div>
        </div>
    </div>
</x-layout>
