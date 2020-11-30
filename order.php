<?php
require_once 'config.php';
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Prepare an insert statement
    $sql = "INSERT INTO orders(userID, pizzaID1, pizzaID2, pizzaID3) VALUES (:id,:pizza1,:pizza2,:pizza3);";
    var_dump($sql);


    if($stmt = $pdo->prepare($sql)){      
        // Bind variables to the prepared statement as parameters  
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pizza1', $pizza1, PDO::PARAM_INT);
        $stmt->bindParam(':pizza2', $pizza2, PDO::PARAM_INT);
        $stmt->bindParam(':pizza3', $pizza3, PDO::PARAM_INT);

        // Set parameters
        $id = $_SESSION["id"];
        $pizza1 = $_SESSION["pizza1"];
        $pizza2 = $_SESSION["pizza2"];
        $pizza3 = $_SESSION["pizza3"];

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Redirect to login page
            header("location: welcome.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        unset($stmt);
    }
    unset($pdo);
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
        <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Bootstrap -->
</head>
<body>
    <div class="container">
        <?php nav(); ?>
        <h1>Order</h1>
        <div class="d-flex flex-row">
            <!-- pizza1 -->
            <div class="card m-3 col-sm">
                <div class="card-body">
                    <?php 
                    if ($_SESSION["pizza1"] !== null){
                    ?>
                    <h5 class="card-title">Pizza 1</h5>
                    <p class="card-text">
                    <!-- sql statment -->
                    <?php 
                    printPizza($_SESSION["pizza1"]);
                    ?>
                    </p>
                    <?php 
                    } else {

                    ?>
                    <a href="pizzaOrder.php" class="btn btn-primary">Add new pizza</a>
                    <?php 
                    }
                    ?>
                </div>
            </div>
            <!-- pizza1 -->
            <!-- pizza2 -->
            <div class="card m-3 col-sm">
                <div class="card-body">
                    <?php 
                    if ($_SESSION["pizza2"] !== null){
                    ?>
                    <h5 class="card-title">Pizza 2</h5>
                    <p class="card-text">
                    <!-- sql statment -->
                    <?php 
                    printPizza($_SESSION["pizza2"]);
                    ?>
                    </p>
                    <?php 
                    } else {

                    ?>
                    <a href="pizzaOrder.php" class="btn btn-primary">Add new pizza</a>
                    <?php 
                    }
                    ?>
                </div>
            </div>
            <!-- pizza2 -->
            <!-- pizza3 -->
            <div class="card m-3 col-sm">
                <div class="card-body">
                    <?php 
                    if ($_SESSION["pizza3"] !== null){
                    ?>
                    <h5 class="card-title">Pizza 3</h5>
                    <p class="card-text">
                    <!-- sql statment -->
                    <?php 
                    printPizza($_SESSION["pizza3"]);
                    ?>
                    </p>
                    <?php 
                    } else {

                    ?>
                    <a href="pizzaOrder.php" class="btn btn-primary">Add new pizza</a>
                    <?php 
                    }
                    ?>
                </div>
            </div>
            <!-- pizza3 -->
        </div>
        <form class="mt-3" action="<?php $_SERVER["SELF_PHP"] ?>" method="post">
            <input type="submit" class="btn btn-success" value="Checkout">
        </form>
    </div>
</body>
</html>