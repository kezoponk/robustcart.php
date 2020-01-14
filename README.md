# Shoppingcart.php

##### Create a complete shoppingcart system with a couple lines of code, can be used on both small and big scale sites

## Examples:

#####

```html
<form method="POST">
  <input type="hidden" name="articlenumber">
  <input type="text" name="label">
  <input type="text" name="description">
  <button type="submit" name="add_to_cart"> Add To Cart </button>
</form>
```

```php
$values = array(
  "articlenmb" => "articlenumber", 
  "label" => "label",
  "desc" => "description"
);

$_SESSION['cart'] = new Cart($values, "username", "users", TRUE);
```
