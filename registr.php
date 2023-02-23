<?php 
    if(session_status()!=PHP_SESSION_ACTIVE) session_start();
    require_once('functions/func.php');
    include 'connect/db.php'; //подключаемся к БД
?>



<!DOCTYPE html>
<html lang="ru">
  <head>
        <meta charset="utf-8">
        <title>Anibox-регистрация</title>
        <link rel="icon" href="imgs/pokeball16.png" type="image/png">
        <link href="css/sign_in.css" rel="stylesheet">
        <link href="css/main.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!--responsive tag-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  
        <style>
          .error{
            color:#e25454;
            font-size: 10px;
          }
        </style>
  </head>
  <body class="text-center image">

  <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $eEn = $e1 = $e2 = $e3 = $e4 = "";

  if(empty($_POST['login'])){
    $e1 = "заполните поле";
  }elseif(!preg_match('/^(\w|\d){3,15}$/i', $_POST['login'])){
    $e1 = "Логин должен содержать от 3 до 15 символов";
  }else{
    $login = test_input($_POST['login']);
  }

  if(empty($_POST['password'])){
    $e2 = "заполните поле";
  }elseif(!preg_match('/^\d{8,}$/', $_POST['password'])){
    $e2 = "Пароль должен содержать минимум 8 символов";
  }else{
    $password = test_input($_POST['password']);
  }

  if(empty($_POST['password2'])){
    $e3 = "заполните поле";
  }elseif ($_POST['password']!=$_POST['password2']){
    $e3.="Пароли не совпадают<br>"; 
  }else{
    $password2 = test_input($_POST['password2']);
  }

  if(empty($_POST['email'])){
    $e4 = "заполните поле";
  }elseif(!preg_match('/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $_POST['email'])){
    $e4 = "неверный формат";
  }else{
    $email = test_input($_POST['email']);
  }


  $eEn=$e1.$e2.$e3.$e4;

  

if($eEn==""){
  $sameUser = mysqli_query($link,"SELECT login FROM users WHERE login LIKE '%$login%'") or die("Ошибка " . mysqli_error($link)); 
  $row = mysqli_fetch_assoc($sameUser); 
  $user = $row['login']; 

  if(!$user){
    $salt = mt_rand(100, 999); 
    $passwordHash = md5(md5($password).$salt); 
    $query="INSERT INTO users (login, password, email, salt) VALUES ('$login','$password','$email', '$salt')"; 
    $result=mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

    if ($result) { 
      $query="SELECT * FROM users WHERE login='$login'";
      $rez = mysqli_query($link, $query); 
      if ($rez) { 
        $row = mysqli_fetch_assoc($rez); 
        $_SESSION['id'] = $row['id'];
        $_SESSION['login'] = $row['login'];
        $_SESSION['role'] = $row['role']; 

        echo "Вы успешно зарегистрированы, ".$_SESSION['login']; 
        mysqli_close($link); 
        // выводим уведомление об успехе операции и перезагружаем страничку 
        print "<script language='Javascript' type='text/javascript'> 
        alert ('Вы успешно зарегистрировались! Спасибо!');
        window.location.href = 'index.php';
        </script>"; 
        $data = "Registration succeed ".$tm."\n";
        file_put_contents('data/log.txt',$data,FILE_APPEND);
      } else {
        print "<script language='Javascript' type='text/javascript'> 
        alert ('Ваши данные не были снесены в БД!');
        </script>"; 
        $data = "Database error ".$tm."\n";
        file_put_contents('log.txt',$data,FILE_APPEND);
      } 
  }
  }else{
    print "<script language='Javascript' type='text/javascript'> 
    alert ('Такой пользователь уже существует!');
    </script>"; 
  }
}else{
  echo"<script language='Javascript' type='text/javascript'> 
  alert ('Неверные данные!!');
  </script>"; 
  $time = date("Y-m-d H:i:s",time());
  $data = "Registration completed with errors ".$time."\n";
  file_put_contents('data/log.txt',$data,FILE_APPEND);
}
}

?>

  <main class="form-signin signin">
    <form method="post">
      <img src="imgs/pokeball.png" alt="" width="52" height="52">
      <h2 class="mb-4">Anibox</h2>
      <h1 class="h3 mb-3 fw-normal"> Регистрация</h1>
                
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" name="login">
        <label for="floatingInput">Логин <span class="error"><?=@$e1;?></span></label>
      </div>

      <div class="form-floating">
          <input type="email" class="form-control" id="floatingEmail" name="email">
          <label for="floatingInput">Email <span class="error"><?=@$e4;?></span></label>
      </div>
      <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password">
          <label for="floatingPassword">Пароль <span class="error"><?=@$e2;?></span></label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword2" name="password2" >
        <label for="floatingPassword">Подтвердить пароль <span class="error"><?=@$e3;?></span></label>
      </div>
                
      <button class="w-100 btn btn-lg buttons" type="submit">Регистрация</button>
    </form>
  </main>    
  </body>
</html>