<?php
require("helper.php");

redirectIfLoggedIn();

$error = "";
$isRegistered = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST["nombre"];
  $email = $_POST["email"];
  $password = $_POST["clave"];

  try {
    registerUser($name, $email, $password);
    $isRegistered = true;
  } catch (PDOException $e) {
    //throw $th;
    $error = $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soporte Informático — Registro</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

  <header>
    <h1>Soporte Informático</h1>
  </header>

  <main class="card">

    <!-- Cuando el registro se cree correctamente, aquí debe aparecer, en vez del formulario:
    <p class="success">Cuenta creada correctamente. Ya puedes <a href="index.html">iniciar sesión</a>.</p> -->
    <?php if ($isRegistered): ?>
      <p class="success">Cuenta creada correctamente. Ya puedes <a href="index.php">iniciar sesión</a></p>
    <?php else: ?>
      <h2>Crear cuenta</h2>

      <!-- Si hay un error (por ejemplo, email ya registrado), aquí debe aparecer:
      <p class="error">Ese email ya está registrado.</p> -->
      <?php if ($error): ?>
        <p class="error">
          <?= htmlspecialchars($error) ?>
        </p>
      <?php endif; ?>

      <form method="post" action="registro.php">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="clave">Contraseña</label>
        <input type="password" id="clave" name="clave" required>

        <button type="submit">Crear cuenta</button>
      </form>

      <p class="link">¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a></p>
    <?php endif; ?>

  </main>

</body>

</html>