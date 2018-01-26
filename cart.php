<?php
    require_once ('common.php');   

    if (isset($_GET['delId'])) { 
        if (in_array($_GET['delId'], $_SESSION['cart'])) {   
            $pos = array_search($_GET['delId'], $_SESSION['cart']);
            unset($_SESSION['cart'][$pos]);    
        }
    }

    if (count($_SESSION['cart'])) {        
        $numberParams = str_repeat('?,', count($_SESSION['cart']) - 1) .'?';
        $numberType = str_repeat('d', count($_SESSION['cart']));
        
        $sql = "SELECT * FROM products WHERE id IN ($numberParams)";
        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
        
        $refarg = array($stmt, $numberType);
        foreach ($_SESSION['cart'] as $key => $value) {
            $refarg[] =& $_SESSION['cart'][$key];
        }
        call_user_func_array("mysqli_stmt_bind_param", $refarg);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }    
    
    if(isset($_POST['send'])) {
        
        $error = 0;
        
        if (!count($_SESSION['cart'])) {
            $error = 1;
        }
        if (empty($_POST['name'])) {
            $error = 1;
        } else {
            $nameUser = strip_tags($_POST['name']);
        }
        
        if (empty($_POST['adress'])) {
            $error = 1;
        } else {
            $adressUser = strip_tags($_POST['adress']);
        }

        $commUser = strip_tags($_POST['comm']);
        
        $headers = 'From: <alinvs09@gmail.com>' . "\r\n";
        $headers .= 'Cc: alinvs09@gmail.com' . "\r\n";
        
        if ($error == 0) {
            $message = "<html><body><table>";
            $message .= "<tr><td>Name: " . $nameUser . "</td></tr><tr><td>" . "Adress: " . $adressUser . "</td></tr><tr><td>" . "Comments: " . $commUser . "</td></tr>";    
            $message .= "<tr><th>Products</th><th>Title</th><th>Description</th><th>Price</th></tr>";    
            while ($product = mysqli_fetch_assoc($result)) {
                $message .= "<tr><td><img src=\"". glob("Images/" . $product['id'] . ".*")[0] ."\" style='width: 100px; height:90px;'></td><td>" . $product["title"] . "</td><td>" . $product["description"] . "</td><td>" . $product["price"] . "</td><tr>";
            }
            mysqli_data_seek($result, 0);
            $message .= "</table></body></html>";
            mail(ADMIN_EMAIL, "New order", $message, $headers); 
            unset($_POST);
        }     
    }   
?>
<html>
<head>

</head>
<body>
<table>
    <tr>
        <th></th>
        <th><?= translate('Title') ?>: </th>
        <th><?= translate('Description') ?>: </th>
        <th><?= translate('Price') ?>: </th>
    </tr>
    <?php if (count($_SESSION['cart'])) : ?>
        <?php while ($product = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><img src="<?= glob("Images/" . $product['id'] . ".*")[0]; ?>" style="width: 100px; height:90px;"></td>
            <td><?= $product["title"]?></td>
            <td><?= $product["description"]?></td>
            <td><?= $product["price"]?></td>
            <td><a href="?delId=<?= $product['id'] ?>"><?= translate('Remove') ?></a></td>   
        </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>
</br>
<form method="POST" action="cart.php">
    <input type="text" name="name" placeholder= <?= translate('Name') ?>  value="<?= !isset($_POST['name']) ?  "" : strip_tags($_POST['name']) ?>"></br>
    <input type="text" name="adress" placeholder= <?= translate('Contact details') ?> value="<?= !isset($_POST['adress']) ?  "" : strip_tags($_POST['adress']) ?>" ></br>
    <input type="text" name="comm" placeholder= <?= translate('Comments') ?> value="<?= !isset($_POST['comm']) ?  "" : strip_tags($_POST['comm']) ?>"></br>
    <input type="submit" name="send" value="<?= translate('Checkout') ?>" >
</form>
<a href = "index.php"><?= translate('Go To Index') ?></a>

</body>
</html>