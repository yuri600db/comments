<html>
<head>
	<title>Комментарии</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>

	<link href="bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include 'connect_to_db.php';
include 'func_comments.php';
?>
<style type="text/css">
	.block_comm{
		border-style: groove;
		border-width: 5px;
		border-color: cadetblue;
		border-radius: 8px;
		padding: 5px;
		background-color: lightblue;
		background-image: linear-gradient(mediumslateblue, lightblue);

	}
	.users{
		font-size: 20px;
		font-style: oblique ;
	}
	.reply_Comment{
		display: none;
	}
</style>
	<script type="text/javascript">
		$(document).ready(function(){

			$("#AddComm").click(function (e) {
				e.preventDefault();
				if(($("#commenT").val()==="") || ($("#users").val()==="") || ($("#email_").val===""))
				{
					alert("Заполните поля!");
					return false;
				}
			
				var myData = { comm: $("#commenT").val(), users: $("#users").val(), email_: $("#email_").val() };

				jQuery.ajax({
					type: "POST",
					url: "response.php",
					data: myData,
					success:  function(response) {
						$("#comms").append(response);
						$("#commenT").val('');
						$("#users").val('');
						$("#email_").val('');
					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			$("body").on("click", "#comms .del_Comment",function (e) {

				e.preventDefault();
				var clickID = this.id.split("-");
				var commID = clickID[2];
				var myData = {deleteComment:commID};

				jQuery.ajax({
					type: "POST",
					url: "response.php",
					data: myData,
					success: function(response){
						$("#com_"+commID).fadeOut();
					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			$("body").on("click", ".reply_add",function (e) {
				e.preventDefault();

				var clickID = this.name.split("-");
				var repID = clickID[2];
				if (($("#reply_c_"+repID).val()==="") || ($("#users_r"+repID).val==="") || ($("#email_r"+repID).val==""))
				{
					alert("Заполните поля!");
					return false;
				}
				var data = { level: $("#level_of_"+repID).val(), parent_user: $("#parent_user"+repID).val(), id_comment: repID, replyComment: $("#reply_c_"+repID).val(), users_r: $("#users_r"+repID).val(), email_r: $("#email_r"+repID).val()};

				jQuery.ajax({
					type: "POST",
					url: "response.php",
					data: data,
					success: function(response) {
						$("#reply_comment_"+repID).append(response);
						$("#users_r"+repID).val('');
						$("#email_r"+repID).val('');
						$("#reply_c_"+repID).val('');
						$("#block-reply-"+repID).hide();

					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			$("body").on("click", " #block_comm .reply_open",function (e) {
				e.preventDefault();
				var clickID = this.id.split("-");
				var commID = clickID[2];
				$("#block-reply-"+commID).show();
			});

			$("body").on("click", "  .cancel_rep",function (e) {
				e.preventDefault();
				var clickID = this.id.split("-");
				var commID = clickID[2];
				$("#block-reply-"+commID).hide();
			});
		

		});
		function CommentChild(parent, id) {
			document.getElementById("reply_comment_"+parent).appendChild(document.getElementById("com_"+id));
		}
	</script>

<FORM method="POST" role="form">

	<div  style="width:800px; margin-left: 20px" >
		<label>Комментарий</label>
		<input class="form-control" placeholder="Имя" width="100" id="users">
		<input type="email" class="form-control" placeholder="E-mail" id="email_" width="100">
		<textarea id="commenT" name="comme" cols="30" rows="5" class="form-control"  placeholder="Добавьте комментарий" ></textarea>
		<button id="AddComm" class="btn btn-primary" name='add'> Добавить</button>
	</div>

	<?php
	$comment_MAX=5;
	$counter=-1;

	echo "<div id='comms'>";
	$com_id=array();
	
	$com_id[0]=0;
	while ($counter<$comment_MAX){
		$counter++;
		$commentInfo=array();
		$commentInfo=get_Comment($com_id);

		if (count($commentInfo)==0){break;}

		$com_id=array();
		for ($i=0; $i<count($commentInfo); $i++) {
			$com_id[$i] = $commentInfo[$i]["id"];
            echo "<div class='com_parent'  id='com_".$commentInfo[$i]["id"]."'>";

			echo "<div id='block_comm' style='margin-left: " . (20 * ($counter + 1)) . "px; '>";
			echo "<input id='level_of_".$commentInfo[$i]["id"]."' value='".$counter."' style='display:none;'>";
			echo "<input  id='parent_user".$commentInfo[$i]["id"]."' value='".$commentInfo[$i]["user_"]."' style='display:none;'>";
			echo "<div class='block_comm'>";
			echo "<div>";
			echo "<p class='users'>" . $commentInfo[$i]["user_"]."</p>"  ;
			echo "<p >   Добавлено " . $commentInfo[$i]["date_add"] . " в " . $commentInfo[$i]["time_add"]."</p>";
			if ($commentInfo[$i]["id_parent"]!=0){
				$sql="SELECT user_ FROM comment WHERE id = ".$commentInfo[$i]["id_parent"];
				$result=$conn->query($sql);
				$row = $result->fetch_assoc();
				echo "<br>Ответ пользователю <B>".$row["user_"]. "</B> <br>";
			}
			echo "</div><br>";
			echo nl2br($commentInfo[$i]["text_comm"]);
			//echo $i;
			echo "<br><br>";
			echo "<div>";
			echo "<a href='#' class='reply_open' id='rep-open-" . $commentInfo[$i]["id"] . "'>Ответить</a>";
			echo " ";
			echo "<a href='#'  class='del_Comment' id='del-com-" . $commentInfo[$i]["id"] . "'>Удалить</a>";
			echo "</div>";
			echo "<div class='reply_Comment' id='block-reply-" . $commentInfo[$i]["id"] . "'>";
			echo "<label>Ответ</label>";
			echo '<input class="form-control" placeholder="Имя" width="100" id="users_r'. $commentInfo[$i]["id"].'">';
			echo '<input type="email" class="form-control" placeholder="E-mail" id="email_r'. $commentInfo[$i]["id"].'" width="100">';
			echo '<textarea id="reply_c_' . $commentInfo[$i]["id"] .'" cols="30" rows="5" class="form-control"  placeholder="Добавьте ответ" ></textarea>';
			echo "<div>";
			echo "<button id='AddRep' class='btn btn-primary reply_add' name='rep-com-" . $commentInfo[$i]["id"] . "'>Ответить</button>";
			echo "<button  class='btn btn-danger cancel_rep' id='cancel-reply-" . $commentInfo[$i]["id"] . "'>Отмена</button>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "<div style='background-color: lightblue;' id='reply_comment_". $commentInfo[$i]["id"]."'>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			if ($counter != 0) {
				echo "<script type='text/javascript'>CommentChild({$commentInfo[$i]['id_parent']},{$commentInfo[$i]['id']});</script>";
			}
		}

	}

 
	echo "</div>";
	$conn->close();
?>

</FORM>
</body>
</html>
