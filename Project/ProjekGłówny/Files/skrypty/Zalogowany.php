<?php
// Zalogowany.php
session_start(); // Uruchomienie sesji, aby zidentyfikować użytkownika

require_once 'klasy/Baza.php';

// Połączenie z bazą danych
$db = new Baza("localhost", "root", "", "priject");

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    die("Nie jesteś zalogowany!");
}

$user_id = $_SESSION['user_id'];

// Pobieranie danych użytkownika z bazy
$user = $db->selectOne("SELECT * FROM users WHERE id = ?", "i", [$user_id]);
if (!$user) {
    die("Nie znaleziono użytkownika!");
}

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        $new_email = $_POST['email'] ?? '';
        if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $db->insert("UPDATE users SET email = ? WHERE id = ?", "si", [$new_email, $user_id]);
            echo "Email został zaktualizowany.";
        } else {
            echo "Podano nieprawidłowy adres e-mail.";
        }
    } elseif (isset($_POST['delete'])) {
        $db->insert("DELETE FROM users WHERE id = ?", "i", [$user_id]);
        session_destroy();
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header("Location: ?strona=Logowanie");
        exit;
    }
}

$zawartosc = "
<body>
<article>
    <section>
        <div id='zielone1'>Witaj, ". htmlspecialchars($user['login']) ."!</div>
        <form method='post'>
        <br>
            <div id='mbiale'>Co chciałbyś zrobić?</div>
            <div id='zielone'>Edytuj swoje dane:</div>
            <br>
            <label for='email'>Nowy adres e-mail:</label>

            <input type='email' name='email' id='email' value='". htmlspecialchars($user['email']) ."' required>
            <br>
            <button type='submit' name='edit'>Zapisz zmiany</button>
            <br><br>
            <h3>Usuń swoje konto</h3>
            <button type='submit' name='delete' onclick='return confirm('Czy na pewno chcesz usunąć swoje konto?')'>Usuń konto</button>
            <br><br>
            <button type='submit' name='logout'>Wyloguj się</button>
            <br>
            <br>
            <br>
            <a href='?strona=Zapisy' class='highlight-link'>Zapisz się na  nasze targi!</a>
            <br>
            <br>
            <a href='http://localhost:8000/' class='highlight-link'>Przyłącz się do naszej społeczności!</a>
        </form>

            <img src='Zdjęcia/baner-główny.jpg' alt='targi' class='targi'>  
    </section>
</article>
</body>
";

// Zamknięcie połączenia z bazą
$db->close();
?>
