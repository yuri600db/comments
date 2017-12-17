<?php
/**
 * Created by PhpStorm.
 * User: Юрий
 * Date: 16.12.2017
 * Time: 0:07
 */

include_once 'connect_to_db.php';

if (isset($_POST["comm"]) && strlen($_POST["comm"])>0) {
   /* $sql="SELECT * FROM comment ORDER BY id DESC";
    $conn->query($sql);*/
    $commAdd = $_POST["comm"];
    date_default_timezone_set('Europe/Moscow');
    $date_add=date('Y-m-d');
    $time_add=date('H:i:s');
    
    $sql= "INSERT INTO comment (id, text_comm, date_add, time_add) VALUES ('', '". $commAdd."', '".$date_add."', '".$time_add."')";
    if($conn->query($sql)) {
        $com_id = $conn->insert_id;
        echo "<li id='n_".$com_id."' >";
        echo "<div>";
        echo "<div>";
        echo "Добавлено ".$date_add." в ".$time_add;
        echo "</div><br>";
        echo strval($commAdd);
        echo "<br><br>";
        echo "<div>";
        echo "<a href='#' class='reply_open' id='rep-open-".$com_id."'>Ответить</a>";
        echo " ";
        echo "<a href='#'  class='del_Comment' id='del-com-".$com_id."'>Удалить</a>";
        echo "</div>";
        echo "</li>";

        $conn->close();
    } else{
        header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
        exit();
    }
}
elseif(isset($_POST["deleteComment"])&&($_POST["deleteComment"])>0 && is_numeric($_POST["deleteComment"])){
    $idDelCom=filter_var($_POST["deleteComment"],FILTER_SANITIZE_NUMBER_INT);
    $sql="DELETE FROM reply where id_com=".$idDelCom;
    $conn->query($sql);
    $sql="DELETE FROM comment WHERE id=".$idDelCom;
    $conn->query($sql);
    /* if (!$conn->query($sql)){
         header('HTTP/1.1 500 Could not delete record!');
         exit();
     }*/
    $conn->close();
}

if (isset($_POST["replyComment"]) && strlen($_POST["replyComment"])>0 && isset($_POST["id_comment"]) && is_numeric($_POST["id_comment"])) {
  
    $idCom = filter_var($_POST["id_comment"],FILTER_SANITIZE_NUMBER_INT);
    $repAdd = $_POST["replyComment"];

    date_default_timezone_set('Europe/Moscow');
    $date_add=date('Y-m-d');
    $time_add=date('H:i:s');
    
    $sql= "INSERT INTO reply (id, id_com, text, date_add, time_add) VALUES ('', $idCom,'". $repAdd."', '".$date_add."', '".$time_add."')";
    if($conn->query($sql)) {
        $rep_id = $conn->insert_id;
        echo "<li style='background-color: lightblue' id='re_".$rep_id."' >";
        echo "<div>";
        echo "Добавлено ".$date_add." в ".$time_add;
        echo "</div><br>";
        echo strval($repAdd);
        echo "<br><br>";
        echo "<div>";
        echo "<a href='#' class='del_reply' id='del-re-".$rep_id."'>Удалить</a>";
        echo "</div>";
        echo "</li>";

        $conn->close();
    } else{
        header('HTTP/1.1 500 Looks like mysql error, could not insert record!');
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