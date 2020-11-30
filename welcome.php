<?php
require_once 'config.php';
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Bootstrap -->
</head>
<body>
    <div class="container">
        <?php 
        nav();
        ?>
        <h3 class="text-center m-3">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h3>
        <?php

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_SESSION["id"]); 
        $stmt->execute(); 
        $row = $stmt->fetch();
        $address1 = $row["address1"];
        $address2 = $row["address2"];
        $city = $row["city"];
        $country = $row["country"];
        $postalCode = $row["postalCode"];
        ?>
        <!-- Address Card -->
        <div class="d-flex flex-row justify-content-center">
            <div class="card m-3 col-6">
                <div class="card-body">
                <h5 class="card-title">Address Information</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><span class="text-info">Address line 1: </span><?php echo $address1 ?></li>
                    <li class="list-group-item"><span class="text-info">Address line 2: </span><?php echo $address2 ?></li>
                    <li class="list-group-item"><span class="text-info">City: </span><?php echo $city ?></li>
                    <li class="list-group-item"><span class="text-info">Country: </span><?php echo $country ?></li>
                    <li class="list-group-item"><span class="text-info">Postal Code: </span><?php echo $postalCode ?></li>
                </ul>
                <div class="card-body">
                    <a class="card-link">Edit Adress</a>
                    <a class="card-link text-danger">Delete address</a>
                </div>
                </div>
            </div>
        </div>
        <!-- Address Card -->

        <!-- my orders -->
        <div class="mt-3">
            <h1>My orders</h1>
            <table class="table">
            <thead>
                <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Order Time</th>
                <th scope="col">Pizza 1</th>
                <th scope="col">Pizza 2</th>
                <th scope="col">Pizza 3</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sql = "SELECT * FROM orders WHERE userID=".$_SESSION["id"];
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    // var_dump($result);
                    foreach($result as $row){
                ?>
                <tr>
                    <td><?php echo $row['orderID']; ?>&nbsp;</td>
                    <td><?php echo $row['orderTime']; ?></td>
                    <td><?php echo $row['pizzaID1']; ?></td>
                    <td><?php echo $row['pizzaID2']; ?></td>
                    <td><?php echo $row['pizzaID3']; ?></td>
                </tr>
                <?php 
                    };
                ?>
            </tbody>
        </div>
        <!-- my orders -->
    </div>
</body>
</html>