<?php
session_start();

require_once 'klasy/Baza.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    die("Musisz być zalogowany, aby przeglądać swoje targi.");
}

$db = new Baza("localhost", "root", "", "priject");

$user_id = $_SESSION['user_id'];
$user = $db->selectOne("SELECT * FROM users WHERE id = ?", "i", [$user_id]);

if (!$user) {
    die("Nie znaleziono użytkownika!");
}

// Obsługa formularza zapisu i usuwania uczestnictwa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fair_id'])) {
        $fair_id = (int)$_POST['fair_id'];

        // Dodanie uczestnictwa
        $exists = $db->selectOne("SELECT * FROM fair_participants WHERE fair_id = ? AND user_id = ?", "ii", [$fair_id, $user_id]);
        if ($exists) {
            echo "Jesteś już zapisany na te targi.";
        } else {
            $db->insert("INSERT INTO fair_participants (fair_id, user_id) VALUES (?, ?)", "ii", [$fair_id, $user_id]);
            echo "Zapisano na targi.";
        }
    } elseif (isset($_POST['remove_fair_id'])) {
        $fair_id = (int)$_POST['remove_fair_id'];

        // Usunięcie uczestnictwa
        $db->insert("DELETE FROM fair_participants WHERE fair_id = ? AND user_id = ?", "ii", [$fair_id, $user_id]);
        echo "Usunięto uczestnictwo w targach.";
    } elseif (isset($_POST['add_fair'])) {
        // Dodawanie nowego targu przez admina
        if ($user['login'] === 'adminSokol') {
            $fair_name = $_POST['fair_name'] ?? '';
            $fair_description = $_POST['fair_description'] ?? '';
            $fair_date = $_POST['fair_date'] ?? '';

            if (!empty($fair_name) && !empty($fair_date)) {
                $db->insert("INSERT INTO fairs (name, description, date) VALUES (?, ?, ?)", "sss", [$fair_name, $fair_description, $fair_date]);
                echo "Nowy targ został dodany.";
            } else {
                echo "Wszystkie pola muszą być wypełnione.";
            }
        } else {
            echo "Nie masz uprawnień do dodawania targów.";
        }
    }
}

// Pobieranie dostępnych targów
$fairs = $db->select("SELECT * FROM fairs");

// Pobieranie targów, na które użytkownik jest zapisany
$user_fairs = $db->select("
    SELECT fairs.id, fairs.name, fairs.description, fairs.date 
    FROM fairs 
    JOIN fair_participants ON fairs.id = fair_participants.fair_id 
    WHERE fair_participants.user_id = ?", "i", [$user_id]);

// Generowanie listy dostępnych targów
$content = "<div id='zielone'> Dostępne targi</div><ul>";
foreach ($fairs as $fair) {
    // Pobierz uczestników dla każdego targu
    $participants = $db->select("SELECT users.login FROM fair_participants JOIN users ON fair_participants.user_id = users.id WHERE fair_participants.fair_id = ?", "i", [$fair['id']]);

    $participant_list = "<ul>";
    foreach ($participants as $participant) {
        $participant_list .= "<li>" . htmlspecialchars($participant['login']) . "</li>";
    }
    $participant_list .= "</ul>";

    $content .= "
    <div id='sekcjaczarna'>
    <li>
        <strong>" . htmlspecialchars($fair['name']) . "</strong> <div id='mbiale'> - " . htmlspecialchars($fair['description']) . " (Data: " . $fair['date'] . ") </div>
        <div id='mbiale'>
        <form method='post' style='margin-top: 10px;'>
            <input type='hidden' name='fair_id' value='" . $fair['id'] . "'>
            <button type='submit'>Zapisz się</button>
        </form>
        <h4>Uczestnicy:</h4>
        $participant_list
    </li>
    </div>
    ";
}
$content .= "</ul>";

// Generowanie listy targów użytkownika
$user_fairs_content = "<h1>Twoje targi</h1><ul>";
foreach ($user_fairs as $fair) {
    $user_fairs_content .= "
    <li>
        <strong>" . htmlspecialchars($fair['name']) . "</strong> <div id='mbiale'> - " . htmlspecialchars($fair['description']) . " (Data: " . $fair['date'] . ") </div>
        <form method='post' style='margin-top: 10px;'>
            <input type='hidden' name='remove_fair_id' value='" . $fair['id'] . "'>
            <button type='submit'>Usuń uczestnictwo</button>
        </form>
    </li>";
}
$user_fairs_content .= "</ul>";


// Sekcja dodawania nowych targów (tylko dla admina)
if ($user['login'] === 'adminSokol') {
    $content .= "
        <div id='sekcjaczarna'>
    <h2>Dodaj nowy targ</h2>
    <form method='post'>
        <label for='fair_name'>Nazwa targu:</label>
        <input type='text' name='fair_name' id='fair_name' required>
        <br>
        <label for='fair_description'>Opis targu:</label>
        <textarea name='fair_description' id='fair_description'></textarea>
        <br>
        <label for='fair_date'>Data targu:</label>
        <input type='date' name='fair_date' id='fair_date' required>
        <br>
        <button type='submit' name='add_fair'>Dodaj targ</button>
    </form>
    </div>
    ";
}

// Wyświetlenie treści strony
echo "

<body>
    <div id='sekcjaczarna'>
    <h2>Witaj, " . htmlspecialchars($user['login']) . "!</h2>
    $content
    </div>
    <div id='malasekcja'>
    <hr>

    $user_fairs_content
    <br>
    <a href='?strona=glowna' class='highlight-link'>Powrót do strony głównej</a>
    </div>
</body>
</html>
";

// Zamknięcie połączenia z bazą
$db->close();
?>
