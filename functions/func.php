<?php 
// include "..\connect\db.php"; не нашел файл по этому пути


function getGenreList($link) // DONE
{
    $arr = [];
    $result = '';
    $tmpl = "<option value='{g_name}'>{g_name}</option>";
    $query = "SELECT g_name from genres_names ORDER BY g_name ASC";
    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_assoc($rez)) {
        $arr[] = $row['g_name'];
    }

    //sort($arr);
    foreach ($arr as $k => $v) {
        $result .= str_replace('{g_name}', $v, $tmpl);
    }

    return $result;  
}

function getRaiting($link){
    $arr = [];
    $result = '';
    $tmpl = "<option value='{rating}'>{rating}</option>";
    $query = "SELECT rating from age_rating";
    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_assoc($rez)) {
        $arr[] = $row['rating'];
    }

    //sort($arr);
    foreach ($arr as $k => $v) {
        $result .= str_replace('{rating}', $v, $tmpl);
    }

    return $result;  

}

function getContentAnime($link, $query) // DONE
{
    $arrname = [];
    $arrimg = [];
    $result = '';

    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_assoc($rez)) {
        $arrname[] = $row['title'];
        $arrimg[] = $row['img'];
    }

    for ($i=0; $i < count($arrname); $i++) { 
        $result .=  " <div class='card col-md-3 col-sm-12 anime-card'>
                         <a href='title_info.php?title=".$arrname[$i]."'>
                         <div class='card-body p-0'><img src='http://animeproject/imgs/COVERS/".$arrimg[$i]."' width='217px' height='318px'></div> 
                         <div class='card-img-overlay text-center'>".$arrname[$i]."</div>
                         </a>
                 </div>";
    }
    
        return $result;  

}

function test_input($data) {
    $data = trim($data);
    $data=strip_tags($data);
    $data = htmlspecialchars($data, ENT_QUOTES); 
    $data = stripslashes($data);
    return $data;
  }
  
function getInfoDB($query, $link){
    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
    $row = mysqli_fetch_assoc($rez);
    return $row;
}










function getTable($link, $listType, $id, $rowcount){
    $arrname = []; $arrmarks = [];
    $arrwatched = []; $arrepisoddes = []; $recordId = [];
    $result = '';

    $query = "SELECT lists.id AS i,lists.id_anime AS a, lists.id_user AS u, watched_amount,mark, title, episod_amount, list_type_key FROM lists JOIN anime ON lists.id_anime=anime.id JOIN list_types ON lists.list_type_key=list_types.list_key  WHERE id_user='$id' AND type='$listType'";
    $rez = mysqli_query($link, $query)or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_assoc($rez)) {
        $arrname[] = $row['title'];
        $arrmarks[] = $row['mark'];
        $animeId[] = $row['a'];
        $arrwatched[] = $row['watched_amount'];
        $arrepisoddes[] = $row['episod_amount'];
        $recordId[] = $row['i'];

    }

    for ($i=0; $i < count($arrname); $i++) { 
        $index=$i+1;
        $result .=  "  <tr class='user_rate'>
        <td style='width:30px;'><span>$index</span></td>
        <td class='name'>
          <a href='title_info.php?title=$arrname[$i]'><span class='name-ru'>$arrname[$i]</span></a>
        </td>
        <td class='text-center' style='width:100px;'>
          <span class='current-value'>$arrmarks[$i]</span>
        </td>
        <td class='num text-center' style='width:100px;'><span class='current-value'><span>$arrwatched[$i]</span></span><span class='misc-value'><span class='b-separator inline'> из </span>$arrepisoddes[$i]</span>
        </td>
        <td class='num text-end' style='width:100px;'><button type='button' class='btn-sm buttons edit'>Изменить
        <input type='hidden' value='$rowcount'>
        </button></td>
        </tr>

        <tr class='editform' id='$rowcount' style='background: #cddeff;'>
        <td colspan ='5''>
        <form class='d-flex flex-row justify-content-between align-items-center p-2' method='post' action='functions/editlist.php'>
        <div class='form-group'>
            <label for='list'>Список:</label>
            <select class='form-control' id='list' name='list'>
                <option></option>
                <option value='s'>смотрю</option>
                <option value='w'>просмотрено</option>
                <option value='p'>запланировано</option>
                <option value='d'>отложено</option>
            </select>
          </div>
        <div class='form-group'>
          <label for='mark'>Оценка:</label>
          <input type='number' id='mark' name='mark' min='1' max='5'>
        </div>
        <div class='form-group'>
          <label for='wctd'>Просмотрено:</label>
          <input type='number' id='wctd' name='wctd' min='1' max='$arrepisoddes[$i]'>
          <input type='hidden' name='animeId' value='$animeId[$i]'>
          <label for='pwd'> серий из  $arrepisoddes[$i]</label>
        </div>
        <div class='form-group d-flex flex-column'>
        <a href='functions/delfromlistbut.php?id=$recordId[$i]' class='btn btn-default text-danger' style='height:30px;'>Удалить из списка</a>
        <button type='submit' class='btn btn-default buttons' style='height:30px;'>Готово</button>
        </div>
      </form>
        </td>
        </tr>";
        $rowcount++;
    }
    
    echo $result;
        return $rowcount; 
}
?>
