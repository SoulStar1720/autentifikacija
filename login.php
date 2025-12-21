<?php

$username = $argv[1] ?? null;

// učitaj users.json
$usersString = file_get_contents(__DIR__.'/users.json');
$usersData = json_decode($usersString, true);

if (!is_array($usersData) || !isset($usersData[$username])) {
    echo "Username or password incorrect.\n";
    exit;
}

// unos lozinke (VIDLJIV na Windowsu)
echo "Password: ";
$password = trim(fgets(STDIN));

// provjera lozinke
if (!password_verify($password, $usersData[$username]["password"])) {
    echo "Username or password incorrect.\n";
    exit;
}

// ako je administrator forsirao promjenu lozinke
if ($usersData[$username]["force_password_change"] === true) {

    echo "New password: ";
    $new1 = trim(fgets(STDIN));

    echo "Repeat new password: ";
    $new2 = trim(fgets(STDIN));

    if ($new1 !== $new2) {
        echo "Password change failed.\n";
        exit;
    }

    // spremi novu lozinku
    $usersData[$username]["password"] = password_hash($new1, PASSWORD_DEFAULT);
    $usersData[$username]["force_password_change"] = false;

    file_put_contents(
        __DIR__.'/users.json',
        json_encode($usersData, JSON_PRETTY_PRINT)
    );
}

echo "Login successful.\n";
?>