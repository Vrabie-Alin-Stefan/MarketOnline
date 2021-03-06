<?php

    require_once ('common.php');
    
    
    if (isset($_GET['id'])) {
        
        if (!in_array($_GET['id'], $_SESSION['cart'])) {
            $_SESSION['cart'][] = $_GET['id'];
        }
        
    }
    
    if (count($_SESSION['cart'])) {        
        $numberParams = str_repeat('?,', count($_SESSION['cart']) - 1) .'?';
        $numberType = str_repeat('d', count($_SESSION['cart']));
        
        $sql = "SELECT * FROM products WHERE id NOT IN ($numberParams)";
        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
        
        $refarg = array($stmt, $numberType);
        foreach ($_SESSION['cart'] as $key => $value) {
            $refarg[] =& $_SESSION['cart'][$key];
        }
        call_user_func_array("mysqli_stmt_bind_param", $refarg);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $allProducts = "SELECT * FROM products";
        $result = mysqli_query($conn, $allProducts);
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
    <?php while($product = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><img src="<?= glob("Images/" . $product['id'] . ".*")[0]; ?>" style="width: 100px; height:90px;"></td>
                <td><?= $product["title"]?></td>
                <td><?= $product["description"]?></td>
                <td><?= $product["price"]?></td>
                <td><a href="?id=<?= $product["id"]?>"><?= translate('Add') ?></a> </td>
            </tr> 
    <?php endwhile; ?>
        
</table>

<a href="cart.php"><?= translate('Go To Cart') ?></a>
<a href="login.php"><?= translate('Go To Login') ?></a>

</body>
</html>