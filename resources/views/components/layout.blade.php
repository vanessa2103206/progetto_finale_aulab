<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presto.it</title>
    
    <!-- Vite per caricare Bootstrap e file JS/CSS locali -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <livewire:styles />
</head>
<body class="bg-light">

    <!-- Inclusione automatica della Navbar in cima al sito -->
    <x-navbar />

    <!-- Contenitore principale delle pagine del sito -->
    <div class="container my-5">
        {{ $slot }}
    </div>

    <livewire:scripts />
</body>
</html>
