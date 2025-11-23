<?php

$host = "localhost";
$puerto = 3307;
$db = "formulario";
$user = "root";
$pass = "";

$conexion =new mysqli($host, $user, $pass, $db, $puerto);

if ($conexion->connect_error){
    die("Conexión fallida: ". $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $nombre = htmlspecialchars(trim($_POST["nombre"]));
    $correo = htmlspecialchars(trim($_POST["correo"]));
    $mensaje = htmlspecialchars(trim($_POST["mensaje"]));

    $errores = [];

    if(empty($nombre)){
        $errores['nombre'] = "Por favor ingresa tu nombre.";
    }
    
    if(empty($correo)){
        $errores['correo'] = "Por favor ingresa tu correo.";
    }elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El correo no es valido.";
    }
    
    if(empty($mensaje)){
        $errores['mensaje'] = "Por favor deja tu mensaje.";
    }

    if(empty($errores)){
        $stmt = $conexion->prepare("INSERT INTO contactos(nombre,correo, mensaje) VALUES (?, ?, ?)"); 
        $stmt->bind_param("sss", $nombre, $correo, $mensaje);
        $stmt->execute();
        $stmt->close();

        header("Location: index.html?enviado=1");
        exit;
    }else{
        header("Location: contacto.html?error=1");
    exit;     
    }
    
} else {

    header("Location: contacto.html");
    exit;
}   

$conexion->close();
?>
