<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jakshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    $imagen_nombre = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $imagen_destino = "imagenes/" . basename($imagen_nombre);

    if (!is_dir("imagenes")) {
        mkdir("imagenes", 0777, true);
    }

    echo "<html><head><title>Resultado de registro</title><style>body{font-family:Poppins,sans-serif;background:#f9f9f9;color:#333;text-align:center;padding-top:50px;}h2{color:#28a745;}h3{color:#ff4d4d;}a{color:#007bff;text-decoration:none;font-weight:600;}</style></head><body>";

    if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen_destino);
        if ($stmt->execute()) {
            echo "<h2>✅ Producto guardado exitosamente.</h2>";
            echo "<a href='AgregarProductos.html'>Agregar otro producto</a> | <a href='Productos.html'>Ver productos</a>";
        } else {
            echo "<h3>❌ Error al guardar el producto: " . htmlspecialchars($stmt->error) . "</h3>";
            echo "<a href='AgregarProductos.html'>Intentar de nuevo</a>";
        }
        $stmt->close();
    } else {
        echo "<h3>❌ Error al subir la imagen.</h3>";
        echo "<a href='AgregarProductos.html'>Intentar de nuevo</a>";
    }
    echo "</body></html>";
}
$conn->close();
?>
