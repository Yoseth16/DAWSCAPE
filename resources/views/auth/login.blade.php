@extends('layouts.app')

@section('title', 'Login Administrativo - DAWSCAPE')

@push('styles')
<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
    }
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        font-family: 'Inter', sans-serif;
    }

    .login-container {
        width: 100%;
        max-width: 420px;
        padding: 20px;
    }

    .login-card {
        background: rgba(30, 41, 59, 0.7);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .login-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .login-header h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.8rem;
        font-weight: 900;
        letter-spacing: 2px;
        background: linear-gradient(45deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
    }

    .login-header p {
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #cbd5e1;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        background: rgba(15, 23, 42, 0.8);
    }

    .btn-login {
        width: 100%;
        background: linear-gradient(45deg, #3b82f6, #8b5cf6);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 10px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.3);
        filter: brightness(1.1);
    }

    .error-list {
        background: rgba(239, 68, 68, 0.1);
        border-left: 4px solid #ef4444;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .error-list ul {
        margin: 0;
        padding-left: 20px;
        color: #fca5a5;
        font-size: 0.85rem;
    }

    .footer-links {
        text-align: center;
        margin-top: 25px;
    }

    .footer-links a {
        color: #64748b;
        text-decoration: none;
        font-size: 0.8rem;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h1>DAWSCAPE</h1>
            <p>Panel de Administración</p>
        </div>

        @if ($errors->any())
            <div class="error-list">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email del Profesor</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="1daw@dawscape.com">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 25px;">
                <input type="checkbox" id="remember_me" name="remember" style="margin-right: 10px;">
                <label for="remember_me" style="margin: 0; color: #94a3b8; font-size: 0.8rem; text-transform: none; font-weight: 400;">Mantener sesión iniciada</label>
            </div>

            <button type="submit" class="btn-login">
                Iniciar Sesión
            </button>
        </form>

        <div class="footer-links">
            <a href="{{ route('inicio') }}">← Volver al inicio del juego</a>
        </div>
    </div>
</div>
@endsection

