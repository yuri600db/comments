<html>
<head>
	<title>Комментарии</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>

	<link href="bootstrap/bootstrap-3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="bootstrap/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
<?php
include 'connect_to_db.php';
?>
<style type="text/css">
	.block_comm{
		background-color: lightblue;
		margin-bottom: 10px;
	}
    
	#comms{
		 list-style: none;
		 margin-left: -30px;
	 }
	#comms li{
		list-style: none;
		background-color: grey;
		margin-bottom: 10px;
		padding: 10px;
	}
	.repl{
		list-style: none;
		margin-left: -30px;
	}
	.replli{
		list-style: none;
		background-color: lightgrey;
		margin-bottom: 10px;
		padding: 10px;
	}
	.reply_Comment{
		display: block;
	}
</style>
	<script type="text/javascript">
		$(document).ready(function(){

			$("#AddComm").click(function (e) {
				e.preventDefault();
				if($("#commenT").val()==="")
				{
					alert("Введите текст!");
					return false;
				}
				var myData = "comm=" + $("#commenT").val();

				jQuery.ajax({
					type: "POST",
					url: "responce.php",
					dataType: "text",
					data: myData,
					success: function(responce) {
						$("#comms").append(responce);
						$("#commenT").val('');

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
				var myData = "deleteComment=" + commID;

				jQuery.ajax({
					type: "POST",
					url: "responce.php",
					dataType: "text",
					data: myData,
					success: function(responce){
						$("#n_"+commID).fadeOut();
					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});

			$("body").on("click", " #AddRep",function (e) {
				//$("#AddRep").click(function(e){
				e.preventDefault();

				var clickID = this.name.split("-");
				var repID = clickID[2];
				if($("#reply_c_"+repID).val()==="")
				{
					alert("Введите текст!");
					return false;
				}
				var data = { id_comment: repID, replyComment: $("#reply_c_"+repID).val()};

				jQuery.ajax({
					type: "POST",
					url: "responce.php",
					dataType: "text",
					data: data,
					success: function(responce) {
						$("#reps_"+repID).append(responce);
						$("#reply_c_"+repID).val('');

					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});
			$("body").on("click", "#comms .del_reply",function (e) {
				e.preventDefault();
				var clickID = this.id.split("-");
				var commID = clickID[2];
				var myData = "deleteReply=" + commID;

				jQuery.ajax({
					type: "POST",
					url: "responce.php",
					dataType: "text",
					data: myData,
					success: function(responce){
						$("#re_"+commID).fadeOut();

					},
					error:function(xhr, ajaxOptions, thrownError){
						alert(thrownError);
					}
				});
			});
			$(".reply_open").click(function (e) {
				e.preventDefault();
				var clickID = this.id.split("-");
				var commID = clickID[2];
				//var myData = "replyComment=" + commID;
				$(".reply_Comment").show();
			});

		});
		$("#CancRep").click(function (e) {
			e.preventDefault();
			$(".reply_Comment").hide("slow");
			/*var clickID = this.name.split("-");
			var commID = clickID[2];

			$("#block-id-179").hide("slow");*/

		});

	</script>
</head>
<body>
<FORM method="POST" role="form">

	<div class="form-group" style="width:800px" >
		<label>Комментарий</label>
		<textarea id="commenT" name="comme" cols="30" rows="5" class="form-control"  placeholder="Добавьте комментарий" ></textarea>
		<button id="AddComm" class="btn btn-primary" name='add'> Добавить</button>
	</div>
    <?php
$sql="SELECT * FROM comment ORDER BY id DESC";
$result=$conn->query($sql);
	echo "<div>";
    echo "<ul  id='comms' >";
if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		echo "<div>";
		echo "<li id='n_" . $row["id"] . "'>";
		echo "<div>";
		echo "Добавлено ".$row["date_add"]." в ".$row["time_add"];
		echo "</div><br>";
		echo strval($row["text_comm"]);
		echo "<br><br>";
		echo "<div>";
		echo "<a href='#' class='reply_open' id='rep-open-".$row["id"]."'>Ответить</a>";
		echo " ";
		echo "<a href='#'  class='del_Comment' id='del-com-" . $row["id"] . "'>Удалить</a>";
		echo "</div>";
		echo "<div class='reply_Comment' id='block-id-'>";
		echo "<label>Ответ</label>";
		echo '<textarea id="reply_c_' . $row["id"] . '" cols="30" rows="5" class="form-control"  placeholder="Добавьте ответ" ></textarea>';
		echo "<div>";
		echo "<button id='AddRep' class='btn btn-primary' name='rep-com-" . $row["id"] . "'>Ответить</button>";
		echo "<button id='CancRep' class='btn btn-danger' name='canc-reply-" . $row["id"] . "'>Отмена</button>";
		echo "</div>";
		echo "</div>";
		$sql_r = "SELECT * FROM reply WHERE id_com = " . $row["id"];
		$result2 = $conn->query($sql_r);
		echo "<div>";
		echo "<ul class='repl' id='reps_".$row["id"]."' >";
		if ($result2->num_rows > 0) {
			while ($row2 = $result2->fetch_assoc()) {
				echo "<li style='background-color: lightblue' id='re_" . $row2["id"] . "'>";
				echo "<div>";
				echo "Добавлено ".$row2["date_add"]." в ".$row2["time_add"];
				echo "</div><br>";
				echo $row2["text"];
				echo "<br><br>";
				echo "<div>";
				echo "<a href='#' class='del_reply' id='del-re-" . $row2["id"] . "'>Удалить</a>";
				echo "</div>";
				echo "</li>";
			}
		}

		echo "</ul>";
		echo "</div>";
		echo "</li>";
		echo "</div>";
	}
	$conn->close();
}
	echo "</ul>";
	echo "</div>";
?>


</FORM>
</body>
</html>
