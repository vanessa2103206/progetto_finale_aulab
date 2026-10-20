<div class="position-relative w-100">
    <!-- Input di ricerca della barra -->
    <div class="input-group">
        <span class="input-group-text bg-success text-white border-0" id="basic-addon1">🔍</span>
        <input type="search" 
               wire:model.live="search" 
               class="form-control border-0 bg-light py-2 shadow-none" 
               placeholder="Cosa stai cercando?" 
               aria-label="Search">
    </div>

    <!-- Menu a tendina fluttuante dei risultati (Compare solo se l'utente digita qualcosa!) -->
    @if($search !== '')
        <div class="position-absolute bg-white rounded shadow-lg mt-2 w-100 overflow-auto z-3" style="max-height: 400px; border: 1px solid #dee2e6; left: 0;">
            <div class="list-group list-group-flush">
                @forelse($articles as $article)
                    <!-- Singolo risultato cliccabile -->
                    <a href="{{ route('articles.show', compact('article')) }}" class="list-group-item list-group-item-action d-flex align-items-center p-3 text-decoration-none">
                        
                        <!-- Mini anteprima immagine rimpicciolita -->
                        <div class="me-3" style="width: 50px; height: 50px;">
                            @if(Str::contains(strtolower($article->title), 'maglia'))
                                <img src="{{ asset('images/maglia.png') }}" class="img-fluid rounded" alt="Maglia" style="width: 100%; height: 100%; object-fit: contain;">
                            @elseif(Str::contains(strtolower($article->title), 'portatile') || Str::contains(strtolower($article->title), 'pc'))
                                <img src="{{ asset('images/portatile.png') }}" class="img-fluid rounded" alt="Computer" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <div class="bg-secondary rounded text-white text-center" style="line-height: 50px; font-size: 10px;">No Img</div>
                            @endif
                        </div>

                        <!-- Testi e prezzo del prodotto trovato -->
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold text-dark">{{ $article->title }}</h6>
                            <small class="text-muted">{{ $article->category->name }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success fs-6">{{ number_format($article->price, 2, ',', '.') }} €</span>
                        </div>
                    </a>
                @empty
                    <!-- Nessun risultato trovato -->
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0">❌ Nessun articolo corrisponde alla tua ricerca.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
