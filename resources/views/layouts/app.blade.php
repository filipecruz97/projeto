<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Controle Financeiro')</title>
</head>
<body>
<header>
<h1>Controle Financeiro</h1>
<nav>
    <a href="{{ route('home') }}">Início</a>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('sobre') }}">Sobre</a>
</nav>
</header>
<main>
@yield('content')
</main>
</body>
</html>