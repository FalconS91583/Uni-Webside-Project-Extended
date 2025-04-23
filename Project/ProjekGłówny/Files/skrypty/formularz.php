<?php
include_once "klasy/Baza.php";
session_start();

// Zabezpieczenie sesji
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = null;
    $_SESSION['logged_in'] = false;
}

// Konfiguracja bazy danych
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'priject';
$baza = new Baza($host, $user, $pass, $db);

// Funkcja generująca formularz dodawania
function drukuj_form_dodaj($errors = []) {
    $errorMessages = '';
    if (!empty($errors)) {
        $errorMessages = '<div class="error-messages" style="color: red; font-weight: bold;">';
        foreach ($errors as $error) {
            $errorMessages .= "<p style='margin: 0; padding: 5px;'>$error</p>";
        }
        $errorMessages .= '</div>';
    }


    return "
    <main>
        <article>
            <div class='kontener-obreczy'>
                <div class='obrecz zielona' id='zielona'></div>
                <div class='obrecz fioletowa' id='fioletowa'></div>
            </div>
            <div class='tile'>
                <a href='index.php'><img src='Zdjęcia/Beznazwy2.png' alt='logo' id='logo'></a>
                <h1>Zarejestruj się</h1>
                <img src='Zdjęcia/Beztytułu2.png' alt='tło' id='tlo'>
                $errorMessages
                <form id='registration-form' method='POST'>
                    <div class='form-group'>
                        <label for='firstName'>Imię:</label>
                        <input type='text' id='firstName' name='firstName' required pattern='^[a-zA-ZąęćłńóśźżĄĘĆŁŃÓŚŹŻ]{2,50}$' title='Imię powinno zawierać od 2 do 50 liter.'>
                    </div>
                    <div class='form-group'>
                        <label for='lastName'>Nazwisko:</label>
                        <input type='text' id='lastName' name='lastName' required pattern='^[a-zA-ZąęćłńóśźżĄĘĆŁŃÓŚŹŻ]{2,50}$' title='Nazwisko powinno zawierać od 2 do 50 liter.'>
                    </div>
                    <div class='form-group'>
                        <label for='login'>Login:</label>
                        <input type='text' id='login' name='login' required pattern='^[a-zA-Z0-9]{5,20}$' title='Login powinien zawierać od 5 do 20 znaków alfanumerycznych.'>
                    </div>
                    <div class='form-group'>
                        <label for='email'>Email:</label>
                        <input type='email' id='email' name='email' required>
                    </div>
                    <div class='form-group'>
                        <label for='password'>Hasło:</label>
                        <input type='password' id='password' name='password' required minlength='8' title='Hasło musi zawierać co najmniej 8 znaków.'>
                    </div>
                    <div class='form-group'>
                        <label for='confirmPassword'>Potwierdź hasło:</label>
                        <input type='password' id='confirmPassword' name='confirmPassword' required title='Hasła muszą być takie same.'>
                    </div>
                    <div class='form-group'>
                        <label for='birthdate'>Data urodzenia:</label>
                        <input type='date' id='birthdate' name='birthdate' required>
                    </div>
                    <div class='form-group'>
                        <label for='gender'>Płeć:</label>
                        <select id='gender' name='gender' required>
                            <option value='' disabled selected>Wybierz</option>
                            <option value='mężczyzna'>Mężczyzna</option>
                            <option value='kobieta'>Kobieta</option>
                            <option value='helikopter bojowy'>Helikopter Bojowy</option>
                        </select>
                    </div>
                    <div class='form-group'>
                        <label for='address'>Adres:</label>
                        <input type='text' id='address' name='address' required pattern='^[a-zA-ZąęćłńóśźżĄĘĆŁŃÓŚŹŻ]{2,50}\s?\d{1,3}\/\s?\d{1,3}$' title='Adres powinien być w formacie Nazwa 23-123.'>
                    </div>
                    <div class='form-group'>
                        <label for='city'>Miasto:</label>
                        <input type='text' id='city' name='city' required pattern='^[a-zA-ZąęćłńóśźżĄĘĆŁŃÓŚŹŻ]{2,50}$' title='Miasto powinno zawierać od 2 do 50 liter.'>
                    </div>
                    <div class='form-group'>
                        <label for='postalCode'>Kod pocztowy:</label>
                        <input type='text' id='postalCode' name='postalCode' required pattern='^\d{2}-\d{3}$' title='Kod pocztowy powinien być w formacie 11-111.'>
                    </div>
                    <label>Specjalizacja:</label>
                    <div class='checkbox-container'>
                        <input type='checkbox' id='frontend' name='specialization[]' value='Front-end'>
                        <label for='frontend'>Front-End</label>
                        <input type='checkbox' id='backend' name='specialization[]' value='Back-end'>
                        <label for='backend'>Back-end</label>
                        <input type='checkbox' id='fullstack' name='specialization[]' value='Full-Stack'>
                        <label for='fullstack'>Full-Stack</label>
                        <input type='checkbox' id='gamedev' name='specialization[]' value='GameDev'>
                        <label for='gamedev'>GameDev</label>
                    </div>
                    <div class='form-group checkbox-container'>
                        <input type='checkbox' id='regulamin' name='regulamin' required>
                        <label for='regulamin'>Akceptuję regulamin. <a href='#'>Przeczytaj</a></label>
                    </div>
                    <div class='form-group'>
                        <button type='submit' name='submit' value='Dodaj'>Zarejestruj się</button>
                    </div>
                </form>
            </div>
        </article>
    </main>";
}

// Funkcja do hashowania haseł
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Funkcja sprawdzająca unikalność loginu
function checkLoginExists($baza, $login) {
    $sql = "SELECT COUNT(*) FROM users WHERE login = ?";
    $result = $baza->select($sql, "s", [$login]);
    return $result && $result[0]['COUNT(*)'] > 0;
}

// Funkcja sprawdzająca unikalność emaila
function checkEmailExists($baza, $email) {
    $sql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $result = $baza->select($sql, "s", [$email]);
    return $result && $result[0]['COUNT(*)'] > 0;
}

// Funkcja do rejestracji użytkownika
function registerUser($baza, $data) {
    $sql = "INSERT INTO users (imie, nazwisko, login, email, haslo, data_urodzenia, plec, adres, miasto, kod_pocztowy, specjalizacja) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    $hashedPassword = hashPassword($data['password']);
    $specialization = implode(",", $data['specialization']);

    $params = [
        $data['firstName'], $data['lastName'], $data['login'], $data['email'], 
        $hashedPassword, $data['birthdate'], $data['gender'], 
        $data['address'], $data['city'], $data['postalCode'], $specialization
    ];

    return $baza->insert($sql, "sssssssssss", $params);
}

// Obsługa formularza rejestracji
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (checkLoginExists($baza, $_POST['login'])) {
        $errors[] = "Login jest już zajęty. Proszę wybrać inny.";
    }
    if (checkEmailExists($baza, $_POST['email'])) {
        $errors[] = "Email jest już zarejestrowany. Proszę wybrać inny.";
    }
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        $errors[] = "Hasła nie pasują.";
    }
    if (empty($errors)) {
        $result = registerUser($baza, $_POST);
        if ($result) {
            echo "<script>
                alert('Rejestracja zakończona sukcesem!');
                window.location.href = '?strona=Logowanie';
            </script>";
            exit();
        } else {
            $errors[] = "Wystąpił błąd podczas rejestracji.";
        }
    }
}

echo drukuj_form_dodaj($errors);
$baza->close();
?>
