<?php

require_once ('common.php');

if (!isset($_GET['con'])){
    header("Location: index.php");
    die();
}

$allProducts = "SELECT * FROM products";
$products = mysqli_query($conn, $allProducts);

$numberProducts = mysqli_num_rows($products);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['idDel'])) {    
    //actualizez si vectorul din cart
    $idProdDel = $_GET['idDel'];
    $arraySize = count($_SESSION['prodsArray']);
    for ($i=0;$i<$arraySize;$i++)
    {
        if ($_SESSION['prodsArray'][$i] == $idProdDel) {
            unset ($_SESSION['prodsArray'][$i]);
        }
    }
        
    $myImage = glob("Images/*" . $idProdDel . ".*")[0];
    unlink ($myImage);
    $delString = "DELETE FROM products WHERE id='$idProdDel'";
    if (mysqli_query($conn,$delString)) {
        echo "Succes!";
        $allProducts = "SELECT * FROM products";
        $products = mysqli_query($conn, $allProducts);
    }
    else {
        echo "Failed!";
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
            <td><img src="<?= glob("Images/*" . $product['id'] . ".*")[0]; ?>" style="width: 100px; height:90px;"></td>
            <td><?= $product["title"]?></td>
            <td><?= $product["description"]?></td>
            <td><?= $product["price"]?></td>
            <td><a href="product.php?idEdit=<?= $product["id"]?>&title=<?= $product["title"]?>&description=<?= $product["description"]?>&price=<?= $product["price"]?>"><?= translate('Edit') ?></a> </td>
            <td><a href="products.php?con=admin_connected&idDel=<?= $product["id"]?>"><?= translate('Delete') ?></a> </td>
        </tr>
    <?php  endwhile; ?>

</table>

<a href="product.php?add=1"><?= translate('Add') ?></a>
<a href="index.php?logout=1"><?= translate('Go To Login') ?></a>

</body>
</html>