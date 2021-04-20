<?php

// jesli nie podano parametru id, przekieruj do listy książek
if(empty($_GET['id'])) {
    header("Location: ksiazki.lista.php");
    exit();
}

$id = (int)$_GET['id'];

include 'header.php';

use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$dane = $ksiazki->pobierz($id);

?>

<h2><?=$dane['tytul']?></h2>

<p>
	<a href="ksiazki.lista.php"><i class="fas fa-chevron-left"></i> Powrót</a>
</p>


<p> 
<b>Tytuł</b>: <?= $dane['tytul']?>
<br>
<b>Opis</b>: <?= $dane['opis']?>
<br>
<b>Liczba Stron</b>: <?= $dane['liczba_stron']?>
<br>
<b>Cena</b>: <?= $dane['cena']?>
<br>
<b>ISBN</b>: <?= $dane['isbn']?>
<br>
</p>
				
<p> 

<img src="zdjecia/<?= $dane['zdjecie']?>" style="width: 300px"/> 

</p>
					
						

<?php include 'footer.php'; ?>