<x-layout>
    <div class="container-fluid text-center bg-body-tertiary py-5 rounded shadow-sm mb-5">
        <h1 class="display-4 fw-bold">Tutti gli Annunci</h1>
        <p class="lead">Esplora tutti gli articoli pubblicati sul nostro portale</p>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            @forelse($articles as $article)
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 overflow-hidden">
                        
                        <!-- SEZIONE IMMAGINE RITAGLIATA CON SPATIE (USER STORY 6) -->
                        <div class="text-center bg-light" style="height: 200px;">
                            @if($article->images->count())
                                @php
                                    $image = $article->images->first();
                                    $cropPath = dirname($image->path) . '/crop_300x300_' . basename($image->path);
                                @endphp
                                @if(file_exists(storage_path('app/public/' . $cropPath)))
                                    <img src="{{ Storage::url($cropPath) }}" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="{{ $article->title }}">
                                @else
                                    <img src="{{ Storage::url($image->path) }}" class="card-img-top h-100 w-100" style="object-fit: cover;" alt="{{ $article->title }}">
                                @endif
                            @else
                                <div class="bg-secondary text-white text-center py-5 fw-bold h-100" style="line-height: 150px;">Nessuna Immagine</div>
                            @endif
                        </div>
                        
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
                <div class="col-12 text-center py-5">
                    <p class="text-muted lead">Non ci sono annunci pubblicati o approvati.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>
