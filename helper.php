<?php
session_start();

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

