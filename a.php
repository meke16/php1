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

if(isset($_POST["submit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $department = $_POST["department"];

    $sql = $conn->prepare("INSERT INTO test (id, name , department) VALUES(?,?,?);");
    $sql->bind_param("iss",$id,$name,$department);
    if($sql->execute()) {
        echo "<script> alert('data inserted')</script>";
        // header("location: a.php");
        // exit();
    } else {
        echo "process failed";
    }
}

$searchQuery = '';
if(isset($_POST["search"]) && !empty($_POST["searchQuery"])) {
    $searchQuery = trim($_POST['searchQuery']);
    $searchQuery = ucwords(strtolower($_POST["searchQuery"]));
}
if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM test WHERE id = ?");
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }
}
$num = 0;
$sql =$conn->query ("SELECT id, name, department FROM test where name LIKE '%$searchQuery%' or id like '%$searchQuery%' or department Like '%$searchQuery%' order by name");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
        <label for="id">Id:</label>
        <input type="number" name="id" placeholder="put your id"><br>
        <label for="name">Name:</label>
        <input type="text" name="name" placeholder="enter your name"><br>
        <label for="department">department:</label>
        <input type="text" name="department" placeholder="enter your department"><br>
        <input type="submit" name="submit">
    </form>
    <br>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <input type="search" name="searchQuery" value="<?php echo $searchQuery; ?>">
        <input type="submit" name="search" value="search">
        <br>
    </form>
    <br>
    <table border="3">
        <thead>
        <tr>
            <th>List</th>
            <th>Name</th>
            <th>Id_Number</th>
            <th>department</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            if($sql -> num_rows > 0) {
                while($data = $sql->fetch_assoc()) {
                    $num++;
                    $name = $data['name'];
                    $id = $data['id'];
                    $dep = $data['department'];
                    echo "
                    <tr>
                    <td>$num</td>
                    <td>$name</td>
                    <td>$id</td>
                    <td>$dep</td>
                    <td>
                        <form method='POST' style='display:inline;'>
                                <input type='hidden' name='delete_id' value='{$id}'>
                                <input type='submit' name='delete' value='Delete' onclick=\"return confirm('Are you sure you want to delete this record?');\">
                        </form>
                     </td>
                    </tr>
                    ";
                }
            }
            ?>
        </tbody>
    </table>

 
</body>
</html>