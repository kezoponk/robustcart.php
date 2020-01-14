# Shoppingcart.php

##### Create a complete shoppingcart system with few lines of code, can be used on both small and big scale sites

The cart needs to be initalized this exact way: <code>$_SESSION['cart'] = new Cart......</code>

<br>

#### Arguments
>Arg 1 = Variable name you use when retrieving the value
>
>Arg 2 = What your $_SESSION['thisvalue'] is for your username system, if you have one. ***If not: enter false***
>
>Arg 3 = Folder to save the users shoppingcart
>
>Arg 4 = Encrypt shoppingcart file, if false: users shoppingcart file will be named as the users username

<br>

## Examples:

```html
<form method="POST">
  <p value="1034" name="articlenumber"> 1034 </p>
  <h1 value="Sword" name="label"> Nuke </h1>
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
```

<br>

*ex1*
```php
$_SESSION['cart'] = new Cart($values, "username", "users", TRUE);
```
***$_SESSION['username']*** is the username variable, shoppingcart is saved in ***"users"*** folder as their ***encrypted username***.cart

<br>

*ex2*
```php
$_SESSION['cart'] = new Cart($values, "user", "shoppingcarts", FALSE);
```
***$_SESSION['user']*** is the username variable, shoppingcart is saved in ***"shoppingcarts"*** folder as their ***username***.cart
