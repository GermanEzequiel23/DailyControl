<?php
    session_start();
    if (isset($_SESSION['email'])) {
        header("Location: panel.php");
    }

    require_once 'conexion.php';

    $errores = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = limpiarInput($_POST['usuario']);
        $clave = $_POST['clave'];

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? OR email = ?");
        $stmt->execute([$usuario, $usuario]);
        $row = $stmt->fetch();

        if ($row && password_verify($clave, $row['clave'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['clave'] = $row['clave'];
            $_SESSION['bienvenido'] = "Bienvenido, ".$_SESSION['usuario'];
            header("Location: panel.php");
        } else {
            $errores[] = "Usuario y/o Contraseña incorrectos.";
        }
    }

    function limpiarInput($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="icono.png">
</head>
<body style="background-image: url('body.png');">
    <div class="login-container">
        <form method="post" class="login-form"> 
            <div class="titulo"><a href="index.html"><img src="css/DailyControl_logo.png" width="490" height="85"></a>
            </div>
            <p><input type="text" name="usuario" <?php setInputValue('usuario'); ?> placeholder="Usuario o Correo Electrónico" required></p>
            <p><input type="password" name="clave" placeholder="Contraseña" required></p>
            <?php
                foreach ($errores as $error) {
                    echo "<p class='message'>$error</p>";
                }
            ?>
            <p><button type="submit" class="btn_login">Ingresar</button></p>
            <p style="font-size: 1.4em;"><strong>¿No tienes una cuenta?</strong></p>
            <button onclick="ventana()" class="btn_registro">Registrarse</button>
        </form>
    </div>

    <?php
        function setInputValue($name) {
            if (isset($_POST[$name])) {
                echo 'value="' . htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8') . '"';
            }
        }
    ?>
    <script>
        function ventana() {
            window.location.href = "registro.php";
        }
    </script>
</body>
</html>