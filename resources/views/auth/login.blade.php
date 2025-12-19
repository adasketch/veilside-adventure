@extends('layouts.main')

@section('title', 'Login | Veilside Adventure')

@section('content')
<div style="display:flex; justify-content:center; align-items:center; height:80vh; background:#e8f5f5;">
    <div style="background:white; padding:30px; width:350px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.15); text-align:center;">
        <h2 style="color:#0c6452; margin-bottom:20px;">Login</h2>

        @if ($errors->any())
            <div style="color: red; margin-bottom: 10px; font-size: 0.9em;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf <input type="text" name="username" placeholder="Username" required
                   value="{{ old('username') }}"
                   style="width:100%; padding:10px; margin:10px 0; border:1px solid #aaa; border-radius:5px;">

            <input type="password" name="password" placeholder="Password" required
                   style="width:100%; padding:10px; margin:10px 0; border:1px solid #aaa; border-radius:5px;">

            <button type="submit" style="width:100%; padding:10px; background:#0c6452; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
