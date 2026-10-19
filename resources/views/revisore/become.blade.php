<x-layout>
    <div class="container-fluid text-center bg-success text-white py-5 shadow-sm rounded mb-5">
        <h1 class="display-4 fw-bold">Lavora con noi</h1>
        <p class="lead">Invia la tua candidatura per entrare nel team dei Revisori di Presto.it</p>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card shadow border-0 p-5 bg-white rounded">
                    <h3 class="text-center fw-bold mb-4">Invia la tua richiesta</h3>
                    
                    <p class="text-muted text-center mb-4">
                        Cliccando sul pulsante in basso, invierai una richiesta formale con i dati del tuo profilo attuale (<strong>{{ Auth::user()->name }}</strong> - {{ Auth::user()->email }}) all'amministratore del sito.
                    </p>

                    <form action="{{ route('revisore.become_submit') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                            📩 Invia Candidatura Ufficiale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
