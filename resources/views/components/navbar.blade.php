<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-success" href="{{ route('homepage') }}">Presto.it</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <!-- BARRA DI RICERCA GLOBALE LIVEWIRE RICHIESTA DAL PROFESSORE -->
            <div class="mx-auto my-2 my-lg-0" style="width: 40%; max-width: 500px;">
                @livewire('search-articles')
            </div>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('homepage') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('articles.index') }}">{{ __('Tutti gli Annunci') }}</a>
                </li>
                
                @auth
                    @if(!Auth::user()->is_revisore)
                        <li class="nav-item">
                            <a class="nav-link text-white-50 px-3" href="{{ route('revisore.become_form') }}">{{ __('Lavora con noi') }}</a>
                        </li>
                    @endif

                    @if(Auth::user()->is_revisore)
                        <li class="nav-item">
                            <a class="nav-link text-info fw-bold px-3" href="{{ route('revisore.index') }}">Zona Revisore</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold px-3" href="{{ route('articles.create') }}">{{ __('Nuovo Annuncio') }}</a>
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

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Accedi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Registrati</a>
                    </li>
                @endguest

                <!-- SELETTORE DELLE LINGUE -->
                <li class="nav-item dropdown ms-3 border-start ps-3">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        🌐 {{ strtoupper(App::getLocale()) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="langDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between gap-3" href="{{ route('set_locale', ['locale' => 'it']) }}">
                                <span>Italiano</span> <span class="badge bg-danger text-white fw-bold shadow-sm">IT</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between gap-3" href="{{ route('set_locale', ['locale' => 'en']) }}">
                                <span>English</span> <span class="badge bg-primary text-white fw-bold shadow-sm">EN</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-between gap-3" href="{{ route('set_locale', ['locale' => 'es']) }}">
                                <span>Español</span> <span class="badge bg-warning text-dark fw-bold shadow-sm">ES</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
