<div class="card shadow border-0 p-5 bg-white rounded">
    <h3 class="text-center fw-bold text-success mb-4">{{ __('Inserisci un nuovo annuncio') }}</h3>

    <!-- Messaggio di successo -->
    @if (session()->has('successMessage'))
        <div class="alert alert-success text-center shadow-sm fw-bold mb-4">
            {{ session('successMessage') }}
        </div>
    @endif

    <form wire:submit.prevent="store">
        <!-- Titolo dell'annuncio -->
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">Titolo Annuncio</label>
            <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Es. Cappotto di lana vintage">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Categoria -->
        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold">Categoria</label>
            <select wire:model="category_id" class="form-select @error('category_id') is-invalid @enderror" id="category_id">
                <option value="">Seleziona una categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Prezzo -->
        <div class="mb-3">
            <label for="price" class="form-label fw-bold">Prezzo (€)</label>
            <input type="text" wire:model="price" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Es. 45.50">
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- Descrizione -->
        <div class="mb-4">
            <label for="description" class="form-label fw-bold">Descrizione Dettagliata</label>
            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="5" placeholder="Descrivi il tuo articolo indicando condizioni, taglia, colore..."></textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <!-- ========================================== -->
        <!-- NUOVA SEZIONE IMMAGINI (USER STORY 5)       -->
        <!-- ========================================== -->
        <div class="mb-4 border p-3 rounded bg-light">
            <label class="form-label fw-bold mb-2">Immagini dell'annuncio</label>
            
            <!-- Input dei File multipli (Caricamento in blocco!) -->
            <input type="file" wire:model="temporary_images" class="form-control @error('temporary_images.*') is-invalid @enderror" multiple>
            @error('temporary_images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            <!-- Griglia di anteprima in tempo reale -->
            @if (!empty($images))
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="small text-muted fw-bold mb-2">Anteprima immagini caricate:</p>
                    </div>
                    @foreach ($images as $key => $image)
                        <div class="col-4 col-md-3 mb-3 position-relative">
                            <!-- Immagine temporanea fluttuante -->
                            <div class="img-thumbnail shadow-sm bg-white p-1 rounded overflow-hidden" style="height: 100px;">
                                <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                            <!-- Pulsante per eliminare la singola immagine (Criterio Richiesto!) -->
                            <button type="button" wire:click="removeImage({{ $key }})" class="btn btn-danger btn-sm position-absolute rounded-circle shadow-sm" style="top: -5px; right: 5px; width: 25px; height: 25px; padding: 0; line-height: 20px; font-weight: bold;">
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <!-- ========================================== -->

        <!-- Pulsante di Invio -->
        <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
            🚀 Crea e Invia ad Approvazione
        </button>
    </form>
</div>
