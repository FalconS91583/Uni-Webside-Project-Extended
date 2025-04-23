<?php
require_once 'klasy/Baza.php'; // Upewnij się, że klasa Baza jest zaimportowana

// Konfiguracja połączenia z bazą danych
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'priject'; // Zamień na nazwę swojej bazy danych

$baza = new Baza($host, $user, $pass, $db);

// Zmienne na potrzeby formularza i błędów
$email = '';
$password = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Walidacja pól formularza
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        // Pobierz użytkownika na podstawie email
        $sql = "SELECT id, email, haslo FROM users WHERE email = ?";
        $user = $baza->selectOne($sql, 's', [$email]);

        if ($user) {
            // Sprawdzenie hasła
            if (password_verify($password, $user['haslo'])) {
                // Zalogowano poprawnie
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                echo "<script>
                alert('Rejestracja zakończona sukcesem!');
                window.location.href = '?strona=Zalogowany';
                </script>";
                exit;
            } else {
                $error = "Nieprawidłowe hasło.";
            }
        } else {
            $error = "Nie znaleziono użytkownika o podanym adresie e-mail.";
        }
    } else {
        $error = "Proszę wprowadzić prawidłowy adres e-mail i hasło.";
    }
}

//$tytul = "Logowanie";
$zawartosc = '
<body>
<main>
<article>
<div class="kontener-obreczy">
<div class="obrecz zielona" id="zielona"></div>
<div class="obrecz fioletowa" id="fioletowa"></div>
</div>
<div class="tile">
<a href="index.php"><img src="Zdjęcia/Beznazwy2.png" alt="logo" id="logo"></a>
<h1>Zaloguj się na swoje konto</h1>
<img src="Zdjęcia/Beztytułu2.png" alt="tło" id="tlo">
<form id="registration-form" method="POST" action="">
<div class="form-group">
<label for="email">Email:</label>
<input type="email" id="email" name="email" value="' . htmlspecialchars($email) . '" required>
<div id="info2"></div>
</div>
<div class="form-group">
<label for="password">Hasło:</label>
<input type="password" id="password" name="password" value="' . htmlspecialchars($password) . '" required>
<div id="password-error" class="error-message">' . htmlspecialchars($error) . '</div>
</div>
<div class="form-group">
<button type="submit">Zaloguj się</button>
</div>
<div class="black-bar"></div>
<div class="loga">
<img src="Zdjęcia/logo1.png" alt="Twitch">
<img src="Zdjęcia/logo2.png" alt="YT">
<img src="Zdjęcia/logo3.png" alt="X">
</div>
<h4>Nie masz konta? <a href="formularz.php">Zarejestruj się</a></h4>
</form>
</div>
</article>
</main>
</body>
';

?>
