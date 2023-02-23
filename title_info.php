<?php
require_once "connect/db.php";
require_once "functions/func.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$title= $_GET["title"];


  $row = getInfoDB(
    "SELECT status_name, anime.id, title, episod_amount, episod_length, img, description,rating, years.year FROM anime_status JOIN anime on anime_status.status_key=anime.statys_key JOIN age_rating ON anime.id_raiting = age_rating.id JOIN years on anime.id_year= years.id WHERE title ='$title'",
    $link);
  $_SESSION["animeId"] = $row["id"];
  $cover = $row["img"];
  $amount_e = $row["episod_amount"];
  $lenth_e = $row["episod_length"];
  $status = $row["status_name"];
  $year = $row["year"];
  $descript = $row["description"];
  $rating = $row["rating"];

  $query = "SELECT g_name FROM genres_names JOIN ganres_for_title ON genres_names.genre_key=ganres_for_title.genre_key JOIN anime ON ganres_for_title.id_anime=anime.id WHERE title LIKE '$title'";
  $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
  while ($row = mysqli_fetch_assoc($rez)) {
    $arr[] = $row['g_name'];
  }
  if(count($arr)>0){
    $genre = implode(", ", $arr);
  }
  else{ $genre = " ";}

  $anime = $_SESSION['animeId'];
?>


<html lang="ru">
<head>
  <meta charset="utf-8">
  <title><?php echo $title ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="js/script.js"></script>

  <link rel="icon" href="../imgs/pokeball16.png" type="image/png">
  <link href="../css/main.css" rel="stylesheet">
  <link href="../css/title_page.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!--responsive tag-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body >
<?php require_once('header.php'); ?>

<div class="container" >
  <div class="info col-12 p-0">
    <h1 class="display-3 mb-3"><?php echo $title ?></h1>
    <div class="col-12 row p-0">
      <div class='col-md-3 col-sm-12'>
        <?php echo "<img height='335px' width='225px' src='http://animeproject/imgs/COVERS/$cover'>";?>



        <!-- дропдаун со списками -->

        <div class='dropdown mt-2 mb-4'>
          <?php

          if(!empty($_SESSION['login'])){
            $loginId = $_SESSION['id'];

            echo "
            <button type='button' class='btn btn-primary dropdown-toggle' id='add' data-toggle='dropdown'>
                      Добавить в список
                    </button> 
                    <div class='dropdown-menu'>
                      <a class='dropdown-item' id='p' href='functions/listsFunc.php?title=$title&do=p'>Запланировано</a>
                      <a class='dropdown-item' id='w' href='functions/listsFunc.php?title=$title&do=w'>Просмотрено</a>
                      <a class='dropdown-item' id='d' href='functions/listsFunc.php?title=$title&do=d'>Отложено</a>
                      <a class='dropdown-item' id='s' href='functions/listsFunc.php?title=$title&do=s'>Сморю</a>";

            $row = getInfoDB(
              "SELECT list_type_key AS k FROM lists WHERE id_anime='$anime' AND id_user='$loginId'",
              $link);
            if(!$row){
              echo "
              </div>
               <script>$('#add').css('background','#e25454');</script>";
            }else{
              $key = $row['k'];
              echo "
              <a class='dropdown-item text-danger'  id='dlt' href='functions/listsFunc.php?title=$title&do=dlt'>Удалить из списка</a>
                    </div>
                    
                    <script>$('#$key').addClass('bg-info');</script>";
            }
          }else{
            echo " 
            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' disabled='true'>
            Добавить в список
            </button>";
          }
          ?>

        </div>



        <!-- звезды -->
        <div class='list-title mr-3'> <p><b>Оценка</b></p></div>
        <div class="rating-result">
          <?php 

            if(!empty($_SESSION['login'])){
              $stars='';
              $loginId = $_SESSION['id'];
              $row = getInfoDB( "SELECT mark from lists WHERE id_anime='$anime' AND id_user='$loginId'", $link);
              $mark = $row['mark'];
              if($row==NULL){
                echo "";
              }elseif($row && $mark==NULL )
              {
                $stars.="<div class='rating-area'>
                <input type='radio' class='radio' id='star-5' name='rating' value='5'>
                <label for='star-5' title='Оценка «5»'></label>	
                <input type='radio' class='radio' id='star-4' name='rating' value='4'>
                <label for='star-4' title='Оценка «4»'></label>    
                <input type='radio' class='radio' id='star-3' name='rating' value='3'>
                <label for='star-3' title='Оценка «3»'></label>  
                <input type='radio' class='radio' id='star-2' name='rating' value='2'>
                <label for='star-2' title='Оценка «2»'></label>    
                <input type='radio' class='radio' id='star-1' name='rating' value='1'>
                <label for='star-1' title='Оценка «1»'></label>
              </div>
              <script>
                  $(document).on('click', '.radio', function(){
                  let starmark = $(this).val();
                  location.href=\"functions/stars.php?title=$title&starmark=\" + starmark;
                  console.log(mark);
                });
              </script>";
              echo $stars;    
            }elseif($row && $mark !=NULL){
                $gray = 5 - $mark;

                for($i=0; $i<$mark;$i++){
                  $stars.="<span class='active'></span>";
                }
                for($i=0; $i<$gray;$i++){
                  $stars.="<span></span>";
                }

                echo $stars;
              }
            }else{
              $row = getInfoDB( "SELECT ROUND(avg(mark),0) AS m from lists WHERE id_anime='$anime'", $link);
              if($row){
                $mark = $row['m'];
                $gray = 5 - $mark;
                for($i=0; $i<$mark;$i++){
                  $stars.="<span class='active'></span>";
                }
                for($i=0; $i<$gray;$i++){
                  $stars.="<span></span>";
                }
                echo $stars;
              }
            }
          ?>

        </div>
        <p class="ml-3" id="mark">
          <?php 
            $anime = $_SESSION['animeId'];
            $row = getInfoDB( "SELECT ROUND(avg(mark),2) AS m from lists WHERE id_anime='$anime'", $link);
            echo $row["m"];
            if($row['m']==0){
              echo "Нет оценок";
            }
          ?>
        </p>
      </div>

      <div class='col-md-9 col-sm-12 p-0'>
        <?php echo "<p>Количество эпизодов: <span>$amount_e</span></p>
        <p>Длительность эпизода: <span>$lenth_e мин.</span></p>
        <p>Статус: <span>$status</span></p>
        <p>Год выхода: <span>$year</span></p>
        <p>Рейтинг: <span>$rating</span></p>
        <p>Жанры: <span>$genre</span></p>
        <p>Описание: <span>$descript</span></p>";
        ?>
      </div>
    </div>
  </div>


  <div class="list-title"> <p><b>Комментарии</b></p></div>

  <div class="comments col-12 mb-4" id="comments-section">
  </div>




  <!-- комменты -->
  <?php if(empty($_SESSION['login'])){ echo "<style> #make-comment{ display:none;} </style>";} ?>
  <div id="make-comment" class="col-12 comment">
    <div class="form-group ">
      <label for="name">Логин: <?php echo $_SESSION['login'];?> <span id="reply"></span></label><br>
      <label for="message">Коммнетарий: </label>
      <textarea class="form-control h-25" id="message"></textarea>
    </div>
    <div class="text-end">
      <button type="button" class="btn buttons w-10" id="sendComment" disabled="true" >Отправить</button>
    </div>
  </div>

</div>

  <?php require_once('footer.php'); ?>
</body>
</html>