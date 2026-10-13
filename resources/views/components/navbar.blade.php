<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-success" href="{{ route('homepage') }}">Presto.it</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-collapse navbar-nav ms-auto mb-2 mb-lg-0 justify-content-end w-100">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">Home</a>
                </li>
                
                <!-- Mostra questi tasti solo se l'utente è LOGGATO -->
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="{{ route('articles.create') }}">Nuovo Annuncio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ciao, {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <form action="/logout" method="POST" id="logout-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Esci (Logout)</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                <!-- Mostra questi tasti solo se l'utente NON è loggato (Ospite) -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Accedi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Registrati</a>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
