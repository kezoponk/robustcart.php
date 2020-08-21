# Robustcart.php ![stability-stable](https://img.shields.io/badge/stability-stable-green.svg)

Create a complete shoppingcart system with a few lines of code, can be used on both small and big scale sites<br>

#### 
| Argument | Description |
| --- | --- |
|  1  | Variable of the dictionary containing 'form element name' => 'desired variable name used later when retreiving shopping cart' |
|  [  |  |
| `username_key`  | What your $_SESSION['thisvalue'] is for your username system, if you have one. |
| `save_dir`  | Folder to save the users shoppingcart |
| `encrypt`  | Encrypt shoppingcart file, if false: users shoppingcart file will be named as the users username |
|  ]  |  |
<p align="center">
  <code>
    $_SESSION['cart'] = new Cart($nameToVariable, [Options for saving cart]);
  </code>
 </p>
<br>

### Examples:
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
  "articlenumber" => "articlenmb", 
  "label" => "label",
  "image" => "img",
  "description" => "desc"
);
// NAME-OF-INPUT-IN-FORM => DESIRED-VARIABLE-NAME
```

<br>

Example 1
```php
$_SESSION['cart'] = new Cart($values, ["username_key" => "username", "save_dir" => "users", "encrypt" => TRUE]);
```
- **$_SESSION['username']** is the username variable, shoppingcart is **stored in session and saved in "users" folder** as their **encrypted username**.json. Keep in mind that encryption **may** affect website performance on very big scale sites
___

<br>

Example 2
```php
$_SESSION['cart'] = new Cart($values, []);
```
- Shoppingcart is **not** saved in file, the customers cart is **stored only in session** which is the only option if you don't have accounts. **This is not the worse option**, but the customers shopping cart is cleared when session cookie run out
___

<br>

Example 3
```php
$_SESSION['cart'] = new Cart($values, ["username_key" => "user", "save_dir" => "shoppingcarts", "encrypt" => FALSE);
```
**$_SESSION['user']** is the username variable, shoppingcart is saved in **"shoppingcarts"** folder as their **username**.json
___

<br>

### Retrieving shopping cart
From the example form, and removing items
```php
foreach($_SESSION["shopping_cart"] as $keys => $values)
  {
    // Echo value of the description element in example form
    echo $values['desc'];
 
    // Items can be removed only with post
    echo '<form method="POST"> <button type="submit" name="rfc" value="'.$values['cart_index'].'"> Remove </button> </form>;
    
  }
```
- Outputs "Nuke" and "Can be used as a toy or deadly weapon", with a remove link, and button
