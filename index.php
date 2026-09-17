<?php
require("helper.php");

redirectIfLoggedIn();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["clave"];

  try {
    $isLogin = loginUser($email, $password);
    if (!$isLogin) {
      $error = "Email o contraseña incorrectos";
    }
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
  <title>Soporte Informático — Iniciar sesión</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

  <header>
    <h1>Soporte Informático</h1>
  </header>

  <main class="card">
    <h2>Iniciar sesión</h2>

    <!-- Cuando el login falle, aquí debe aparecer un mensaje de error, por ejemplo:
    <p class="error">Email o contraseña incorrectos.</p> -->

    <?php if ($error): ?>
      <p class="error">
        <?= htmlspecialchars($error); ?>
      </p>
    <?php endif; ?>

    <form method="post" action="index.php">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="clave">Contraseña</label>
      <input type="password" id="clave" name="clave" required>

      <button type="submit">Entrar</button>
    </form>

    <p class="link">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
  </main>

</body>

</html>