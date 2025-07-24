<!DOCTYPE html>
<html>
<head>
    <title>E-mail de Bun Venit</title>
</head>

<body>
<h2>Bine ai venit pe site {{$user['name']}}</h2>
<br/>
Adresa ta de e-mail înregistrată este {{$user['email']}} , Te rugăm să faci clic pe linkul de mai jos pentru a-ți verifica contul de e-mail
<br/>
<a href="{{ url('user/verify/', $user['verification_code']) }}">Verifică E-mail</a>
</body>

</html>