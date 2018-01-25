<?php

require_once ('common.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $nameUser = htmlspecialchars($_POST['user']);
    $nameUser = strip_tags($nameUser);
    $passUser = htmlspecialchars($_POST['pass']);
    $passUser = strip_tags($passUser);

    if ($nameUser === ADMIN_NAME && $passUser === ADMIN_PASS) {
        header("Location: products.php?con=admin_connected");
        die();
    }
    else {    
        echo "Wrong Username or Password";
    }
}

?>

<html>
<head>

</head>
<body>
<form method="POST" action="login.php">
    <input type="text" name="user" placeholder="<?= translate('User Name') ?>" value="<?= !isset($nameUser) ?  "" : $nameUser ?>"></br>
    <input type="password" name="pass" placeholder="<?= translate('Password') ?>"></br>
    <input type="submit" name="login" value="<?= translate('Login') ?>n">
</form>
</body>

</html>