<?php

require_once ('common.php');
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['idEdit'])) {
    $_SESSION['nameImg'] = $_GET['idEdit'];    
}
else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['add'])) {
    $_SESSION['nameImg'] = -1;    
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $error = 1;
    $defaultImage = true;
    
    if (empty($_POST['title'])) {
        $error = "Title empty<br>";
        //echo "da1";
    }    
    if (empty($_POST['description'])) {
        $error = "Description empty<br>";
        //echo "da2";
    }
    if (empty($_POST['price'])) {
        $error = "Price empty<br>";
        //echo "da3";
    }
    
    if ($_FILES["fileToUpload"]["size"] > 0) {
        $defaultImage = false; 
        $target_dir = "Images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $error = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $error = "File is not an image.";
                $uploadOk = 0;
            }
        }
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $error = "Too large.";
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $error = "Extension not allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            $error = "Your file was not uploaded.";

        } 
    }
    else {
        $defaultImage = true;
    }
    
    
    //echo "0";
    if ($error != 1) {
        echo $error;
    }
    else {
        $title = strip_tags($_POST['title']);
        $description = strip_tags($_POST['description']);
        $price = strip_tags($_POST['price']);
        //echo "1";
        if ($_SESSION['nameImg'] == -1) {
            //echo "2";
            $add = "INSERT INTO products (title, description, price) VALUES (?, ?, ?)";
            $addSQL = mysqli_prepare($conn, $add);
            mysqli_stmt_bind_param($addSQL, 'ssd', $title, $description, $price);
            mysqli_stmt_execute($addSQL);
            mysqli_stmt_close($addSQL);
            echo "New product added! <br>";
        }
        else {
            //echo "3";
            $id = $_SESSION['nameImg'];
            $update = "UPDATE products SET title=? , description=? , price = ? WHERE id = '$id'";
            $updateSQL = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($addSQL, 'ssd', $title, $description, $price);
            mysqli_stmt_execute($updateSQL);
            mysqli_stmt_close($updateSQL);
            echo "The product was edited! <br>";
                
        }
        
        $allProducts = "SELECT * FROM products ORDER BY id DESC LIMIT 1";
        $products = mysqli_query($conn, $allProducts);
        if ($product = mysqli_fetch_assoc($products)) {
            $id = $product['id'];
        }
        if ($defaultImage == false) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir . "" . $id . "." . $imageFileType)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";    
            }
        }
        else if ($defaultImage == true) {    
            $file = "Images/define.jpg";
            $fileToCopy = "Images/" . $id . ".jpg";
            if (!copy($file,$fileToCopy)) {
                echo "Failed image upload.";
            }        
        }
        
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
<a href="products.php?con=admin_connected">Products</a>
</body>

</html>