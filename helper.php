<?php
session_start();

// MARK: Connect
function connect()
{
  $host = "localhost";
  $db = "soporte";
  $user = "root";
  $pass = "";

  $dsn = "mysql:host=$host;dbname=$db";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  // crea y devuelve un PDO conectado a mysql:host=localhost;dbname=curso (usuario 'root', sin contraseña)
  try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    return $pdo;
  } catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
  }
}

// MARK: Login
function loginUser($email, $password)
{
  $pdo = connect();
  $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);


  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['isLogin'] = true;
    $_SESSION['userName'] = $user['nombre'];
    $_SESSION['id'] = $user['id'];

    header('location: incidencias.php');
    exit();
  }
  return false;

}

function registerUser($name, $email, $password)
{
  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  $pdo = connect();
  $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash) VALUES (?, ?, ?)");
  $stmt->execute([$name, $email, $password_hash]);
}

function requireLogin()
{
  if (!isset($_SESSION["isLogin"])) {
    header("location: index.php");
    exit();
  }
}

function redirectIfLoggedIn()
{
  if (isset($_SESSION["isLogin"])) {
    header("location: incidencias.php");
    exit();
  }
}

// MARK: Incidents
function addIncidents($title, $description)
{
  if (!isset($_SESSION["isLogin"])) {
    throw new Exception("Usuario no autenticado");
  }
  $userId = $_SESSION["id"];
  $currentDate = date("Y-m-d H:i:s");
  $state = 'abierta';
  $pdo = connect();
  $stmt = $pdo->prepare("INSERT INTO incidencias (usuario_id, asunto, descripcion, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$userId, $title, $description, $state, $currentDate]);
}

function getIncidents()
{
  $userId = $_SESSION["id"];
  $pdo = connect();
  $stmt = $pdo->prepare("SELECT * FROM incidencias WHERE usuario_id = ? ORDER BY fecha_creacion DESC");
  $stmt->execute([$userId]);
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $result;
}

function changeStateIncident($id, $newState)
{
  $pdo = connect();
  $stmt = $pdo->prepare("UPDATE incidencias SET estado = ? WHERE id = ?");
  $stmt->execute([$newState, $id]);
}

