<!-- Helper functions -->

<?php 
// Database connection
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'pizza');
 
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

function nav() {
    echo '
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="welcome.php">Pizza Store</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="pizzaOrder.php" class="nav-link text-primary">Order now</a>
            </li>
            <li class="nav-item">
                <a href="welcome.php" class="nav-link">My orders</a>
            </li>
            <li class="nav-item">
                <a href="order.php" class="nav-link">My order</a>
            </li>
            <li class="nav-item">
                <a href="welcome.php" class="nav-link">My account</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </nav>';
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
      parent::__construct($it, self::LEAVES_ONLY);
    }
  
    function current() {
      return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }
  
    function beginChildren() {
      echo "<tr>";
    }
  
    function endChildren() {
      echo "</tr>" . "\n";
    }
}

function printPizza($pizzaID) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM pizza WHERE pizzaID=".$pizzaID); 
    $stmt->execute(); 
    $row = $stmt->fetch();
    echo "<span class='text-warning'>Size</span>: ".$row["size"]."<br>";
    echo "<span class='text-warning'>Cheese</span>: ".$row["cheese"]."<br>";
    echo "<span class='text-warning'>Sauce</span>: ".$row["sauce"]."<br>";
    echo "<span class='text-warning'>Toppings</span>: ".$row["toppings"]."<br>";
}