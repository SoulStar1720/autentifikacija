<?php
    $usersString = file_get_contents(__DIR__.'/users.json');
    $usersData = json_decode($usersString, true);

    //ubacivanje u users.json
    $users = array('username' => $_POST['username'], 'lozinka' => $_POST['lozinka']);
    if (isset($usersData))
    {
        $usersData[] = $users;
    }
    else
    {
        $usersData = array($users);
    }

    $newString = json_encode($usersData);
    file_put_contents(__DIR__.'/users.json', $newString);

    header("Location: http://localhost/php_LS/autentikacija_LS_10_12/login.php");
    die();
?>