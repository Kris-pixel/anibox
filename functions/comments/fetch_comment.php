
<?php
require_once "../../connect/db.php";
if(session_status()!=PHP_SESSION_ACTIVE) session_start();

$output = '';

$animeId = $_SESSION['animeId'];

$query = "SELECT comments.id, text, publish, id_parent_comm, login, img FROM comments JOIN users ON comments.id_user=users.id WHERE id_anime='$animeId' AND id_parent_comm='0' ORDER BY comments.id";
$rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
while ($row = mysqli_fetch_assoc($rez)) {
if($row['img'] == NULL){
    $img = "../../imgs/users/default-user.png";
}else{
    $img ="../../imgs/users/".$row['img'];
}
$date = date_create_from_format('Y-m-d H:i:s',$row['publish'] );
$publish =date_format($date, 'd-m-Y H:i');

 $output .= "
 <div class='row comment '>
 <div class='col-md-1 col-sm-2'>
     <img class='rounded' src='$img' width='60' height='60'>
 </div>
 <div class='col-md-9 col-sm-8 px-4'>
     <h5>".$row['login']."</h5>
     <p>".$row['text']."</p>
     </p>
 </div>
 <div class='col-2 text-end'>
 <p style='font-size: 12px;'>".$publish."</p>
 <button type='button' class='btn-sm buttons reply' id='".$row['id']."'>Ответить 
 <input type='hidden' class='postName' name='postName' value='".$row['login']."'>
 </button></div>
</div>";

 $output .= get_reply_comment($link, $animeId, $row["id"], 0);
}

echo $output;




function get_reply_comment($link, $animeId, $parent_id, $marginleft)
{
 $query = "SELECT comments.id, text, publish, id_parent_comm, login, img FROM comments JOIN users ON comments.id_user=users.id WHERE id_anime='$animeId' AND id_parent_comm='$parent_id' ORDER BY comments.id";
 $output = '';
 $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
 $count = count($rez);
 if($parent_id == 0)
 {
  $marginleft = 0;
 }
 else
 {
  $marginleft = $marginleft + 48;
 }
 if($count > 0)
 {
  foreach($rez as $row)
  {
    if($row['img'] == NULL){
        $img = "../../imgs/users/default-user.png";
    }else{
        $img ="../../imgs/users/".$row['img'];
    }
    $date = date_create_from_format('Y-m-d H:i:s',$row['publish'] );
    $publish =date_format($date, 'd-m-Y H:i');
    
   $output .= "
   <div class='row comment' style='margin-left:$marginleft;'>
   <div class='col-md-1 col-sm-2'>
       <img class='rounded' src='$img' width='60' height='60'>
   </div>
   <div class='col-md-9 col-sm-8 px-4'>
       <h5>".$row['login']."</h5>
       <p>".$row['text']."</p>
       </p>
   </div>
   <div class='col-2 text-end'>
   <p style='font-size: 12px;'>".$publish."</p>
   <button type='button' class='btn-sm buttons reply' id='".$row['id']."'>Ответить 
   <input type='hidden' class='postName' name='postName' value='".$row['login']."'>
   </button></div>
  </div>";
   $output .= get_reply_comment($link, $animeId, $row["id"], $marginleft);
  }
 }
 return $output;
}

?>
