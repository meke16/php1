<?php
include "connect.php";
if(isset($_POST["submit"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $department = $_POST["department"];
    if(empty($id)) {
        $_SESSION['error'] = "required field";
    }
    elseif (empty($name)) {
        $_SESSION['error'] = "required field"; 
    }
    else {
    $sql = $conn->prepare("INSERT INTO test (id, name , department) VALUES(?,?,?);");
    $sql->bind_param("iss",$id,$name,$department);
    if($sql->execute()) {
        echo "<script> alert('data inserted')</script>";
        header("location: a.php");
        exit();
    } else {
        echo "process failed";
    }
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
    <title>asdfghjl;</title>
    <style>
        *{
            box-sizing: border-box;

        }
        body {
            display: flex;
            justify-content: center;
            height: 100vh;
            align-items: center;
            color: white;
            background-color: rgb(58,58,58 )

        }
        h1 {
            margin: auto;
        }
       .div1 {
        background-color: rgb(18, 18, 18);
        padding:10px 30px;
        border: none;
        border-radius: 20px;
       }
       input {
        padding: 5px;
        margin-left: 7px;
        margin-bottom: 5px;
        border-radius: 5px;
       }
       .submit {
        margin-top: 25px;
        color: white;
        font-weight: bold;
        font-size: large;
        padding: 5px;
        width: 120px;
        background-color: rgb(73, 74, 71);
        border: none;
       }
       .submit:hover {
        background-color: green;
       }
       .submit:active {
        color: black;
        background-color: antiquewhite;
       }
       .form1 {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 20px;
            margin:10px 11px;
            color: rgb(206, 206, 202);
            font-weight: bold;
         }
         .info {
            color: white;
            font-size: 25px;
            padding: 30px;
         }
         thead {
            padding: 25px;
            color: rgb(31, 184, 20);
            font-size: 25px
         }
         .deletebtn {
            background-color: red;
            color: white;
         }
    </style>

</head>
<body>
<div class="div1">
<h1>PHP-PRACTICE-01</h1>
<?php if (isset($_SESSION['error'])): ?>
            <!-- <div class="alert alert-danger text-center"  id="error-message"> -->
            <div>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
<form class="form1" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
        <label for="id">Id Number:</label>
        <input type="number" name="id" placeholder="put your id"><br>
        <label for="name">Name:</label>
        <input type="text" name="name" placeholder="enter your name"><br>
        <label for="department">Department:</label>
        <input type="text" name="department" placeholder="enter your department"><br>
        <input class="submit" type="submit" name="submit" value="insert">
    </form>
    <br>
    <div class="search">
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <input type="search" name="searchQuery" value="<?php echo $searchQuery; ?>">
        <input type="submit" name="search" value="search">
        <br>
    </form>
    </div>
    <br>
    <div class="table">
    <table border="1">
        <thead>
        <tr>
            <th>List</th>
            <th>Name</th>
            <th>Id_Number</th>
            <th>department</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            if($sql -> num_rows > 0):
                while($data = $sql->fetch_assoc()):
                    $num++;
                    $name = $data['name'];
                    $id = $data['id'];
                    $dep = $data['department'];
                    ?>
                    <tr class="info">
                        <td><?= $num; ?></td>
                        <td><?= $name; ?></td>
                        <td><?= $id; ?></td>
                        <td><?= $dep; ?></td>
                        <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?php echo $id; ?>">
                            <input class="deletebtn" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this record?');">
                        </form> 
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <tfoot>no data found </tfoot>
                        </tr>
                    <?php endif; ?>            
        </tbody>
    </table>
        </div>
</div>
</body>
</html>