
<body>


<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 16.12.2017
 * Time: 0:07
 */

include_once 'connect_to_db.php';
include 'func_comments.php';

if ( isset($_POST["comm"]) && strlen($_POST["comm"])>0 && (isset($_POST["users"])>0) && strlen($_POST["users"])>0 && (isset($_POST["email_"])>0 && strlen($_POST["email_"])>0)) {
    
    $commAdd = $_POST["comm"];
    $user = $_POST["users"];
    $email=$_POST["email_"];
    date_default_timezone_set('Europe/Moscow');
    $date_add=date('Y-m-d');
    $time_add=date('H:i:s');
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $sql = "INSERT INTO comment (id, id_parent, text_comm, user_, email, date_add, time_add) VALUES ('', 0, '$commAdd','$user' , '$email', '$date_add', '$time_add')";
    if ($conn->query($sql)) {
        $com_id = $conn->insert_id;
        echo "<div class='com_parent'  id='com_" . $com_id . "'>";
        echo "<div id='block_comm' style='margin-left: 20px; '>";
        echo "<input id='level_of_" . $com_id . "' value='0' style='display:none;'>";
        echo "<input  id='parent_user" . $com_id . "' value='" . $user . "' style='display:none;'>";
        echo "<div class='block_comm'>";
        echo "<div>";
        echo "<p class='users'>" . $user."</p>"  ;
        echo "<p >   Добавлено " . $date_add . " в " . $time_add."</p>";
        echo "</div><br>";
        echo strval($commAdd);
        echo "<br><br>";
        echo "<div>";
        echo "<a href='#' class='reply_open' id='rep-open-" . $com_id . "'>Ответить</a>";
        echo " ";
        echo "<a href='#'  class='del_Comment' id='del-com-" . $com_id . "'>Удалить</a>";
        echo "</div>";
        echo "<div class='reply_Comment' id='block-reply-" . $com_id . "'>";
        echo "<label>Ответ</label>";
        echo '<input class="form-control" placeholder="Имя" width="100" id="users_r' . $com_id . '">';
        echo '<input type="email" class="form-control" placeholder="E-mail" id="email_r' . $com_id . '" width="100">';
        echo '<textarea id="reply_c_' . $com_id . '" cols="30" rows="5" class="form-control"  placeholder="Добавьте ответ" ></textarea>';
        echo "<div>";
        echo "<button id='AddRep' class='btn btn-primary reply_add' name='rep-com-" . $com_id . "'>Ответить</button>";
        echo "<button  class='btn btn-danger cancel_rep' id='cancel-reply-" . $com_id . "'>Отмена</button>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "<div style='background-color: lightblue' id='reply_comment_" . $com_id . "'>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        $conn->close();
    } else {
        header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
        exit();
    }
}
    else{
       echo "<script> alert('Неверно заполенено поле Email!') </script>";
        exit();
    }
}
elseif(isset($_POST["deleteComment"])&&($_POST["deleteComment"])>0 && is_numeric($_POST["deleteComment"])){
    $idDelCom=filter_var($_POST["deleteComment"],FILTER_SANITIZE_NUMBER_INT);
    /*$sql="DELETE FROM comment where id_parent in (SELECT id_parent FROM comment WHERE id =".$idDelCom.") and id=".$idDelCom;
    $conn->query($sql);
    $sql="DELETE FROM comment WHERE id=".$idDelCom;
   // $conn->query($sql);
     if (!$conn->query($sql)){
         header('HTTP/1.1 500 Could not delete record!');
         exit();
     }*/
    delCommentsTree($idDelCom);
    $conn->close();
}

if (isset($_POST["parent_user"]) && isset($_POST["level"])&& is_numeric($_POST["level"])  && isset($_POST["replyComment"]) && strlen($_POST["replyComment"])>0 && isset($_POST["id_comment"]) && is_numeric($_POST["id_comment"])&& (isset($_POST["users_r"])>0) && strlen($_POST["users_r"])>0 && (isset($_POST["email_r"])>0) && strlen($_POST["email_r"])>0) {

    $idCom = filter_var($_POST["id_comment"], FILTER_SANITIZE_NUMBER_INT);
    $commAdd = $_POST["replyComment"];
    $user = $_POST["users_r"];
    $parent_user = $_POST["parent_user"];
    $email = $_POST["email_r"];
    date_default_timezone_set('Europe/Moscow');
    $date_add = date('Y-m-d');
    $time_add = date('H:i:s');
    $counter = filter_var($_POST["level"], FILTER_SANITIZE_NUMBER_INT);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($_POST["level"] != 5) {
            $sql = "INSERT INTO comment (id, id_parent, text_comm, user_, email, date_add, time_add) VALUES ('', '$idCom', '$commAdd','$user' , '$email', '$date_add', '$time_add')";
            if ($conn->query($sql)) {
                $rep_id = $conn->insert_id;
                echo "<div class='com_parent' id='com_" . $rep_id . "'>";

                echo "<div id='block_comm' style='margin-left: " . (20 * ($counter + 2)) . "px; '>";
                echo "<input id='level_of_" . $rep_id . "' value='" . ($counter + 1) . "' style='display:none;'>";
                echo "<input  id='parent_user" . $rep_id . "' value='" . $user . "' style='display:none;'>";
                echo "<div class='block_comm'>";
                echo "<div>";
                echo "<p class='users'>" . $user."</p>"  ;
                echo "<p >   Добавлено " . $date_add . " в " . $time_add."</p>";
                echo "<br>Ответ пользователю <B>".$parent_user. "</B> <br>";
                echo "</div><br>";
                echo nl2br($commAdd);
                echo "<br><br>";
              
                echo "<div>";
                echo "<a href='#' class='reply_open' id='rep-open-" . $rep_id . "'>Ответить</a>";
                echo " ";
                echo "<a href='#'  class='del_Comment' id='del-com-" . $rep_id . "'>Удалить</a>";
                echo "</div>";
                echo "<div class='reply_Comment' id='block-reply-" . $rep_id . "'>";
                echo "<label>Ответ</label>";
                echo '<input class="form-control" placeholder="Имя" width="100" id="users_r' . $rep_id . '">';
                echo '<input type="email" class="form-control" placeholder="E-mail" id="email_r' . $rep_id . '" width="100">';
                echo '<textarea id="reply_c_' . $rep_id . '" cols="30" rows="5" class="form-control"  placeholder="Добавьте ответ" ></textarea>';
                echo "<div>";
                echo "<button id='AddRep' class='btn btn-primary reply_add' name='rep-com-" . $rep_id . "'>Ответить</button>";
                echo "<button  class='btn btn-danger cancel_rep' id='cancel-reply-" . $rep_id . "'>Отмена</button>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "<div style='background-color: lightblue' id='reply_comment_" . $rep_id . "'>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
               /* $message = wordwrap($commAdd, 70,"\r\n");
                $sql_e="SELECT * FROM comment WHERE id=".$idCom;
                $result=$conn->query($sql_e);
                $row=$result->fetch_assoc();

                mail($row["email"], "Пользователь ".$user." ответил на Ваш комментарий", $commAdd);*/

                $conn->close();
            } else {
                header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
                exit();
            }
        } else {
            echo "<script> alert('Превышение лимита вложенности!') </script>";
            exit();
        }
    }
else{
    echo "<script> alert('Неверно заполенено поле Email!') </script>";
    exit();
}
}

if(isset($_POST["deleteReply"])&&($_POST["deleteReply"])>0 && is_numeric($_POST["deleteReply"])){
    $idDelRep=filter_var($_POST["deleteReply"],FILTER_SANITIZE_NUMBER_INT);
    // $idDelCom=$_POST["deleteComment"];
    $sql="DELETE FROM reply WHERE id=".$idDelRep;
    $conn->query($sql);
    /* if (!$conn->query($sql)){
         header('HTTP/1.1 500 Could not delete record!');
         exit();
     }*/
   $conn->close();
}
?>

