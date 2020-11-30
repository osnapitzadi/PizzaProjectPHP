<?php 
//Including functions
require_once "config.php";
// start session
session_start();
// create the email session variable if not already created
if(!isset($_SESSION["email"])){
    $_SESSION["email"] = null;
};

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Address validation 
    if(empty(trim($_POST["address1"]))){
        $address1_err = "Please enter address line 1.";     
    } else{
        $address1 = test_input($_POST["address1"]);
    }

    // address line 2
    if(empty(trim($_POST["address2"]))){
        $address2_err = "Please enter address line 2.";     
    } else{
        $address2 = test_input($_POST["address2"]);
    }

    // city
    if(empty(trim($_POST["city"]))){
        $city_err = "Please enter city.";     
    } else{
        $city = test_input($_POST["city"]);
    }

    // country
    if(empty(trim($_POST["country"]))){
        $country_err = "Please enter country.";     
    } else{
        $country = test_input($_POST["country"]);
    }

    // zip
    if(empty(trim($_POST["zip"]))){
        $zip_err = "Please enter Postal Code.";     
    } else{
        $zip = test_input($_POST["zip"]);
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($address1_err) && empty($address2_err) && empty($country_err) && empty($city_err) && empty($zip_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, address1, address2, city, postalCode, country) VALUES (:username, :password, :address1, :address2, :city, :zip, :country)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":address1", $param_address1, PDO::PARAM_STR);
            $stmt->bindParam(":address2", $param_address2, PDO::PARAM_STR);
            $stmt->bindParam(":city", $param_city, PDO::PARAM_STR);
            $stmt->bindParam(":country", $param_country, PDO::PARAM_STR);
            $stmt->bindParam(":zip", $param_zip, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_address1 = $address1;
            $param_address2 = $address2;
            $param_city = $city;
            $param_country = $country;
            $param_zip = $zip;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Bootstrap -->
</head>
<body>
    <div class="container">
        <h2 class="mt-3">Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Username -->
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="example@mail.com">
                <span class="help-block text-danger"><?php echo $username_err; ?></span>
            </div>
            <!-- Username -->

            <!-- Password -->
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="********">
                <span class="help-block text-danger"><?php echo $password_err; ?></span>
            </div>
            <!-- Password -->

            <!-- Confirm password -->
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="********">
                <span class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
            </div>
            <!-- Confirm password -->

            <!-- Adress -->
            <div class="form-group">
              <label for="address1">Address</label>
              <input type="text" class="form-control" id="address1" name="address1" placeholder="1234 Main St">
              <span class="help-block text-danger"><?php echo $address1_err; ?></span>
            </div>

            <div class="form-group">
              <label for="address2">Address 2</label>
              <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment, studio, or floor">
              <span class="help-block text-danger"><?php echo $address2_err; ?></span>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="London">
                <span class="help-block text-danger"><?php echo $city_err; ?></span>

              </div>
              <div class="form-group col-md-4">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Ontario">
                <span class="help-block text-danger"><?php echo $country_err; ?></span>
              </div>
              <div class="form-group col-md-4">
                <label for="zip">Postal Code</label>
                <input type="text" class="form-control" id="zip" name="zip" placeholder="N6L2A2">
                <span class="help-block text-danger"><?php echo $zip_err; ?></span>
              </div>
            </div>
            <!-- Adress -->

            <!-- Buttons -->
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <!-- Buttons -->
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
