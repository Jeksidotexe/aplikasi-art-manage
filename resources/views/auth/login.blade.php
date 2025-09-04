@extends('layouts.auth')

@section('login')
<form action="{{ route('login') }}" method="POST">
    @csrf
    <h1>Silahkan Login</h1>
    <div class="social-container">
        <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
        <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <span>or use your account</span>
    <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Masukkan alamat email"
        value="{{ old('email') }}" />
    <input type="password" name="password" id="password" class="form-control form-control-lg"
        placeholder="Masukkan password" />
    <button type="submit">Login</button>
</form>
@endsection