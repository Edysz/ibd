<?php

namespace Ibd;

class Ksiazki
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
     * Pobiera wszystkie książki.
     *
     * @return array
     */
    public function pobierzWszystkie(): ?array
    {
		$sql = "
            SELECT k.*, a.imie, a.nazwisko, kat.nazwa
            FROM ksiazki k
                LEFT JOIN autorzy a on k.id_autora = a.id
                LEFT JOIN kategorie kat on k.id_kategorii = kat.id
        ";

        return $this->db->pobierzWszystko($sql);
    }

    /**
     * Pobiera dane książki o podanym id.
     *
     * @param int $id
     * @return array
     */
    public function pobierz(int $id): ?array
    {
        return $this->db->pobierz('ksiazki', $id);
    }

	/**
	 * Pobiera najlepiej sprzedające się książki.
	 * 
	 */
	public function pobierzBestsellery(): ?array
	{
		$sql = "
            SELECT k.*, a.imie, a.nazwisko
            FROM ksiazki k
                LEFT JOIN autorzy a on k.id_autora = a.id
            ORDER BY RAND() LIMIT 5 
        ";

        return $this->db->pobierzWszystko($sql);
	}

    /**
     * Pobiera zapytanie SELECT oraz jego parametry;
     *
     * @param array $params
     * @return array
     */
    public function pobierzZapytanie(array $params = []): array
    {
        $parametry = [];
        $sql = "
            SELECT k.*, a.imie, a.nazwisko, kat.nazwa
            FROM ksiazki k
                LEFT JOIN autorzy a on k.id_autora = a.id
                LEFT JOIN kategorie kat on k.id_kategorii = kat.id 
            WHERE 1=1 
        ";

        // dodawanie warunków do zapytanie
        if (!empty($params['fraza'])) {
            $czesciFrazy = [];
            foreach (['k.tytul', 'k.opis', 'CONCAT(a.nazwisko, " ", a.imie)'] as $kolumna){
                $nazwaParametru = "param_" . (count($parametry) + 1);
                $czesciFrazy[] = "$kolumna LIKE :{$nazwaParametru}";
                $parametry[$nazwaParametru] = "%{$params['fraza']}%";
            }
            $sqlFrazy = implode(' OR ', $czesciFrazy);
            $sql .= "AND ($sqlFrazy)";

        }
        if (!empty($params['id_kategorii'])) {
            $sql .= " AND k.id_kategorii = :id_kategorii ";
            $parametry['id_kategorii'] = $params['id_kategorii'];
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['k.tytul', 'k.cena', 'a.nazwisko'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }

    /**
     * Pobiera stronę z danymi książek.
     *
     * @param string $select
     * @param array  $params
     * @return array
     */
    public function pobierzStrone(string $select, array $params = []): array
    {
        return $this->db->pobierzWszystko($select, $params);
    }
}
