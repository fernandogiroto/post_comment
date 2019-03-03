
<html>
<head>
<title>Add-Delete-comment</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="jquery-3.2.1.min.js"></script>

</head>
<body>
	<div class="demo-container">
		<form action=" " id="frmComment" method="post">
			<div class="row">
				<label> Name: </label> <span id="name-info"></span><input class="form-field" id="name"
					type="text" name="user"> 
			</div>
			<div class="row">
				<label for="mesg"> Message : <span id="message-info"></span></label>
				<textarea class="form-field" id="message" name="message" rows="4"></textarea>
				
			</div>
			<div class="row">
				<input type="hidden" name="add" value="post" />
				<button type="submit" name="submit" id="submit" class="btn-add-comment">Add Comment</button>
				<img src="LoaderIcon.gif" id="loader" />
			</div>
		</form>
<?php
include_once 'db.php';

$sql_sel = "SELECT * FROM tbl_user_comments ORDER BY id DESC";
$result = $conn->query($sql_sel);
$count = $result->num_rows;

    if($count > 0) {
?>
        <div id="comment-count">
        <span id="count-number"><?php echo $count;?></span> Comment(s)
        </div>
<?php } ?>
		<div id="response">
<?php
while ($row = $result->fetch_array(MYSQLI_ASSOC)) // using prepared staement
{
?>
			<div id="comment-<?php echo $row["id"];?>" class="comment-row">
				<div class="comment-user"><?php echo $row["username"];?></div>
				<div class="comment-msg" id="msgdiv-<?php echo $row["id"];?>"><?php echo $row["message"];?></div>
				<div class="delete" name="delete"
					id="delete-<?php echo $row["id"];?>"
					onclick="deletecomment(<?php echo $row["id"];?>)">Delete</div>
			</div>
<?php 
}
?>
		</div>
	</div>

	<script type="text/javascript"></script>
	<script>

    function deletecomment(id) {

       if(confirm("Are you sure you want to delete this comment?")) {

            $.ajax({
            url: "comment-delete.php",
            type: "POST",
            data: 'comment_id='+id,
            success: function(data){
                if (data)
                {
                    $("#comment-"+id).remove();
                    if($("#count-number").length > 0) {
                        var currentCount = parseInt($("#count-number").text());
                        var newCount = currentCount - 1;
                        $("#count-number").text(newCount)
                    }
                }
            }
           });
        }
     }

	$(document).ready(function() {

        $("#frmComment").on("submit", function(e) {
        		$(".error").text("");
            $('#name-info').removeClass("error");
            $('#message-info').removeClass("error");
            e.preventDefault();
            var name = $('#name').val();
            var message = $('#message').val();
            
            if(name == ""){
            		$('#name-info').addClass("error");
            }
            if(message == ""){
            		$('#message-info').addClass("error");
            }
            $(".error").text("required");
            if(name && message){
                	$("#loader").show();
            		$("#submit").hide();
            	 	$.ajax({
                
                 type:'POST',
                 url: 'comment-add.php',
                 data: $(this).serialize(),
                 success: function(response)
                    {
						$("#frmComment input").val("");
						$("#frmComment textarea").val("");
                     	$('#response').prepend(response);

                         if($("#count-number").length > 0) {
                             var currentCount = parseInt($("#count-number").text());
                             var newCount = currentCount + 1;
                             $("#count-number").text(newCount)
                         }
                         $("#loader").hide();
                 		$("#submit").show();
                     }
                	});
			}
		});
     });
    </script>
</body>
</html>