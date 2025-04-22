<?php 
$svn="localhost";
$usn="root";
$pwd="";
$dbn="_a";

try{
    $conn = new mysqli($svn,$usn,$pwd,$dbn);
}
catch(mysqli_sql_exception) {
    echo "<h1>database connection failed,</h1><br>try again later";
}
