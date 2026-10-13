<x-layout>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 bg-white p-5 rounded shadow">
                <h2 class="text-center mb-4 fw-bold">Accedi a Presto.it</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @uniq
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/login" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Indirizzo Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Entra</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
