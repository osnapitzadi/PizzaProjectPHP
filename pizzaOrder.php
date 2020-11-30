<?php
require_once 'config.php';
// Initialize the session
session_start();

// Set a pizza id in global
if(!isset($_SESSION["pizza1"])){
    $_SESSION["pizza1"] = null; 
}
if(!isset($_SESSION["pizza2"])){
    $_SESSION["pizza2"] = null; 
}
if(!isset($_SESSION["pizza3"])){
    $_SESSION["pizza3"] = null; 
}

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// autorization on post 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(count($_POST["toppings"]) < 5){
        $topping_err = "Choose more than 5 toppings";
    } else {
        $size = $_POST["size"];
        $cheese = $_POST["cheese"];
        $sauce = $_POST["sauce"];
        $toppings = json_encode($_POST["toppings"]);

        //Prepare SQL statment 
        $sql = "INSERT INTO pizza (size, cheese, sauce, toppings) VALUES (:size, :cheese, :sauce, :toppings)";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":size", $param_size, PDO::PARAM_STR);
            $stmt->bindParam(":cheese", $param_cheese, PDO::PARAM_STR);
            $stmt->bindParam(":sauce", $param_sauce, PDO::PARAM_STR);
            $stmt->bindParam(":toppings", $param_toppings, PDO::PARAM_STR);
            
            // Set parameters
            $param_size = $size;
            $param_cheese = $cheese;
            $param_sauce = $sauce;
            $param_toppings = $toppings;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: order.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // set id of pizza from database to session global
            if ($_SESSION["pizza1"] === null){
                $_SESSION["pizza1"] = $pdo->lastInsertId();
            } elseif ($_SESSION["pizza2"] === null) {
                $_SESSION["pizza2"] = $pdo->lastInsertId();
            } elseif ($_SESSION["pizza3"] === null) {
                $_SESSION["pizza3"] = $pdo->lastInsertId();
            } else {
                header("location: order.php");
            }


            // Close statement
            unset($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Bootstrap -->
</head>
<body>
    <div class="container">
        <!-- Nav -->
        <?php nav();?>
        <!-- Nav -->
        <!-- Pizza builder -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="m-3">
            <h1>Create your <span class="text-info">OWN</span> Pizza</h1>
            <div class="m-3">
                <!-- Size -->
                <div>
                    <h3 class="text">Size:</h3>
                    <h6 class="text-muted">Choose 1<span class="badge badge-warning ml-3">required</span></h6>
                    <!-- Small size -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="small" name="size" value="small">
                        <label class="custom-control-label" for="small">Small</label>
                    </div>
                    
                    <!-- Normal size -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="normal" name="size" value="normal" checked>
                        <label class="custom-control-label" for="normal">Normal</label>
                    </div>
                    
                    <!-- Large size -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="large" name="size" value="large">
                        <label class="custom-control-label" for="large">Large</label>
                    </div>
                </div>
                <!-- /Size -->

                <!-- Cheese -->
                <div>
                    <h3 class="text">Cheese:</h3>
                    <h6 class="text-muted">Choose 1<span class="badge badge-warning ml-3">required</span></h6>
                    <!-- lightCheese -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="lightCheese" value="lightCheese" name="cheese">
                        <label class="custom-control-label" for="lightCheese">Light Cheese</label>
                    </div>
                    
                    <!-- noCheese -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="noCheese" value="noCheese" name="cheese" checked>
                        <label class="custom-control-label" for="noCheese">No cheese</label>
                    </div>
                    
                    <!-- normalCheese -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="normalCheese" value="normalCheese" name="cheese">
                        <label class="custom-control-label" for="normalCheese">Normal cheese</label>
                    </div>
                </div>
                <!-- /Cheese -->

                <!-- Sauce -->
                <div>
                    <h3 class="text">Sauce:</h3>
                    <h6 class="text-muted">Choose 1<span class="badge badge-warning ml-3">required</span></h6>
                    <!-- bbq -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="bbq" value="bbq" name="sauce">
                        <label class="custom-control-label" for="bbq">BBQ</label>
                    </div>
                    
                    <!-- original -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="original" value="original" name="sauce" checked>
                        <label class="custom-control-label" for="original">Original</label>
                    </div>
                    
                    <!-- ranch -->
                    <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="ranch" value="ranch" name="sauce">
                        <label class="custom-control-label" for="ranch">Normal cheese</label>
                    </div>
                </div>
                <!-- /Sauce -->

                <!-- Toppings -->
                <div>
                <h3 class="text">Toppings:</h3>
                    <h6 class="text-muted">Choose up to 10 <span class="text-danger"><?php echo $topping_err ?></span></h6>
                    <!-- bacon -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="bacon" value="bacon" name="toppings[]">
                        <label class="custom-control-label" for="bacon">Bacon</label>
                    </div>
                    <!-- mushrooms -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="mushrooms" value="mushrooms" name="toppings[]">
                        <label class="custom-control-label" for="mushrooms">Mushrooms</label>
                    </div>
                    <!-- onion -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="onion" value="onion" name="toppings[]">
                        <label class="custom-control-label" for="onion">Onion</label>
                    </div>
                    <!-- pineapple -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pineapple" value="pineapple" name="toppings[]">
                        <label class="custom-control-label" for="pineapple">Pineapple</label>
                    </div>
                    <!-- sausage -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="sausage" value="sausage" name="toppings[]">
                        <label class="custom-control-label" for="sausage">Sausage</label>
                    </div>
                    <!-- feta -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="feta" value="feta" name="toppings[]">
                        <label class="custom-control-label" for="feta">Feta</label>
                    </div>
                    <!-- pepperoni -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="pepperoni" value="pepperoni" name="toppings[]">
                        <label class="custom-control-label" for="pepperoni">Pepperoni</label>
                    </div>
                    <!-- salami -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="salami" value="salami" name="toppings[]">
                        <label class="custom-control-label" for="salami">Salami</label>
                    </div>
                    <!-- beef -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="beef" value="beef" name="toppings[]">
                        <label class="custom-control-label" for="beef">Beef</label>
                    </div>
                    <!-- jalapeno -->
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="jalapeno" value="jalapeno" name="toppings[]">
                        <label class="custom-control-label" for="jalapeno">Jalapeno</label>
                    </div>
                </div>
                <!-- /Toppings -->
            </div>
            <button type="submit" class="btn btn-primary">Order</button>
        </form>
        <!-- Pizza builder -->
    </div>
</body>
</html>