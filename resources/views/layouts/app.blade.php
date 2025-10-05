<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
    <main>
        @yield('content')
    </main>
    
    @livewireScripts
</body>
</html>