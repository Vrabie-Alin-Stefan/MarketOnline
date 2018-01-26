<?php

    require_once ('common.php');

    if ($_SESSION['admin'] == "dissconected"){
        header("Location: index.php");
        die();
    }

    $allProducts = "SELECT * FROM products";
    $products = mysqli_query($conn, $allProducts);

    if (isset($_GET['idDel'])) {   
        if (in_array($_GET['idDel'], $_SESSION['cart'])) {
            $pos = array_search($_GET['idDel'], $_SESSION['cart']);
            unset ($_SESSION['cart'][$pos]);
        }
        $myImage = glob("Images/" . $_GET['idDel'] . ".*")[0];
        unlink ($myImage);
        $delString = "DELETE FROM products WHERE id=" . $_GET['idDel'] . "";
        if (mysqli_query($conn,$delString)) {
            header("Location: products.php");
            die();
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
    <?php while ($product = mysqli_fetch_assoc($products)) : ?>
        <tr>
            <td><img src="<?= glob("Images/*" . $product['id'] . ".*")[0] ?>" style="width: 100px; height:90px;"></td>
            <td><?= $product["title"]?></td>
            <td><?= $product["description"]?></td>
            <td><?= $product["price"]?></td>
            <td><a href="product.php?idEdit=<?= $product["id"]?>&title=<?= $product["title"]?>&description=<?= $product["description"]?>&price=<?= $product["price"]?>"><?= translate('Edit') ?></a> </td>
            <td><a href="?idDel=<?= $product["id"]?>"><?= translate('Delete') ?></a> </td>
        </tr>
    <?php  endwhile; ?>

</table>

<a href="product.php?add=1"><?= translate('Add') ?></a>
<a href="index.php"><?= translate('Logout') ?></a>

</body>
</html>