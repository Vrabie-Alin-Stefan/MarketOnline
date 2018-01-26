<?php

    require_once ('common.php');

    if (isset($_POST['login'])) {
        $nameUser = strip_tags($_POST['user']);
        $passUser = strip_tags($_POST['pass']);

        if ($nameUser === ADMIN_NAME && $passUser === ADMIN_PASS) {
            header("Location: products.php?con=admin_connected");
            die();
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
    <input type="submit" name="login" value="<?= translate('Login') ?>">
</form>
</body>

</html>