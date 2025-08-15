<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jakshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<div style='color:#ff4d4d;text-align:center;'>Error de conexión: " . $conn->connect_error . "</div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($contraseña, $usuario['contraseña'])) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['tipo'] = $usuario['tipo'];
            if ($usuario['tipo'] === 'vendedor') {
                header("Location: Vendedor Productos.html");
                exit();
            } else {
                header("Location: Productos.html");
                exit();
            }
        } else {
            mostrarError();
        }
    } else {
        mostrarError();
    }
    $stmt->close();
}
$conn->close();

function mostrarError() {
    echo "<html><head><title>Error de inicio de sesión</title><style>body{font-family:Arial,sans-serif;background:#0f2027;color:#fff;text-align:center;padding-top:50px;}h2{color:#ff4d4d;}a{color:#FFD700;text-decoration:none;font-weight:600;}</style></head><body>";
    echo "<h2>❌ Usuario o contraseña incorrectos</h2>";
    echo "<a href='login.html'>Volver a intentar</a>";
    echo "</body></html>";
    exit();
}
?>
