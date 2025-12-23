<?php

$command  = $argv[1] ?? null;
$username = $argv[2] ?? null;

// učitaj users.json
$usersString = file_get_contents(__DIR__.'/users.json');
$usersData = json_decode($usersString, true);

if (!is_array($usersData)) {
    $usersData = [];
}
/* ===================== ADD USER ===================== */
if ($command === "add") {

    if (isset($usersData[$username])) {
        echo "User already exists.\n";
        exit;
    }

    echo "Password: ";
    $pass1 = trim(fgets(STDIN));//STDIN = standardni ulaz → ono što korisnik tipka u terminalu, fgets(STDIN) = čita jedan redak koji je korisnik upisao,trim() = uklanja ENTER (novi red) i praznine s početka i kraja
    echo "\n";

    echo "Repeat Password: ";
    $pass2 = trim(fgets(STDIN));
    echo "\n";

    if ($pass1 !== $pass2) {
        echo "User add failed. Password mismatch.\n";
        exit;
    }

    $usersData[$username] = [
        "password" => password_hash($pass1, PASSWORD_DEFAULT),
        "force_password_change" => true //mora biti true ako želimo da radi forsiranje promjene lozinke
    ];

    echo "User successfully added.\n";
}
/* ===================== CHANGE PASSWORD ===================== */
elseif ($command === "passwd") {

    if (!isset($usersData[$username])) {
        echo "User does not exist.\n";
        exit;
    }

    echo "Password: ";
    $pass1 = trim(fgets(STDIN));
    echo "\n";

    echo "Repeat Password: ";
    $pass2 = trim(fgets(STDIN));
    echo "\n";

    if ($pass1 !== $pass2) {
        echo "Password change failed. Password mismatch.\n";
        exit;
    }

    $usersData[$username]["password"] = password_hash($pass1, PASSWORD_DEFAULT);
    $usersData[$username]["force_password_change"] = false;

    echo "Password change successful.\n";
}
/* ===================== FORCE PASSWORD CHANGE ===================== */
elseif ($command === "forcepass") {

    if (!isset($usersData[$username])) {
        echo "User does not exist.\n";
        exit;
    }

    $usersData[$username]["force_password_change"] = true;

    echo "User will be requested to change password on next login.\n";
}
/* ===================== DELETE USER ===================== */
elseif ($command === "del") {

    if (!isset($usersData[$username])) {
        echo "User does not exist.\n";
        exit;
    }

    unset($usersData[$username]);

    echo "User successfully removed.\n";
}
else {
    echo "Unknown command.\n";
    exit;
}
// spremi natrag u users.json
file_put_contents(
    __DIR__.'/users.json',
    json_encode($usersData, JSON_PRETTY_PRINT)
);
?>