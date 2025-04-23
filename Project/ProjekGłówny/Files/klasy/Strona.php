<?php
class Strona {
    // Własności klasy
    protected $zawartosc;
    //protected $tytul = "Modułowy serwis PHP";
    protected $slowa_kluczowe = "narzędzia internetowe, php, formularz, galeria";
    protected $przyciski = array(
        "Galeria" => "?strona=galeria",
        "Formularz" => "?strona=formularz",
    );

    // Metody modyfikujące zawartość strony
    public function ustaw_zawartosc($nowa_zawartosc) {
        $this->zawartosc = $nowa_zawartosc;
    }

    public function ustaw_tytul($nowy_tytul) {
        $this->tytul = $nowy_tytul;
    }

    public function ustaw_slowa_kluczowe($nowe_slowa) {
        $this->slowa_kluczowe = $nowe_slowa;
    }

    public function ustaw_przyciski($nowe_przyciski) {
        $this->przyciski = $nowe_przyciski;
    }

    public function ustaw_style($url) {
        echo '<link rel="stylesheet" href="' . $url . '" type="text/css"/>';
    }

    // Metody wyświetlające stronę
    public function wyswietl() {
        // Generowanie nagłówka z personalizacją dla różnych stron
        if ($this->czy_formularz()) {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/StyleF.css">');
        } elseif ($this->czy_galeria()) {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/StyleG.css">
                <link rel="stylesheet" href="react-app/static/css/main.2221a30d.css">');
        } elseif ($this->czy_Logowanie()) {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/StyleFZ.css">');
        } elseif ($this->czy_Zalogowany()) {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/StyleZ.css">');
        } elseif ($this->czy_zapisy()) {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/StyleZapisy.css">');
        } 
        else {
            $this->wyswietl_naglowek('<link rel="stylesheet" href="css/Style.css">');
        }

        $this->wyswietl_zawartosc();

        // Jeśli to nie jest formularz ani galeria, wyświetlamy stopkę i menu
        if (!$this->czy_formularz() && !$this->czy_galeria() && !$this->czy_Logowanie()) {
            $this->wyswietl_menu();
            $this->wyswietl_stopke();
        }
    }

    public function czy_formularz() {
        return isset($_GET['strona']) && $_GET['strona'] === 'formularz';
    }

    public function czy_galeria() {
        return isset($_GET['strona']) && $_GET['strona'] === 'galeria';
    }
    public function czy_Logowanie() {
        return isset($_GET['strona']) && strtolower($_GET['strona']) === 'logowanie';
    }
    public function czy_Zalogowany() {
        return isset($_GET['strona']) && strtolower($_GET['strona']) === 'zalogowany';
    }
    public function czy_zapisy(){
        return isset($_GET['strona']) && strtolower($_GET['strona']) === 'zapisy';
    }
    public function wyswietl_tytul() {
        echo "<title>$this->tytul</title>";
    }

    public function wyswietl_slowa_kluczowe() {
        echo "<meta name=\"keywords\" content=\"$this->slowa_kluczowe\">";
    }

    public function wyswietl_menu() {
        echo '<header>
<div class="sticky-header">
<h1>
<img src="Zdjęcia/Beznazwy2.png" alt="logo" class="logo">
</h1>
<nav>
<a href="#Home" class="nav-link active">Home</a>
<a href="#Onas" class="nav-link">O nas</a>
<a href="#Dlaczego" class="nav-link">Dlaczego MY</a>
<a href="#Kontakt" class="nav-link">Kontakt</a>
<a href="?strona=galeria">Galeria</a>
<a href="?strona=formularz">Formularz</a>
</nav>
</div>
</header>';
    }

    public function wyswietl_naglowek($dodatkowy_kod = '') {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        //$this->ustaw_style('css/Style.css');
        $this->ustaw_style('css/StyleK.css');
        //echo "<title>".$this->tytul."</title>";
        // Dodaj dodatkowy kod przekazany jako parametr
        if (!empty($dodatkowy_kod)) {
            echo $dodatkowy_kod;
        }
        ?>
        </head>
        <body>
        <?php
    }

    public function wyswietl_zawartosc() {
        echo "<div id='tresc'>";

        // Menu wyświetlane tylko jeśli to nie formularz ani galeria
        if (!$this->czy_formularz() && !$this->czy_galeria() && !$this->czy_Logowanie()) {
            $this->wyswietl_menu();
        }
        echo "</div></div>";
       // echo "<div id='main'><h1>".$this->tytul."</h1>";
        echo $this->zawartosc . "</div>";
    }

    public function wyswietl_stopke() {
        echo '<footer id="Kontakt">
<div class="footer-text-and-image">
<p>&copy;2025 Kacper Sokół</p>
<img src="Zdjęcia/Beznazwy2.png" alt="Logo" class="footer-image">
<p>Kacper@mejl.com </p>
<p id="Ztekst">Falcon Nest SP.z.o.o</p>
</div>
<ul class="menu">
<li class="menu__item"><a class="menu__link" href="#Home">Home</a></li>
<li class="menu__item"><a class="menu__link" href="#Onas">O nas</a></li>
<li class="menu__item"><a class="menu__link" href="#Dlaczego">Dlaczego</a></li>
<li class="menu__item"><a class="menu__link" href="#">Aktualnosci</a></li>
<li class="menu__item"><a class="menu__link" href="#Kontakt">Kontakt</a></li>
</ul>
<ul class="social-icon">
<li class="social-icon__item"><a class="social-icon__link" href="#">
<ion-icon name="logo-facebook"></ion-icon>
</a></li>
<li class="social-icon__item"><a class="social-icon__link" href="#">
<ion-icon name="logo-twitter"></ion-icon>
</a></li>
<li class="social-icon__item"><a class="social-icon__link" href="#">
<ion-icon name="logo-linkedin"></ion-icon>
</a></li>
<li class="social-icon__item"><a class="social-icon__link" href="#">
<ion-icon name="logo-instagram"></ion-icon>
</a></li>
</ul>
</footer>
<script type="module"
src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>';
        echo '</body></html>';
    }
}
?>
