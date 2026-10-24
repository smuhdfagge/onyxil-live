<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · Onyx admin</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="login-body">
    <main class="login">
        <img src="{{ asset('images/logo-dark.png') }}" alt="Onyx Innovations Limited" width="150" class="login-logo">
        <form method="post" action="{{ route('login.attempt') }}" class="login-card">
            @csrf
            <h1>Sign in to the admin</h1>
            @if (session('status'))<p class="ok">{{ session('status') }}</p>@endif
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('email')<p class="err">{{ $message }}</p>@enderror
            <label class="check"><input type="checkbox" name="remember" value="1"> Keep me signed in on this device</label>
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
        </form>
        <a href="{{ route('home') }}" class="login-back">← Back to the website</a>
    </main>
</body>
</html>
