# Robustcart.php
Create a complete shoppingcart system, that can be deployed on both small and big scale sites, with a few lines of code<br><br>

### Usage
| Argument | Description |
| --- | --- |
|  1  | Dictionary array with structure:<br>"**form element name value**" => "**desired variable name/key used later when retreiving value**"<br>See examples bellow |
|  [  |  |
| `username_key`  | What your $_SESSION['thisvalue'] is for your username system, if you have one. |
| `save_dir`  | Folder to save the users shoppingcart |
| `encrypt`  | Encrypt shoppingcart file, if false: users shoppingcart file will be named as the users username |
|  ]  |  |
<p align="center">
  <code>
    $_SESSION['cart'] = new Cart($nameToVariable, [<strong>Options for saving cart</strong>]);
  </code><br>
  Simply leave options empty if you do not want cart saved in a file!
 </p>
<br>

### Examples
```html
<?php include('robustcart.php') ?>
<html>
...
<!-- Optional: Create an iframe and add target="votar" in the form
     to prevent page from refreshing each time the user adds an item to their cart -->
<iframe name="votar" style="display:none;"></iframe>
...
<form method="POST" target="votar">
  <p value="1034" name="articlenumber"> 1034 </p>
  <h1 value="Nuke" name="label"> Nuke </h1>
  <img value="img/uranB12_nuke.jpg" src="img/uranB12_nuke.jpg" name="image">
  <p value="Can be used as a toy or deadly weapon" type="text" name="description"> Can be used as a toy or deadly weapon </p>
  <button type="submit" name="add_to_cart"> Add To Cart </button>
</form>
```
```php
$values = array(
  "articlenumber" => "articlenmb", 
  "label" => "label",
  "image" => "img",
  "description" => "desc"
);
```

**Example 1 / 3**
```php
$_SESSION['cart'] = new Cart($values, ["username_key" => "username", "save_dir" => "users", "encrypt" => TRUE]);
```
- $_SESSION['username'] is the username variable
- Shoppingcart is stored in session and saved in "users" folder as their encrypted username.json

<br>

**Example 2 / 3**
```php
$_SESSION['cart'] = new Cart($values, []);
```
- Shoppingcart is not saved in file
- The customers cart is stored only in session which is the only option if you don't have accounts.<br> This is not the worse option, but the customers shopping cart is cleared when session cookie run out

<br>

**Example 3 / 3**
```php
$_SESSION['cart'] = new Cart($values, ["username_key" => "user", "save_dir" => "shoppingcarts", "encrypt" => FALSE);
```
- $_SESSION['user'] is the username variable
- Shoppingcart is saved in "shoppingcarts" folder as their username.json

<br><br>

### Retreiving and removing items
Can be done with a POST request containing a element with name=rfc (Remove-from-cart) and value=cart_index of item to remove
```php
foreach($_SESSION["shopping_cart"] as $keys => $values)
{
  // Echo value of the description element in example form
  echo $values['desc'];

  // Items can be removed only with post
  echo '<form method="POST"> <button type="submit" name="rfc" value="'.$values['cart_index'].'"> Remove </button> </form>';
}
```
Outputs "Can be used as a toy or deadly weapon" with a remove button
