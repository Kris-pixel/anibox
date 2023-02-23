
    $(document).ready(function(){
      let parentId = 0;
      let text='';

      $('#message').change(function() {
        text = $(this).val().trim();

        if(text != ""){ 
        $('#sendComment').prop("disabled", false);
      }
      });
     
      $(document).on('click', '#sendComment', function(){
        $.ajax({
        url:"../functions/comments/add_comment.php",
        method:"POST",
        cache: false,
        data:{'parentId': parentId, 'text': text },
        dataType:"html",
        success:function(data)
        {
          if(data.error != '')
          {
          $('#reply').text('');
          $('#message').val('');
          load_comment();
          }
        }
        });
      });

      load_comment();  // подгрузка комментов при загруске страницы

      function load_comment()
      {
        $.ajax({
        url:"../functions/comments/fetch_comment.php",
        method:"POST",
        success:function(data)
        {
          $('#comments-section').html(data);
        }
        });
      }
    

      $(document).on('click', '.reply', function(){
        let replyLogin = "Ответ: ";
          let commId = $(this).attr("id");
          var name = $(this).children().val();
          parentId = commId;
          replyLogin+= name;
          $('#reply').text(replyLogin);
      });

    });