<?
/**
* @author Albin Eriksson, https://github.com/kezoponk
* @license MIT, https://opensource.org/licenses/MIT
*/
session_start();

class Cart {
    private $itemNames = array();
    private $saveName = NULL;
    private $isLoggedIn = FALSE;
    private $encrypt = FALSE;

    function resetIndexes() {
        $updatedIndex = 0;
        foreach($_SESSION['shopping_cart'] as $keys => $values)
        {
            $_SESSION['shopping_cart'][$keys]['cart_index'] = $updatedIndex;
            $updatedIndex++;
        }
    }

    function writeUserCart() {
        // save_dir/(username.json or encrypted-username).json
        $fileName =  $this->savePlace .'/'. $this->saveName . '.json';

        // Fatal error if unable to open
        $writeTo = fopen($fileName, 'w') or die('Unable to write file!');

        // Save shoppingcart as in json format
        $jsontxt = json_encode($_SESSION['shopping_cart']);
        fwrite($writeTo, $jsontxt);
        fclose($writeTo);
    }

    function readUserCart() {
        $fileName = $this->savePlace.'/' . $this->saveName . '.json';

        if(file_exists($fileName)) {
            // Attach line by line to json decode variable
            $lines = file($fileName);
            $completeFile = '';
            foreach ($lines as $line) $completeFile = $completeFile . $line;

            // Turn json shoppingcart into php array
            $completeFileDecoded = json_decode($completeFile, TRUE);
            foreach($completeFileDecoded as $key => $value) {
                array_push($_SESSION['shopping_cart'], $value);
            }
            $this->resetIndexes();
            $this->writeUserCart();
            
        } else if(count($_SESSION['shopping_cart']) > 0) {
            $this->writeUserCart();
        }
    }

    function addToCart() {
        // Cart index is inserted into the 1st(0) index
        $itemArray = array('cart_index' => count($_SESSION['shopping_cart']));

        // Then add the rest of the product / entity
        foreach($this->itemNames as $formname => $variablename) {
            $itemArray += [$variablename => $_POST[$formname]];
        }
        // Finally enter the $item_array we just created into the main shopping cart array
        array_push($_SESSION['shopping_cart'], $itemArray);

        // Save cart if logged in & saveName argument is set
        if($this->isLoggedIn) {
            $this->writeUserCart();
        }
    }

    function removeFromCart() {
        $index = $_POST['remove-from-cart'];
        // Retrieve the complete shopping cart
        foreach($_SESSION['shopping_cart'] as $keys => $values)
        {
            // Search for entered id
            if($values['cart_index'] == $index)
            {
            // Remove from main shopping cart array
            unset($_SESSION['shopping_cart'][$keys]);
            }
        }
        // Since an item has been removed the indexes is incorrect(unless it was the last item)
        $this->resetIndexes();

        // Save new shopping cart
        if($this->isLoggedIn) {
            $this->writeUserCart();
        }
    }

    public function __construct($nameToVariable, $options) {
        if(isset($options['username_key'])) $this->username = $options['username_key'];
        if(isset($options['save_dir'])) $this->savePlace = $options['save_dir'];
        if(isset($options['encrypt'])) $this->encrypt = $options['encrypt'];
        $this->itemNames = $nameToVariable;
        $this->isLoggedIn = (isset($_SESSION[$this->username]));

        if($this->encrypt && $this->isLoggedIn) {
            // Turn the current users username that is used as filename for the cart file into md5 hash
            $this->saveName = md5($_SESSION[$this->username]);
        } else if($this->isLoggedIn) {
            // Filename of cart file is the username
            $this->saveName = $_SESSION[$this->username];
        }

        // Initialize cart
        if(!isset($_SESSION['shopping_cart'])) {
            $_SESSION['shopping_cart'] = array();
        }

        // Executed only on first refresh/reload after user login
        if($this->isLoggedIn && !isset($_SESSION['robustcart_keychain'])) {
            // Preventing this "first time login" code to be run every reload
            $_SESSION['robustcart_keychain'] = true;
            $this->readUserCart();
        }
    }
}

if(isset($_POST['add-to-cart']))
{
    $_SESSION['cart']->addToCart();
}
if(isset($_POST['remove-from-cart']))
{
    $_SESSION['cart']->removeFromCart();
}
