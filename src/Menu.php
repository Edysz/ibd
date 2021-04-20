<?php

namespace Ibd;

/**
 * Klasa obsługująca generowanie menu.
 *
 */
class Menu
{
    /**
     * Generuje opcję listy menu.
     *
     * @param string $plik  Nazwa pliku do którego ma kierować link
     * @param string $nazwa Nazwa która ma wyświetlić się na linku
     * @return string
     */
    public static function generujOpcje(string $plik, string $nazwa): string
    {
        $biezacy = basename($_SERVER["SCRIPT_NAME"]);

        return $biezacy == $plik
            ? "<li class='nav-item active'><a href='$plik' class='nav-link'>$nazwa</a></li>"
            : "<li class='nav-item'><a href='$plik' class='nav-link'>$nazwa</a></li>";
    }
}

