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
	
	
	$sql =
	"SELECT
	ksiazki.*, autorzy.imie, autorzy.nazwisko, kategorie.nazwa
	FROM autorzy
	INNER JOIN ksiazki ON ksiazki.id_autora = autorzy.id
	INNER JOIN kategorie ON ksiazki.id_kategorii = kategorie.id";
	
	
	
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
		$sql = 
		"SELECT ksiazki.*, autorzy.imie, autorzy.nazwisko
		FROM autorzy
		INNER JOIN ksiazki ON ksiazki.id_autora = autorzy.id
		ORDER BY RAND() LIMIT 5";
		
		return $this->db->pobierzWszystko($sql);
	
	}



}
