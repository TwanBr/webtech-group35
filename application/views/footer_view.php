
</div>
<div class="col-md-3"></div>
</div>
<div class="col-md-12 footer">
    &copy; 2016 <a href="<?php echo base_url(); ?>">SocialFeed</a> All Rights Reserved.
</div>
</div>
</body>
</html>

<script type='text/javascript'>
    $(document).ready(function(){
        $(".submitbtn").live("click",function(){
            var post_id = $(this).attr('postid');
            var comment = $("#comment_"+post_id).val();
            if(comment == ''){
                alert("comment can't be empty!");
                return false;
            }else{
                $.ajax({
                    type: "POST",
                    data: $('#commentform_'+post_id).serialize(),
                    url: '<?php echo base_url("index.php/welcome/submitPostComment/"); ?>',
                    dataType: 'json',
                    success: function(response) {
                        if(response.ResponseCode == 200){
                            $('#postbox_'+post_id).load('<?php echo base_url("index.php/welcome/"); ?> #postbox_'+post_id+' >*');
                        }else{
                            alert(response.Message);
                        }
                    }
                });
            }
        });
        $(".dis_like_btn").live("click",function(){
            var post_id = $(this).attr('postid');
            $.ajax({
                type: "POST",
                data: {'post_id':post_id,'action':'dislike'},
                url: '<?php echo base_url("index.php/welcome/submitPostdisLike/"); ?>',
                dataType: 'json',
                success: function(response) {
                    if(response.ResponseCode == 200){
                        $('#postbox_'+post_id).load('<?php echo base_url("index.php/welcome/"); ?> #postbox_'+post_id+' >*');
                    }else{
                        alert(response.Message);
                    }
                }
            });
        });
        $(".like_btn").live("click",function(){
            var post_id = $(this).attr('postid');
            $.ajax({
                type: "POST",
                data: {'post_id':post_id,'action':'like'},
                url: '<?php echo base_url("index.php/welcome/submitPostLike/"); ?>',
                dataType: 'json',
                success: function(response) {
                    if(response.ResponseCode == 200){
                        $('#postbox_'+post_id).load('<?php echo base_url("index.php/welcome/"); ?> #postbox_'+post_id+' >*');
                    }else{
                        alert(response.Message);
                    }
                }
            });
        });
        /*
        $('#frmpost').on('submit', function(e){
            e.preventDefault();
            if($('#userfile').val() == '')
            {
                alert("Please Select the File");
            }
            else
            {
                $.ajax({
                    url:"<?php echo base_url(); ?>index.php/welcome/ajax_upload/",
                    //base_url() = http://localhost/tutorial/codeigniter
                    method:"POST",
                    data:new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    success:function(data)
                    {
                        $('#uploaded_image').html(data);
                    }
                });
            }
        });*/
/////////////////////////
        $("#frmpost").submit(function(evt){
            evt.preventDefault();

            var url = $(this).attr('action');
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $("#post_feed").val("");
                    $('#feed_div').load('<?php echo base_url("index.php/welcome/"); ?> #feed_div');

                },
                error: function (error) {
                    console.log(error);
                }
            }); // End: $.ajax()

        });
        ////////////////////////invitefriends
        $("#emailsend").click(function(){
            var post = $("#iemail").val();
            if(post == ""){
                alert("Post Feed Data can't be empty!");
                return false;
            }else{
               // alert(post);
                $.ajax({
                    type: "POST",
                    data: {'post_email':post,'action':'email'},
                    url: '<?php echo base_url("index.php/welcome/invitefriends/"); ?>',
                    dataType: 'json',
                    success: function(response) {
                        if(response.ResponseCode == 200){
                            alert("Email Sent successfully");
                        }else{
                            alert(response.Message);
                        }
                    }
                });
            }
        });
        /////////////////////////
        $("#btnpssssost").click(function(){
            var post = $("#post_feed").val();
            //var inputfile = $('input[name=image]');
            //alert(inputfile);
           // var filetoupload = inputfile[0].files[0]['name'];
            //alert(filetoupload);
            //console.log(filetoupload);
            //if(filetoupload != 'undefined') {
            //}
            if(post == ""){
                alert("Post Feed Data can't be empty!");
                return false;
            }else{
                $.ajax({
                    type: "POST",
                    data: {'post_feed':post,'action':'post'},
                    url: '<?php echo base_url("index.php/welcome/submitWallPost/"); ?>',
                    dataType: 'json',
                    success: function(response) {
                        if(response.ResponseCode == 200){
                            $("#post_feed").val("");
                            $('#feed_div').load('<?php echo base_url("index.php/welcome/"); ?> #feed_div');
                            //location.reload();
                        }else{
                            alert(response.Message);
                        }
                    }
                });
            }
        });

    });
    function callCrudAction(action,id) {
        var queryString;
                queryString = 'action='+action+'&id='+ id;

        jQuery.ajax({
            url: "<?php echo base_url("index.php/welcome/deletecomment/"); ?>",
            data:queryString,
            type: "POST",
            data:queryString,
            success:function(data){
                        $('#commentdel_'+id).fadeOut();
            },
        });}
    function showhidecomments(id) {
          $('#commentss_'+id).toggle("slide");

    };
</script>
<script type="text/javascript">
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

