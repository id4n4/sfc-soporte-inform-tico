<?php
require("helper.php");

requireLogin();

$userName = $_SESSION["userName"];
$error = "";
$incidents = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['cambiar_estado'])) {
    $incidentId = (int) ($_POST['incidencia_id'] ?? 0);
    $newState = $_POST['estado'] ?? '';
    $validStates = ['abierta', 'en_proceso', 'cerrada'];

    if ($incidentId > 0 && in_array($newState, $validStates, true)) {
      changeStateIncident($incidentId, $newState);
    }

    header("Location: incidencias.php");
    exit();
  }

  $title = $_POST["asunto"] ?? '';
  $description = $_POST['descripcion'] ?? '';

  try {
    addIncidents($title, $description);
    header("Location: incidencias.php");
    exit();
  } catch (PDOException $e) {
    $error = $e->getMessage();
  }

}

if (!$error) {
  $incidents = getIncidents();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soporte Informático — Mis incidencias</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>

  <header class="header-app">
    <span class="titulo">Soporte Informático</span>
    <span class="usuario" style="text-transform: capitalize;">
      <!-- El nombre "Ana Ejemplo" es solo de muestra: en la versión final debe salir
      el nombre del usuario que ha iniciado sesión -->
      Hola, <?= $userName ?>
      · <a href="logout.php">Cerrar sesión</a>
    </span>
  </header>

  <main>

    <!-- Si hay un error al crear la incidencia (campos vacíos, etc.), aquí debe aparecer:
    <p class="error">El asunto y la descripción son obligatorios.</p> -->
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <section class="card">
      <h2>Nueva incidencia</h2>
      <form method="post" action="incidencias.php">
        <label for="asunto">Asunto</label>
        <input type="text" id="asunto" name="asunto" required>

        <label for="descripcion">Describe el problema</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <button type="submit">Enviar incidencia</button>
      </form>
    </section>

    <section class="card">
      <h2>Mis incidencias</h2>

      <!-- Esta tabla es un EJEMPLO de cómo debe verse con datos. En la versión final,
      las filas deben generarse dinámicamente con PHP a partir de lo que haya en la
      base de datos para el usuario que ha iniciado sesión. Si no tiene ninguna,
      se muestra en su lugar: <p class="vacio">Todavía no has enviado ninguna incidencia.</p> -->


      <table>
        <thead>
          <tr>
            <th>Asunto</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($incidents) === 0): ?>
            <tr>
              <td colspan="4">
                <p class="vacio" style="text-align: center;">Todavía no has enviado ninguna incidencia.</p>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($incidents as $incident): ?>
              <tr>
                <td><?= htmlspecialchars($incident['asunto']) ?></td>
                <td><?= htmlspecialchars($incident['descripcion']) ?></td>
                <td>
                  <!-- abierta, en_proceso, cerrada -->
                  <?php if ($incident['estado'] === 'abierta'): ?>
                    <span class="estado estado-abierta">
                      <?= htmlspecialchars($incident['estado']) ?>
                    </span>
                  <?php elseif ($incident['estado'] === 'en_proceso'): ?>
                    <span class="estado estado-en_proceso">
                      <?= htmlspecialchars($incident['estado']) ?>
                    </span>
                  <?php else: ?>
                    <span class="estado estado-cerrada">
                      <?= htmlspecialchars($incident['estado']) ?>
                    </span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($incident['fecha_creacion']) ?></td>
                <td>
                  <div class="table-action">
                    <!-- abierta, en_proceso, cerrada -->
                    <?php if ($incident['estado'] === 'abierta'): ?>
                      <form method="post" action="incidencias.php">
                        <input type="hidden" name="incidencia_id" value="<?= (int) $incident['id'] ?>">
                        <input type="hidden" name="estado" value="en_proceso">
                        <button type="submit" name="cambiar_estado">Iniciar</button>
                      </form>
                      <form method="post" action="incidencias.php">
                        <input type="hidden" name="incidencia_id" value="<?= (int) $incident['id'] ?>">
                        <input type="hidden" name="estado" value="cerrada">
                        <button type="submit" name="cambiar_estado" class="close-button">✔️</button>
                      </form>
                    <?php elseif ($incident['estado'] === 'en_proceso'): ?>
                      <form method="post" action="incidencias.php">
                        <input type="hidden" name="incidencia_id" value="<?= (int) $incident['id'] ?>">
                        <input type="hidden" name="estado" value="cerrada">
                        <button type="submit" name="cambiar_estado" class="close-button">✔️</button>
                      </form>
                    <?php else: ?>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>

    </section>

  </main>

</body>

</html>