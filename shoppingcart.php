// Created by Albin Eriksson, https://github.com/kezoponk
// MIT License, https://opensource.org/licenses/MIT

<?php
session_start();

class Cart {

  public $itemNames = array();
  public $savename = false;
  public $saveplace = NULL;
  public $encrypt = false;
  private $username = NULL;

  // Read users cart
  function readusercart() {
    $x = 0;
    $y = 1;
    // Create filename                     folder/username.cart
    $filename = $this->saveplace ."/". $this->username . ".cart";
    $lines = file($filename);

    while($x <= 100) { // Maximum entities saved : 100
      $line = explode(";",$lines[$x]);

      // Break when file end is reached
      if (empty($lines[$x])) {
        break;
      }

      // If shopping cart is not set ( empty ) then input att index 0 of shopping cart array
      $count = 0;
      if(isset($_SESSION["shopping_cart"]))
      {
        $count = count($_SESSION["shopping_cart"]);
      }
      
      $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");

      // Cart id is inserted into the 1st index
      $item_array = array('cart_id' => $line[0]);

      foreach($this->itemNames as $variablename => $formname) {
        // Then add the rest of the product / entity
        $item_array += [$variablename => $line[$y]];
        $y++;
      }

      if(!empty($item_array)) {
        // Finally enter the $item_array we just created into the main shopping cart array
        $_SESSION["shopping_cart"][$count] = $item_array;
      }
      $x++;
    }
  }

  function savefunc() {
    // Create filename                        folder/username.cart
      $filename =  $this->saveplace ."/". $this->username . ".cart";
    
      // Fatal error if unable to open
      $myfile = fopen($filename, "w") or die("Unable to open file!");

      if(!empty($_SESSION["shopping_cart"])) {
        $txt = "";

        foreach($_SESSION["shopping_cart"] as $keys => $values)
        {
          $txt = $txt.$values['cart_id'].";";

          foreach($this->itemNames as $variablename => $name) {
            // Semicolon seperated values
            $txt = $txt . $values[$variablename].";";
          }

          // New line to distinguish different articles / entities
          $txt = $txt."\r\n";

        }
        // Validate data
        if(!($txt==";;;;;") && (strpos($txt, ";") !== false)) {
          fwrite($myfile, $txt);
        }
        fclose($myfile);
      }
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
  }
}

// Add new article to cart
if(isset($_POST["add_to_cart"]))
{
  $count = 0;

  // If shopping cart is not set ( empty ) then input att index 0 of shopping cart array
  if(isset($_SESSION["shopping_cart"]))
  {
    $count = count($_SESSION["shopping_cart"]);
  }

  $item_array_id = array_column($_SESSION["shopping_cart"], "cart_id");

  // Cart id is inserted into the 1st index
  $item_array = array('cart_id' => rand(1000, 9999));

  // Then add the rest of the product / entity
  foreach($_SESSION['cart']->itemNames as $variablename => $formname) {
    $item_array += [$variablename => $_POST[$formname]];
  }

  // Finally enter the $item_array we just created into the main shopping cart array
  $_SESSION["shopping_cart"][$count] = $item_array;

  // Save cart if logged in & savename argument is not false
  if(($_SESSION['cart']->savename != "false") && (isset($_SESSION[$_SESSION['cart']->savename]))) {
    $_SESSION['cart']->savefunc();
  }
}

function removefromcart($id) {
  // Retrieve the complete shopping cart
  foreach($_SESSION["shopping_cart"] as $keys => $values)
  {
    // Search for entered id
    if($values["cart_id"] == $id)
    {
      // Remove from main shopping cart array
      unset($_SESSION["shopping_cart"][$keys]);
      // Save new shopping cart
      savefunc();
      header("location:javascript://history.go(-1)");

    }
  }
}

// Remove from cart
if(isset($_GET['rfc']))
{
  removefromcart($_GET['rfc']);
}
else if(isset($_POST['rfc']))
{
  removefromcart($_POST['rfc']);
}

?>
