<x-layout>
    <div class="container-fluid text-center bg-dark text-white py-5 shadow-sm rounded mb-5">
        <h1 class="display-4 fw-bold">Plancia del Revisore</h1>
        <p class="lead">Approvazione e controllo degli annunci inseriti dagli utenti</p>
    </div>

    <div class="container">
        <!-- Messaggio di notifica dopo aver cliccato un bottone -->
        @if (session()->has('successMessage'))
            <div class="alert alert-success text-center shadow-sm" role="alert">
                {{ session('successMessage') }}
            </div>
        @endif

        @if($article_to_check)
            <!-- C'è un annuncio da controllare -->
            <div class="card shadow border-0 p-5 bg-white mb-5 rounded">
                <div class="row align-items-center">
                    <!-- Immagine dell'annuncio -->
                    <div class="col-12 col-md-5 text-center mb-4 mb-md-0">
                        @if(Str::contains(strtolower($article_to_check->title), 'maglia'))
                            <img src="{{ asset('images/maglia.png') }}" class="img-fluid rounded shadow-sm w-70 mx-auto" alt="Maglia" style="max-height: 300px; object-fit: contain;">
                        @elseif(Str::contains(strtolower($article_to_check->title), 'portatile') || Str::contains(strtolower($article_to_check->title), 'pc'))
                            <img src="{{ asset('images/portatile.png') }}" class="img-fluid rounded shadow-sm w-70 mx-auto" alt="Computer" style="max-height: 300px; object-fit: contain;">
                        @else
                            <div class="bg-secondary text-white text-center py-5 rounded fw-bold" style="height: 250px; line-height: 150px;">Nessuna Immagine</div>
                        @endif
                    </div>

                    <!-- Dettagli e Testi dell'annuncio -->
                    <div class="col-12 col-md-7">
                        <span class="badge bg-success mb-2 px-3 py-2 fs-6">{{ $article_to_check->category->name }}</span>
                        <h2 class="fw-bold text-dark mb-2">{{ $article_to_check->title }}</h2>
                        <h4 class="text-success fw-bold mb-3">{{ number_format($article_to_check->price, 2, ',', '.') }} €</h4>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted">Descrizione:</h6>
                            <p class="text-dark bg-light p-3 rounded" style="white-space: pre-line;">{{ $article_to_check->description }}</p>
                        </div>

                        <!-- I DUE BOTTONI UFFICIALI CON LA SINTASSI CORRETTA -->
                        <div class="d-flex gap-3 mt-4">
                            <!-- Form per Rifiutare l'annuncio -->
                            <form action="{{ route('revisore.reject_article', ['article' => $article_to_check->id]) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold shadow-sm">❌ Rifiuta Annuncio</button>
                            </form>

                            <!-- Form per Accettare l'annuncio -->
                            <form action="{{ route('revisore.accept_article', ['article' => $article_to_check->id]) }}" method="POST" class="flex-grow-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">✅ Accetta Annuncio</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Non ci sono annunci da controllare -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-center py-5">
                    <div class="alert alert-info shadow border-0 p-5 rounded" role="alert">
                        <h4 class="alert-heading fw-bold mb-2">Grande lavoro!</h4>
                        <p class="mb-0 lead text-muted">Non ci sono nuovi annunci da revisionare in questo momento.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
