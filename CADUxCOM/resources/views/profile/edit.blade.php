@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
    {{-- Importar estilos de perfil --}}
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-update-info.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile-delete.css') }}">

    <div class="profile-container">
        
        <h1 class="profile-title">Editar Perfil</h1>

        {{-- Mensajes de éxito --}}
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{-- FORMULARIO DE ACTUALIZACIÓN DE PERFIL --}}
        <div class="profile-card">
            <form method="POST" action="{{ route('profile.update') }}" class="update-profile-form">
                @csrf
                @method('PATCH')

                <div class="profile-grid">
                    {{-- Nombre --}}
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email">Correo</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="form-group">
                        <label for="phone">Teléfono</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}">
                        @error('phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="form-group">
                        <label for="address">Dirección</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address ?? '') }}">
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-save">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- FORMULARIO DE ELIMINACIÓN DE CUENTA --}}
        <div class="profile-card danger-zone">
            <form method="POST" action="{{ route('profile.destroy') }}" class="delete-user-form">
                @csrf
                @method('DELETE')
                <p>Esta acción eliminará tu cuenta y no se puede deshacer.</p>
                <button type="submit" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')" class="btn-delete">
                    Eliminar Cuenta
                </button>
            </form>
        </div>
    </div>
@endsection
