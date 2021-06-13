<?php
include 'header.php';

use Ibd\Ksiazki;
use Ibd\Kategorie;
use Ibd\Stronicowanie;

$zamowienia = new \Ibd\Zamowienia();

$lista = $zamowienia->pobierzZamowienia();
?>

    <h1>Zamowienia</h1>

    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Id zamowienia</th>
            <th>Data</th>
            <th>Status</th>
            <th>Ksiazka</th>
            <th>Ilosc</th>
            <th>Cena</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lista as $zamowienie) : ?>
            <tr>
                <td><?= $zamowienie['id_zamowienia'] ?></td>
                <td><?= $zamowienie['data_dodania'] ?></td>
                <td><?= $zamowienie['nazwa'] ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php foreach ($zamowienie['szczegoly'] as $szczegol) : ?>
                <td></td>
                <td></td>
                <td></td>
                <td><?=$szczegol['tytul']?></td>
                <td><?=$szczegol['liczba_sztuk']?></td>
                <td><?=$szczegol['cena_zamowienia_ksiazki']?></td>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php include 'footer.php'; ?>
