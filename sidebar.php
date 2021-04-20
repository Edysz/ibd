<?php


use Ibd\Ksiazki;

$ksiazki = new Ksiazki();
$lista = $ksiazki->pobierzBestsellery();

?>
<div class="col-md-2">
	<h1>Bestsellery</h1>
	



<?php foreach ($lista as $ks) : ?>
		
			<ul>
				<li>
					<?php if (!empty($ks['zdjecie'])) : ?>
					<a href="ksiazki.szczegoly.php?id=<?= $ks['id'] ?>" title="szczegóły">
						<img src="zdjecia/<?= $ks['zdjecie'] ?>" alt="<?= $ks['tytul'] ?>" style="width: 100px" />
						</a>
					<?php else : ?>
						brak zdjęcia 
					<?php endif; ?>
					<br>
					<?= $ks['tytul'] ?>
					<br>
					<?= $ks['imie']?> <?= $ks['nazwisko']?>
				</li>
				<br><br>
				
			</ul>
		<?php endforeach; ?>
</div>