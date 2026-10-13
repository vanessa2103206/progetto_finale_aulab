<div class="row justify-content-center">
    <div class="col-12 col-md-8 bg-white p-5 rounded shadow">
        <h2 class="text-center mb-4 fw-bold">Inserisci il tuo Annuncio</h2>

        <!-- Messaggio di successo ufficiale -->
        @if (session()->has('successMessage'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('successMessage') }}
            </div>
        @endif

        <form wire:submit.prevent="store">
            <!-- Campo Titolo -->
            <div class="mb-3">
                <label for="title" class="form-label">Titolo Annuncio</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model="title">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Selezione Categoria -->
            <div class="mb-3">
                <label for="category" class="form-label">Categoria</label>
                <select id="category" class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                    <option value="">Scegli una categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Campo Prezzo Sbloccato per numeri interi e decimali -->
            <div class="mb-3">
                <label for="price" class="form-label">Prezzo (€)</label>
                <input type="number" step="any" class="form-control @error('price') is-invalid @enderror" id="price" wire:model="price">
                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Campo Descrizione -->
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione dell'articolo</label>
                <textarea id="description" rows="4" class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">Pubblica Annuncio</button>
        </form>
    </div>
</div>
