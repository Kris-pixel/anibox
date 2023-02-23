<?php
if(session_status()!=PHP_SESSION_ACTIVE) session_start();
require_once('connect/db.php');
require_once 'functions/func.php';

$login = $_SESSION['login'];

$row = getInfoDB(
  "SELECT * FROM users  WHERE login ='$login'",
  $link);
$id = $row['id'];
$img = $row['img'];
$email = $row['email'];
$reg_date = $row['reg_date'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Личный кабинет</title>
  <link rel="icon" href="imgs/pokeball16.png" type="image/png">
  <link href="css/main.css" rel="stylesheet">
  <link href="css/room.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!--responsive tag-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body >
  <?php include ("header.php"); ?>

  <div class="container">
    <div class="head row col-12">

      <div class="col-md-3 col-sm-12 mb-2 mt-5" >
        <div class="card">
          <?php
          if($img == NULL){ $img ='default-user.png';}
          echo "<img class='img-fluid' src='imgs/users/$img' alt='card image'>";
          ?>
        </div> 
      </div>

      <div class="col-md-9 mb-5 d-flex flex-row justify-content-between align-items-top pt-5">

        <div>
          <h1><?php echo $login;?></h1> 
          <p><?php echo $email; ?></p>
          <p>
            <?php 
              $reg =date_create_from_format('Y-m-d H:i:s', $reg_date);
              $reg = date_format($reg, 'd.m.Y');
              echo "Использует сайт с $reg";
            ?>
        </p>
        </div>
          <div class="d-flex flex-column justify-content-between h-50 align-items-end">
            <button type="button" class="btn-sm buttons w-25" id="exit" onclick="Exit()">Выйти</button>
            <script>
                function Exit(){
                    window.location.href = "functions/logout.php";
                }
            </script>
          <button type="button" id="addTitle" class="btn-sm buttons" style="display:none;">Добавить тайтл</button>

          <form enctype="multipart/form-data" id="send-photo" class="d-flex flex-column justify-content-around m-0 mt-3" action="functions/photo.php" method="POST">
          <label>Изменить фото</label>
            <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
             <input name="userphoto" type="file" accept=".png, .jpg, .jpeg">
            <input type="submit" value="Сохранить" class="btn-sm buttons w-50 mt-2"/>
            </form>

        </div>
        <?php if($_SESSION['role']) {print "<script> addTitle.style.display = 'block'; </script>";}?>

      </div>

    </div>

    <div class="lists row col-12">

      <div class="col-md-3 col-sm-12 px-5">
        <?php
        $row = getInfoDB(
          "SELECT count(mark) as c, mark FROM lists WHERE id_user = '$id' GROUP BY mark ORDER BY c DESC LIMIT 1",
          $link);
        $mark = $row['mark'];

        $query = "SELECT count(g_name) as c, g_name FROM lists JOIN ganres_for_title ON lists.id_anime=ganres_for_title.id_anime JOIN genres_names ON ganres_for_title.genre_key=genres_names.genre_key WHERE id_user = '$id' GROUP BY g_name ORDER BY c DESC LIMIT 3";
        $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
        while ($row = mysqli_fetch_assoc($rez)) {
          $arr[] = $row['g_name'];
        }
        if(count($arr)>0){
          $genre = implode(", ", $arr);
        }
        else{ $genre = " ";}

        $row = getInfoDB(
          "SELECT count(rating) as c, rating FROM lists JOIN anime ON lists.id_anime=anime.id JOIN age_rating ON anime.id_raiting=age_rating.id WHERE id_user = '$id' GROUP BY rating ORDER BY c DESC LIMIT 1",
          $link);
        $raiting = $row['rating'];
        ?>

          <div class="stat" >
            <h6>Любимая оценка: <span><?php echo $mark;?></span></h6>
        </div >
        <div class="stat">
            <h6>Любимые жанры: <span><?php echo $genre;?></span></h6>
        </div >
        <div class="stat">
            <h6>Любимый рейтинг: <span><?php echo $raiting;?></span></h6>
        </div >

      </div>

      <div class="col-md-9 " id="kkkk">

          <div class="list-title"> <p><b>Смотрю</b></p></div>
          <table class="b-table list-lines w-100">
            <thead>
              <tr>
                <th class="index">#</th>
                <th class="name order-control">Название</th>
                <th class="num order-control" >Оценка</th>
                <th class="num order-control" >Эпизоды</th>
                <th class="num order-control" ></th>
              </tr>
              <tr class="border">
                <th colspan="5"></th>
              </tr>
            </thead>

              <tbody class="entries">
                <?php $rowcount=1; $rowcount = getTable($link, "смотрю", $id, $rowcount);?>
              </tbody>
            </table>

          <div class="list-title"> <p><b>Запланировано</b></p></div>
            <table class="b-table list-lines w-100 mb-4">
              <thead>
                <tr>
                  <th class="index">#</th>
                  <th class="name order-control active" data-order="name" title="Упорядочить по названию">Название</th>
                  <th class="num order-control" data-order="rate_score" title="Упорядочить по оценке">Оценка</th>
                  <th class="num order-control" data-order="episodes" title="Упорядочить по эпизодам">Эпизоды</th>
                  <th class="num order-control" ></th>
                </tr>
                <tr class="border">
                  <th colspan="5"></th>
                </tr>
              </thead>
              <tbody class="entries">
              <?php $rowcount = getTable($link, "запланировано", $id, $rowcount);?>
              </tbody>
          </table>

        <div class="list-title"> <p><b>Просмотрено</b></p></div>
            <table class="b-table list-lines w-100 mb-4">
              <thead>
                <tr>
                  <th class="index">#</th>
                  <th class="name order-control active" data-order="name" title="Упорядочить по названию">Название</th>
                  <th class="num order-control" data-order="rate_score" title="Упорядочить по оценке">Оценка</th>
                  <th class="num order-control" data-order="episodes" title="Упорядочить по эпизодам">Эпизоды</th>
                  <th class="num order-control" ></th>
                </tr>
                <tr class="border">
                  <th colspan="5"></th>
                </tr>
              </thead>
              <tbody class="entries">
              <?php $rowcount = getTable($link, "просмотрено", $id, $rowcount);?>
              </tbody>
          </table>

        <div class="list-title"> <p><b>Отложено</b></p></div>
            <table class="b-table list-lines w-100 mb-4">
              <thead>
                <tr>
                  <th class="index">#</th>
                  <th class="name order-control active" data-order="name" title="Упорядочить по названию">Название</th>
                  <th class="num order-control" data-order="rate_score" title="Упорядочить по оценке">Оценка</th>
                  <th class="num order-control" data-order="episodes" title="Упорядочить по эпизодам">Эпизоды</th>
                  <th class="num order-control" ></th>
                </tr>
                <tr class="border">
                  <th colspan="5"></th>
                </tr>
              </thead>
              <tbody class="entries">
              <?php $rowcount = getTable($link, "отложено", $id, $rowcount);?>
              </tbody>
          </table>
      </div>

    </div>
  </div>


  <?php include ("footer.php"); ?>

  <style>
    .editform{
      display: none;
    }
    .show{
      display: table-row;
    }
  </style>

  <script>
    $(document).on('click', '.edit', function(){
        let show = $(this).children().val();
        $('#'+show).toggleClass("show");
      });
  </script>
</body>
</html>