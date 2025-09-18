<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Mensaje solicitud de Contacto</title>
</head>
<body>
    <h1>Hay una persona que quiere contactarse mediante la plataforma de Mentorial</h1>
    <p><strong>De:</strong> {{ $nombre }}</p>
    <p><strong>Correo Electrónico:</strong> {{ $correo }}</p>
    <hr>
    <p><strong>Mensaje:</strong></p>
    <p>{{ $mensaje }}</p>
</body>
</html>