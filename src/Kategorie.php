<?php

namespace Ibd;

class Kategorie
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

    public function pobierz(int $id): array
    {
        return $this->db->pobierz('kategorie', $id);
    }

    public function pobierzSelect(array $params): array
    {
        $sql = "
            SELECT *
            FROM kategorie k 
            WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        $parametry = [];
        if (!empty($params['szukaj'])) {
            $czesciFrazy = [];
            foreach (['k.nazwa'] as $kolumna){
                $nazwaParametru = "param_" . (count($parametry) + 1);
                $czesciFrazy[] = "$kolumna LIKE :{$nazwaParametru}";
                $parametry[$nazwaParametru] = "%{$params['szukaj']}%";
            }
            $sqlFrazy = implode(' OR ', $czesciFrazy);
            $sql .= "AND ($sqlFrazy)";
        }

        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['k.nazwa'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
    }


    /**
     * Pobiera wszystkie kategorie.
     *
     * @return array
     */
    public function pobierzWszystkie(): array
    {
        $sql = "SELECT * FROM kategorie";

        return $this->db->pobierzWszystko($sql);
    }

    public function pobierzWszystko(string $select, array $parametry): array
    {
        return $this->db->pobierzWszystko($select, $parametry);
    }

    public function edytuj(array $dane, int $id): bool
    {
        $update = [
            'nazwa' => $dane['nazwa'],
        ];

        return $this->db->aktualizuj('kategorie', $update, $id);
    }

    public function usun($id)
    {
        $this->db->usun('kategorie', $id);
    }

}
