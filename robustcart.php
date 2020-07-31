<?php
// @author Albin Eriksson, https://github.com/kezoponk
// @license MIT, https://opensource.org/licenses/MIT

session_start();

class Cart {

  public $itemNames = array();
  public $savename = false;
  public $saveplace = NULL;
  public $encrypt = false;
  private $username = NULL;

  // Read users cart
  function readuserCart() {
    $filename = $this->saveplace."/" . $this->username . ".cart";
    $lines = file($filename);
    $completefile = "";

    // Attach line by line to json decode variable
    foreach ($lines as $line) {
      $completefile = $completefile.$line;
    }
    // Turn json shoppingcart into php array
    $_SESSION['shopping_cart'] = json_decode($completefile, true);
  }

  function saveuserCart() {
    $jsontxt = json_encode($_SESSION['shopping_cart']);
    // Create filename
    // folder/(username.cart or encrypted-username.cart)
    $filename =  $this->saveplace ."/". $this->username . ".cart";

    // Fatal error if unable to open
    $myfile = fopen($filename, "w") or die("Unable to open file!");

    // Save shoppingcart as in json format
    fwrite($myfile, $jsontxt);
    fclose($myfile);
  }

  public function __construct($marray, $savename, $saveplace, $encrypt) {

    $this->itemNames = $marray;
    $this->savename = $savename;
    $this->saveplace = $saveplace;

    if($encrypt) {
      // Turn the current users username that is used as filename for the cart file into md5 hash
      $this->username = md5($_SESSION[$this->savename]);
    } else {
      // Filename of cart file is the username
      $this->username = $_SESSION[$this->savename];
    }
    // Executed only on first refresh/reload after user login
    if(($this->savename != "false") && (isset($_SESSION[$this->savename])) && (!$_SESSION['keychain'])) {
      // Preventing this "first time login" code to be run every reload
      $_SESSION['keychain'] = true;
      // Create entered folder to save shoppingcart(s) in, if not already created
      mkdir($this->saveplace);
      $this->readuserCart();
    }
  }
}

// Configure here
$values = array(
  "label" => "label",
  "articlenumber" => "hidden-articlenumber",
);
$_SESSION['cart'] = new Cart($values, "username", "src/carts", TRUE);

// Add new article to cart
if(isset($_POST["add_to_cart"]))
{
  $count = 0;
  // If shopping cart is not set ( empty ) then input att index 0 of shopping cart array
  if(isset($_SESSION["shopping_cart"]))
  {
    $count = count($_SESSION["shopping_cart"]);
  }
  // Cart id is inserted into the 1st(0) index
  $item_array = array('cart_id' => rand(1000, 9999));

  // Then add the rest of the product / entity
  foreach($_SESSION['cart']->itemNames as $variablename => $formname) {
    $item_array += [$variablename => $_POST[$formname]];
  }
  // Finally enter the $item_array we just created into the main shopping cart array
  $_SESSION["shopping_cart"][$count] = $item_array;

  // Save cart if logged in & savename argument is not false
  if(($_SESSION['cart']->savename != "false") && (isset($_SESSION[$_SESSION['cart']->savename]))) {
    $_SESSION['cart']->saveuserCart();
  }
}

// Remove from cart
if(isset($_POST['rfc']))
{
  $id = $_POST['rfc'];
  // Retrieve the complete shopping cart
  foreach($_SESSION["shopping_cart"] as $keys => $values)
  {
    // Search for entered id
    if($values['cart_id'] == $id)
    {
      // Remove from main shopping cart array
      unset($_SESSION["shopping_cart"][$keys]);
      // Save new shopping cart
      if(($_SESSION['cart']->savename != "false") && (isset($_SESSION[$_SESSION['cart']->savename]))) {
        $_SESSION['cart']->saveuserCart();
      }
    }
  }
}

?>
