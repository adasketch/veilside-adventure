@extends('layouts.main')
@section('title', 'Register')
@section('content')
<div style="display:flex; justify-content:center; align-items:center; height:80vh; background:#e8f5f5;">
    <div style="background:white; padding:30px; width:350px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align:center; color:#0c6452;">Registrasi</h2>
        <input type="email" id="regEmail" placeholder="Email" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #aaa; border-radius:5px;">
        <input type="text" id="regUser" placeholder="Username" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #aaa; border-radius:5px;">
        <input type="password" id="regPass" placeholder="Password" required style="width:100%; padding:10px; margin:10px 0; border:1px solid #aaa; border-radius:5px;">
        <button onclick="register()" style="width:100%; padding:10px; background:#0c6452; color:white; border:none; border-radius:5px; cursor:pointer;">Daftar</button>
    </div>
</div>
<script>
    function register(){
        const email = document.getElementById('regEmail').value;
        const user = document.getElementById('regUser').value;
        const pass = document.getElementById('regPass').value;
        let acc = JSON.parse(localStorage.getItem('accounts')||'[]');
        if(acc.find(a=>a.email===email || a.username===user)){
            alert('Sudah terdaftar!'); return;
        }
        acc.push({email, username:user, password:pass, role:'user'});
        localStorage.setItem('accounts', JSON.stringify(acc));
        alert('Berhasil!');
        window.location.href="{{ route('login') }}";
    }
</script>
@endsection
