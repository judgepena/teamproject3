<?php
$hostname = 'localhost';
$username = 'judge1991';
$password = '';
$databaseName = 'c7154275';
$connection = mysqli_connect($hostname, $username, $password, $databaseName) or exit("Unable to connect database");


    function sanitize($dirty){
        return htmlentities($dirty,ENT_QUOTES,"UTF-8");
    }
    
    function get_category($child_id){
        global $connection;
        $id = sanitize($child_id);
        $sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid', c.category AS 'child'
                FROM categories c
                INNER JOIN categories p
                ON c.parent = p.id
                WHERE c.id = '$id'";
        $query = $connection->query($sql);
        $category = mysqli_fetch_assoc($query);
        return $category;
    }
    

?>

