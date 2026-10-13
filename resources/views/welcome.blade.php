<x-layout>
    <div class="container-fluid text-center bg-body-tertiary">
        <div class="row vh-100 justify-content-center align-items-center">
            <div class="col-12">
                <h1 class="display-4 fw-bold">Presto.it</h1>
                <p class="lead mb-4">Il portale degli annunci d'affari</p>
                
                <!-- Il bottone ufficiale richiesto dai criteri di accettazione -->
                <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg px-4 gap-3 fw-bold shadow-sm">
                    Inserisci Annuncio
                </a>
            </div>
        </div>
    </div>
</x-layout>
