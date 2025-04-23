<?php
require_once("klasy/Strona.php");
$strona_akt = new Strona();

// Sprawdzenie, co wybrał użytkownik
if (filter_input(INPUT_GET, 'strona')) {
    $strona = filter_input(INPUT_GET, 'strona');
    switch ($strona) {
        case 'galeria': 
        case 'formularz': 
        case 'onas': 
        case 'Logowanie':
        case 'Zalogowany':
        case 'Zapisy':
            break;
        default: 
            $strona = 'glowna';
    }
} else {
    $strona = "glowna";
}

// Dołącz wybrany plik z ustawioną zmienną $tytul i $zawartosc
$plik = "skrypty/" . $strona . ".php";
if (file_exists($plik)) {
    require_once($plik);
    //$strona_akt->ustaw_tytul($tytul ?? "Strona główna");
    $strona_akt->ustaw_zawartosc($zawartosc ?? "Brak zawartości.");
    $strona_akt->wyswietl();
} else {
    echo "Nie znaleziono pliku: $plik";
}
?>
