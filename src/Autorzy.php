<?php

namespace Ibd;

class Autorzy
{
	private Db $db;

	public function __construct()
	{
		$this->db = new Db();
	}

	/**
	 * Pobiera zapytanie SELECT z autorami.
	 *
	 * @return string
     */
	public function pobierzSelect(array $params): array
    {
        $sql = "
            SELECT a.id, a.imie, a.nazwisko, COUNT(*) as liczba
            FROM autorzy a  
                LEFT JOIN ksiazki k on a.id = k.id_autora
            WHERE 1=1 ";

        // dodawanie warunków do zapytanie
        $parametry = [];
        if (!empty($params['szukaj'])) {
            $czesciFrazy = [];
            foreach (['a.nazwisko', 'a.imie'] as $kolumna){
                $nazwaParametru = "param_" . (count($parametry) + 1);
                $czesciFrazy[] = "$kolumna LIKE :{$nazwaParametru}";
                $parametry[$nazwaParametru] = "%{$params['szukaj']}%";
            }
            $sqlFrazy = implode(' OR ', $czesciFrazy);
            $sql .= "AND ($sqlFrazy)";
        }

        $sql .= 'GROUP BY 1,2,3';
        // dodawanie sortowania
        if (!empty($params['sortowanie'])) {
            $kolumny = ['a.nazwisko'];
            $kierunki = ['ASC', 'DESC'];
            [$kolumna, $kierunek] = explode(' ', $params['sortowanie']);

            if (in_array($kolumna, $kolumny) && in_array($kierunek, $kierunki)) {
                $sql .= " ORDER BY " . $params['sortowanie'];
            }
        }

        return ['sql' => $sql, 'parametry' => $parametry];
	}

	/**
	 * Wykonuje podane w parametrze zapytanie SELECT.
	 * 
	 * @param string $select
	 * @return array
	 */
	public function pobierzWszystko(string $select, array $parametry): array
    {
		return $this->db->pobierzWszystko($select, $parametry);
	}

	/**
	 * Pobiera dane autora o podanym id.
	 * 
	 * @param int $id
	 * @return array
	 */
	public function pobierz(int $id): array
    {
		return $this->db->pobierz('autorzy', $id);
	}

	/**
	 * Dodaje autora.
	 *
	 * @param array $dane
	 * @return int
	 */
	public function dodaj(array $dane): int
    {
		return $this->db->dodaj('autorzy', [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		]);
	}

	/**
	 * Usuwa autora.
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function usun(int $id): bool
    {
		if(!empty($this->pobierzWszystko('SELECT * FROM ksiazki WHERE id_autora = :idAutora', ['idAutora' => $id]))){
		    return false;
        }
        return $this->db->usun('autorzy', $id);
	}

	/**
	 * Zmienia dane autora.
	 * 
	 * @param array $dane
	 * @param int   $id
	 * @return bool
	 */
	public function edytuj(array $dane, int $id): bool
    {
		$update = [
			'imie' => $dane['imie'],
			'nazwisko' => $dane['nazwisko']
		];
		
		return $this->db->aktualizuj('autorzy', $update, $id);
	}

}
