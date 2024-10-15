@extends('layouts.auth')

@section('title')
Welcome back Login
@endsection
@section('content')
<div class="auth-form">
    @include('partials.logo')
    <h4 class="text-center mb-4">Sign in to your account</h4>
    @include('partials.flash-message')
    <form action="{{ route('postLogin') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="mb-1 form-label">Email</label>
            <input 
                type="text" 
                name="email" 
                class="form-control" 
                placeholder="email"
                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                autocomplete="email" 
                autofocus  
                value="{{ old('email', null) }}"
            >
            @if($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>
        
        <div class="mb-3 position-relative">
            <label class="form-label" for="dz-password">Password</label>
            <input 
                type="password" 
                name="password" 
                id="dz-password" 
                class="form-control" 
                value="123456"
                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
            >
            <span class="show-pass eye">
                <i class="fa fa-eye-slash"></i>
                <i class="fa fa-eye"></i>
            </span>
            @if($errors->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
    </form>
</div>

@endsection