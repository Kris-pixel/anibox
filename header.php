<?php
    if(session_status()!=PHP_SESSION_ACTIVE) session_start();
?>

<header id="menu" class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-2">
  <a href="/" class="d-flex align-items-center  mb-2 mb-md-0 text-dark text-decoration-none">
  <img src="../imgs/pokeball.png" alt="Anibox logo" height="40" width="40"><h2>Anibox</h2>
  </a>

  <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
  <li><a href="index.php?action=all" id="linkAll" class="nav-link px-2 link-dark">Каталог</a></li>
  <li><a href="index.php?action=top10" id="link10" class="nav-link px-2 link-dark">ТОП-10</a></li>
  <li><a href="index.php?action=ong" id="linkOng" class="nav-link px-2 link-dark">Онгоинги</a></li>
  <li><a href="index.php?action=anons" id="linkAnons" class="nav-link px-2 link-dark">Анонсы</a></li>
  </ul>

  <?php
  switch ($_GET["action"]) {
  case 'all':
  echo '<style>
  #linkAll{ color:#ff5959;}
  </style>';
  break;
  case 'top10':
  echo '<style>
  #link10{ color:#ff5959;}
  </style>';
  break;
  case 'ong':
  echo '<style>
  #linkOng{ color:#ff5959;}
  </style>';
  break;
  case 'anons':
  echo '<style>
  #linkAnons{ color:#ff5959;}
  </style>';
  break;

  default:
  # code...
  break;
  } 
  ?>

  <div class=" text-end ">

  <?php 
  if(session_status()!=PHP_SESSION_ACTIVE) session_start();
  require_once('connect/db.php');
  require_once 'functions/func.php';



  if (!empty($_SESSION['login'])) {

  $login = $_SESSION['login'];

  $row = getInfoDB(
  "SELECT img FROM users  WHERE login ='$login'",
  $link);
  $img = $row['img'];
  if($img == NULL){ $img ='default-user.png';}

  echo "<a href='user_room.php' class='d-flex flex-row align-items-center' >
  <img class='rounded' src='imgs/users/$img' width='40' height='40'>
  <h4 class='pl-2'>$login</h4>
  </a>";
  }
  else{
  echo '<button type="button" class="btn outline me-2" onclick="Login()">Войти</button>
  <button type="button" class="btn buttons" onclick="Registr()">Регистрация</button>';
  }
  ?>
  <script>
    function Login(){
    window.location.href="login.php";
    }
    function Registr(){
    window.location.href="registr.php";
    }
    </script>

  </div>
  </header>