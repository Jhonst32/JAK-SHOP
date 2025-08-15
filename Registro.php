<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "jakshop";

// Crear conexión

echo "<pre>";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
echo "Conexión exitosa a la base de datos.\n";

// Mostrar datos recibidos
echo "Datos recibidos:\n";
print_r($_POST);
echo "</pre>";

// Recibir datos del formulario

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$contraseña = isset($_POST['contraseña']) ? password_hash($_POST['contraseña'], PASSWORD_DEFAULT) : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

// Insertar
$sql = "INSERT INTO usuarios (nombre, email, contraseña, tipo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nombre, $email, $contraseña, $tipo);

if ($stmt->execute()) {
    echo "<html><head><title>Registro exitoso</title><style>body{font-family:Arial,sans-serif;background:#0f2027;color:#fff;text-align:center;padding-top:50px;}h2{color:#FFD700;}a,button{background:#FFD700;color:#333;padding:10px 20px;border:none;border-radius:8px;font-weight:bold;cursor:pointer;text-decoration:none;margin-top:20px;}</style></head><body>";
    echo "<h2>✅ Usuario registrado exitosamente</h2>";
    echo "<form action='login.html' method='get'><button type='submit'>Ir al login</button></form>";
    echo "</body></html>";
    exit();
} else {
    echo "<html><head><title>Error de registro</title><style>body{font-family:Arial,sans-serif;background:#f9f9f9;color:#333;text-align:center;padding-top:50px;}h2{color:#ff4d4d;}a{color:#007bff;text-decoration:none;font-weight:600;}</style></head><body>";
    echo "<h2>❌ Error al registrar usuario: " . htmlspecialchars($stmt->error) . "</h2>";
    echo "<a href='Registro.html'>Volver al registro</a>";
    echo "</body></html>";
}

$stmt->close();
$conn->close();
?>