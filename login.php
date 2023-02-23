<?php
  
if(session_status()!=PHP_SESSION_ACTIVE) session_start();
require_once('functions/func.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eEn = $e1 = $e2 = "";


    if(empty($_POST['login'])){
      $e1 = "заполните поле";
    }else{
      $login = test_input($_POST['login']);
    }

    if(empty($_POST['password'])){
      $e2 = "заполните поле";
    }

  $eEn=$e1.$e2;

  if($eEn==""){
    if(isset($_POST["password"])){
      include 'connect/db.php';

      $query="SELECT * FROM users WHERE login ='$login'";
      $rez = mysqli_query($link, $query); 
      $row = mysqli_fetch_assoc($rez);
      if (count($row) == 0)
      {
          mysqli_close($link);
          $e1 .='Такого логина не существует!';
      }
      else {
        $password = test_input($_POST['password']);

          if ($row['password']==md5(md5($password).$row['salt']))
          {
              $_SESSION['id'] = $row['id']; 
              $_SESSION['login'] = $row['login']; 
              $_SESSION['role'] = $row['role']; 
              print "<script language='Javascript' type='text/javascript'>
              window.location.href = 'index.php';
              </script>";
          }
          else {
              print "<script language='Javascript' type='text/javascript'>
              alert ('Вы ввели не верные данные!');
              </script>";
          }
          mysqli_close($link);
      }
    }
  }

}
  ?>



<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Anibox-вход</title>
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
    
    <main class="form-signin signin">
      <form method="post">
        <img src="imgs/pokeball.png" alt="" width="52" height="52">
        <h2 class="mb-4">Anibox</h2>
        <h1 class="h3 mb-3 fw-normal"> Вход</h1>
                
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="login">
          <label for="floatingInput">Логин <span class="error"><?=@$e1;?></span></label>
          
        </div>
                    
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingPassword" name="password">
          <label for="floatingPassword">Пароль <span class="error"><?=@$e2;?></span></label>
        </div>
                
        <button class="w-100 btn btn-lg buttons" type="submit">Вход</button>
      </form>
    </main>
           
  </body>
</html>