# Robustcart.php ![stability-stable](https://img.shields.io/badge/stability-stable-green.svg)

Create a complete shoppingcart system with a few lines of code, can be used on both small and big scale sites<br>
The cart needs to be **configured in the robustcart.php file at the configure here part**

#### Arguments
| Arg | Description |
| --- | --- |
|  1  | Variable of the array containing desired variable name used later when retreiving shopping cart => form element name |
|  2  | What your $_SESSION['thisvalue'] is for your username system, if you have one. ***If not: enter false*** |
|  3  | Folder to save the users shoppingcart |
|  4  | Encrypt shoppingcart file, if false: users shoppingcart file will be named as the users username |
<p align="center">
<code>
  $_SESSION['cart'] = new Cart($1, "2", "3", 4);
</code>
  </p>
<br>

## Examples:

```html
<?php include('robustcart.php') ?>
<html>
...
<form method="POST">
  <p value="1034" name="articlenumber"> 1034 </p>
  <h1 value="Nuke" name="label"> Nuke </h1>
  <img value="img/uranB12_nuke.jpg" src="img/uranB12_nuke.jpg" name="image">
  <p value="Can be used as a toy or deadly weapon" type="text" name="description"> Can be used as a toy or deadly weapon </p>
  <button type="submit" name="add_to_cart"> Add To Cart </button>
</form>
```
```php
$values = array(
  "articlenmb" => "articlenumber", 
  "label" => "label",
  "img" => "image",
  "desc" => "description"
);
// DESIRED-VARIABLE-NAME => NAME-OF-INPUT-IN-FORM
```

<br>

**Example 1**
```php
$_SESSION['cart'] = new Cart($values, "username", "users", TRUE);
```
***$_SESSION['username']*** is the username variable, shoppingcart is ***stored in session and saved in "users" folder*** as their ***encrypted username***.cart. Keep in mind that encryption ***may*** affect website performance

<br>

**Example 2**
```php
$_SESSION['cart'] = new Cart($values, "false", "false", FALSE);
```
Shoppingcart is ***not*** saved in file, the customers cart is ***stored only in session*** which is the only option if you don't have accounts. ***This is not the "worse option"***, but the customers shopping cart is cleared when session cookie run out

<br>

**Example 3**
```php
$_SESSION['cart'] = new Cart($values, "user", "shoppingcarts", FALSE);
```
***$_SESSION['user']*** is the username variable, shoppingcart is saved in ***"shoppingcarts"*** folder as their ***username***.cart

<br>

## Retrieving shopping cart
From the example form, and removing items
```php
foreach($_SESSION["shopping_cart"] as $keys => $values)
  {
    echo $values['label'].'<br>'; // Echo value of the articlenumber element in example form
    echo $values['desc'];
 // echo $values['DESIRED-VARIABLE-NAME']
 
 // Items can be removed only with post
    echo '<form type="POST"> <button type="submit" name="rfc" value="'.$values['cart_id'].'"> Remove </button> </form>;
    
  }
```
- Outputs "Nuke" and "Can be used as a toy or deadly weapon", with a remove link, and button
