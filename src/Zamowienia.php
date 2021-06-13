<?php

namespace Ibd;

class Zamowienia
{
    /**
     * Instancja klasy obsługującej połączenie do bazy.
     *
     * @var Db
     */
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Dodaje zamówienie.
     * 
     * @param int $idUzytkownika
     * @return int Id zamówienia
     */
    public function dodaj(int $idUzytkownika): int
    {
        return $this->db->dodaj('zamowienia', [
            'id_uzytkownika' => $idUzytkownika,
            'id_statusu' => 1
        ]);
    }

    /**
     * Dodaje szczegóły zamówienia.
     * 
     * @param int   $idZamowienia
     * @param array $dane Książki do zamówienia
     */
    public function dodajSzczegoly(int $idZamowienia, array $dane): void
    {
        foreach ($dane as $ksiazka) {
            $this->db->dodaj('zamowienia_szczegoly', [
                'id_zamowienia' => $idZamowienia,
                'id_ksiazki' => $ksiazka['id'],
                'cena' => $ksiazka['cena'],
                'liczba_sztuk' => $ksiazka['liczba_sztuk']
            ]);
        }
    }

    public function pobierzZamowienia(): array
    {
        $sql = "SELECT *, s.cena as cena_zamowienia_ksiazki FROM zamowienia z 
                    LEFT JOIN zamowienia_szczegoly s on z.id = s.id_zamowienia
                    LEFT JOIN ksiazki k on s.id_ksiazki = k.id
                    LEFT JOIN zamowienia_statusy st on z.id_statusu = st.id
                WHERE z.id_uzytkownika = :id_uzytkownika";

        $rekordy = $this->db->pobierzWszystko($sql, ['id_uzytkownika' => $_SESSION['id_uzytkownika']]);

        $zamowienia = [];

        foreach ($rekordy as $rekord) {
            if(!isset($zamowienia[$rekord['id_zamowienia']])){
                $zamowienia[$rekord['id_zamowienia']] = $rekord;
            }
            $zamowienia[$rekord['id_zamowienia']]['szczegoly'][] = $rekord;

        }

        return $zamowienia;
    }

}
