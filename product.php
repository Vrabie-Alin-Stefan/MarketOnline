<?php

require_once ('common.php');

if (isset($_GET['idEdit'])) {
    $_SESSION['nameImg'] = $_GET['idEdit'];    
}
else if (isset($_GET['add'])) {
    $_SESSION['nameImg'] = -1;    
}

if (isset($_POST['save'])) {
    $error = 0;
    
    if (empty($_POST['title'])) {
        $error = 1;
    }    
    if (empty($_POST['description'])) {
        $error = 1;
    }
    if (empty($_POST['price'])) {
        $error = 1;
    }
    
    if ($_FILES["fileToUpload"]["size"] > 0) {
        $target_dir = "Images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $error = 1;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $error = 1;
        }
    }
    else {
        $error = 1;
    }
    
    if ($error == 0) {
        $title = strip_tags($_POST['title']);
        $description = strip_tags($_POST['description']);
        $price = strip_tags($_POST['price']);
        if ($_SESSION['nameImg'] == -1) {
            $add = "INSERT INTO products (title, description, price) VALUES (?, ?, ?)";
            $addSQL = mysqli_prepare($conn, $add);
            mysqli_stmt_bind_param($addSQL, 'ssd', $title, $description, $price);
            mysqli_stmt_execute($addSQL);
            mysqli_stmt_close($addSQL);
        }
        else {
            $id = $_SESSION['nameImg'];
            $update = "UPDATE products SET title=? , description=? , price = ? WHERE id = '$id'";
            $updateSQL = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($updateSQL, 'ssd', $title, $description, $price);
            mysqli_stmt_execute($updateSQL);
            mysqli_stmt_close($updateSQL); 
        }
        
        $allProducts = "SELECT * FROM products ORDER BY id DESC LIMIT 1";
        $products = mysqli_query($conn, $allProducts);
        if ($product = mysqli_fetch_assoc($products)) {
            $id = $product['id'];
        }
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . "" . $id . "." . $imageFileType);
            
    }
}

?>

<html>
<head>

</head>
<body>
<?php if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['title']) && isset($_GET['description']) && isset($_GET['price'])) : ?>
<form method="POST" action="product.php" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="<?= translate('title') ?>" value="<?= $_GET['title']; ?>"></br>
    <input type="text" name="description" placeholder="<?= translate('description') ?>" value="<?= $_GET['description']; ?>"></br>
    <input type="text" name="price" placeholder="<?= translate('price') ?>" value="<?= $_GET['price']; ?>"></br>
    <input type="file" name="fileToUpload" id="fileToUpload"></br>
    <input type="submit" name="save" value="<?= translate('Save') ?>">
</form>
<?php else: ?>
<form method="POST" action="product.php" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="<?= translate('title') ?>"></br>
    <input type="text" name="description" placeholder="<?= translate('description') ?>"></br>
    <input type="text" name="price" placeholder="<?= translate('price') ?>"></br>
    <input type="file" name="fileToUpload" id="fileToUpload"></br>
    <input type="submit" name="save" value="<?= translate('Save') ?>">
</form>
<?php endif; ?>
<a href="products.php"><?= translate('Products') ?></a>
</body>

</html>