<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: panel.php");
}
require_once 'conexion.php';

$errores = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = limpiarInput($_POST['usuario']);
    $email = limpiarInput($_POST['email']);
    $clave = $_POST['clave'];
    $confirmar_clave = $_POST['confirmar_clave'];

    $stmtUsuario = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmtUsuario->execute([$usuario]);
    $rowUsuario = $stmtUsuario->fetch();

    $stmtEmail = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmtEmail->execute([$email]);
    $rowEmail = $stmtEmail->fetch();

    if ($rowUsuario) {
        $errores[] = "El usuario ya está registrado.";
    }
    if ($rowEmail) {
        $errores[] = "El correo electrónico ya está en uso.";
    }
    if ($clave !== $confirmar_clave) {
        $errores[] = "Las contraseñas no coinciden.";
    }
    if (empty($errores)) {
        $hashedClave = password_hash($clave, PASSWORD_DEFAULT);
        $stmtInsert = $conn->prepare("INSERT INTO usuarios (usuario, email, clave) VALUES (?, ?, ?)");
        $stmtInsert->execute([$usuario, $email, $hashedClave]);
        header("Location: login.php");
    }
}

function limpiarInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="icon" href="icono.png">
    <link rel="stylesheet" href="css/registro.css">
    <link rel="stylesheet" href="css/general.css">
</head>
<body style="background-image: url('body.png');">
    <center>
    <div class="registro-container">
        <form method="post" class="registro-form">
            <h1>Rellene sus datos</h1>
            <p><input type="text" name="usuario" placeholder="Nombre de Usuario" required><br>
            <p><input type="email" name="email" placeholder="Email" required><br>
            <p><input type="password" name="clave" placeholder="Contraseña" required><br>
            <p><input type="password" name="confirmar_clave" placeholder="Confirmar Contraseña" required><br>
            <?php
                foreach ($errores as $error) {
                    echo "<p style='color: red;'>$error</p>";
                }
            ?>
            <button type="submit" class="btn_registro">Registrarse</button>
            <p style="font-size: 1.4em;"><strong>¿Ya tienes una cuenta? <a href="login.php">Iniciar Sesion</a></strong>
        </form>
    </div>
    </center>
</body>
</html>