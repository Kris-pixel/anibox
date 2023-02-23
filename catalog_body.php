<?php 
  require_once 'connect/db.php';
  require_once 'functions/func.php';
?>


<div class="container content-place">
  
    <div class="heading col-10 search mb-3">
                <form name="search" class="form-inline form-search">
                    <div class="input-group">
                        <input class="form-control" id="searchInput" type="text" name="search" 
                        placeholder="Поиск" onkeyup="showHint(this.value)">
                        <div class="input-btn">
                            <button type="submit" class="btn btn-primary buttons">Найти</button>
                        </div>
                    </div>
                </form>
    </div>
    <div id="Search" class="col-8"  style="display: none;">
      <table id="SearchTable">
         <tbody id="txtHint">
        </tbody>
      </table>
    </div>

    <script>
      // $('#searchInput').blur(function(){
      //     $('#txtHint').css('display','none');
      // });
      function showHint(str) {
        Search.style.display = "block";
        if (str.length == 0) {
          document.getElementById("txtHint").innerHTML = "";
          return;
        } else {
          const xmlhttp = new XMLHttpRequest();
          xmlhttp.onload = function() {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        xmlhttp.open("GET", "functions/search.php?q=" + str);
        xmlhttp.send();
        }
      }
</script>



    <div class="container row main">
      <div class="col-md-10 col-sm-12 d-flex flex-row flex-wrap align-content-start" id="content-place">

        <?php 

          switch ($_REQUEST['action']) {
            case 'all':
              $query = "SELECT title, img from anime";
              break;
            case 'top10':
              $query = "SELECT title, img,  avg(mark) as r FROM anime JOIN lists ON anime.id = lists.id_anime GROUP BY title, img ORDER BY r DESC LIMIT 10";
              break;
            case 'ong':
              $query = "SELECT title, img from anime JOIN anime_status on anime.statys_key = anime_status.status_key WHERE anime_status.status_name LIKE 'онгоинг'";
              break;
            case 'anons':
              $query = "SELECT title, img from anime JOIN anime_status on anime.statys_key = anime_status.status_key WHERE anime_status.status_name LIKE 'анонс'";
              break;
            
            default:
              if(!empty( $_GET['search']) ) {
                $search = $_GET['search'];
                $query = "SELECT title, img from anime WHERE title LIKE '%$search%'";
              }
              else{
                $query = "SELECT title, img from anime";
              }
              
              break;
          }
          echo getContentAnime($link, $query); 
        ?>

      </div>

      <div class="col-md-2 col-sm-12">
              <form class="filter">
              
                <div class="form-group">
                  <label >Жанр</label>
                  <select class="form-select" id="g">
                    <option></option>
                  <?php echo getGenreList($link); ?>
                  </select>                  
                </div>
                <div class="form-group">
                  <label >Рейтинг</label>
                  <select class="form-select" id="nc">
                  <option></option>
                  <?php echo getRaiting($link); ?>
                  </select>
                </div>
                <div class="form-group">
                  <label >Сортировать по</label>
                  <select class="form-select" id="sort">
                    <option></option>
                    <option value="1">алфавиту (А-Я)</option>
                    <option value="2">алфавиту (Я-А)</option>
                    <option value="3">дате(сначала новые)</option>
                  </select>
                </div>
              
                <button class="w-100 btn btn-lg buttons" type="button" id="filter">Найти</button>
              </form>

              <script>
                   $(document).on('click', '#filter', function(){
                      let g = $("#g").val();
                      let nc = $("#nc").val();
                      let sort = $("#sort").val();

                       $.ajax({
                       url:"../functions/filter.php",
                       method:"GET",
                       cache: false,
                       data:{'g': g, 'nc': nc, 'sort': sort },
                       dataType:"html",
                       success:function(data)
                       {
                         if(data.error != '')
                         {
                            $("#content-place").text("");
                            $("#content-place").html(data);
                         }
                       }
                       });
                    });
              </script>
      </div>     
    </div>
  </div>

