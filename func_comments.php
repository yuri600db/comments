<?php

function get_Comment($id_parent){
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="comments_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    $query="WHERE";
    $result_row=array();
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }
    $conn->set_charset('utf8');

        

        for($i=0; $i<count($id_parent);$i++){
            $id_parent[$i]=intval($id_parent[$i]);
            if ($i==0){
                $query.=" id_parent = ".$id_parent[$i];
            }else {
                $query.=" or id_parent = ".$id_parent[$i];
            }
        }
        
        $sql="SELECT * FROM comment ".$query;
        $result = $conn->query($sql);

        $k=-1;
        while ($row = $result->fetch_assoc()){
            $k++;
            $result_row[$k]["id"]=$row["id"];
            $result_row[$k]["id_parent"]=$row["id_parent"];
            $result_row[$k]["text_comm"]=$row["text_comm"];
            $result_row[$k]["user_"]=$row["user_"];
            $result_row[$k]["email"]=$row["email"];
            $result_row[$k]["date_add"]=$row["date_add"];
            $result_row[$k]["time_add"]=$row["time_add"];
            
        }
    
    $conn->close();
    return $result_row;
}

function delCommentsTree($comment_id){
    if ($comment_id>0){
        $servername="localhost";
        $username="root";
        $password="";
        $dbname="comments_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: ". $conn->connect_error);
        }
        $conn->set_charset('utf8');

        $idDelRow=array();
        $idDelRow[0]=$comment_id;
        $k=0;
        while ($k<count($idDelRow)){
            $sql="SELECT * FROM comment WHERE id_parent=".$idDelRow[$k];
            $result=$conn->query($sql);
            while ($row=$result->fetch_assoc()){
                $idDelRow[]=$row["id"];
            }
            $k++;
        }
       
        echo "<br>";
        for ($i=count($idDelRow)-1;$i>=0;$i--){
            $sql="DELETE FROM comment where id=".$idDelRow[$i];
            $conn->query($sql);
        }
        $conn->close();
    }
}

?>