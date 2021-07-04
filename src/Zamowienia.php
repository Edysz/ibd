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

    /**
     * Pobiera wszystkie zamówienia.
     *
     * @return array
     */
    public function pobierzWszystkie(): array
    {
        $sql = "
			SELECT z.*, u.login, s.nazwa AS status,
			ROUND(SUM(sz.cena*sz.liczba_sztuk), 2) AS suma,
			COUNT(sz.id) AS liczba_produktow,
			SUM(sz.liczba_sztuk) AS liczba_sztuk
			FROM zamowienia z JOIN uzytkownicy u ON z.id_uzytkownika = u.id
			JOIN zamowienia_statusy s ON z.id_statusu = s.id
			JOIN zamowienia_szczegoly sz ON z.id = sz.id_zamowienia
			GROUP BY z.id
	    ";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera szczegóły zamówienia o podanym ID.
     * @param $id id zamówienia
     * @return array szczegóły zamówienia
     */
    public function pobierzSzczegoly($id): array
    {
        $sql = "
			SELECT *
			FROM zamowienia_szczegoly
			WHERE id_zamowienia = '" . $id . "'
			ORDER BY id_ksiazki DESC";
        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane ogólne zamówienia o podanym id
     * @param $id
     * @return array
     */
    public function pobierzZamowienie($id): array
    {
        return $this->db->pobierz('zamowienia', $id);
    }

    /**
     *
     */
    public function pobierzStatus($id): string
    {
        $result = $this->db->pobierz('zamowienia_statusy', $id);
        return $result['nazwa'];
    }

    /**
     * Zmienia status zamowienia.
     *
     * @param array $dane
     * @param int   $id
     * @return bool
     */
    public function edytuj(array $dane, int $id): bool
    {
        $update = [
            'id_statusu' => $dane['id_statusu']
             ];
        return $this->db->aktualizuj('zamowienia', $update, $id);
    }

    /**
     * Pobiera wszystkie statusy.
     *
     * @return array
     */
    public function pobierzWszystkieStatusy(): array
    {
        $sql = "SELECT * FROM zamowienia_statusy";

        return $this->db->pobierzWszystko($sql);
    }

}
