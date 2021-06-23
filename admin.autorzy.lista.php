<?php

require_once 'vendor/autoload.php';

use Ibd\Autorzy;

$autorzy = new Autorzy();
$zapytanie = $autorzy->pobierzSelect($_GET);

// dodawanie warunków stronicowania i generowanie linków do stron
$stronicowanie = new \Ibd\Stronicowanie($_GET, $zapytanie['parametry']);
$podsumowanieListy = $stronicowanie->pobierzPodsumowanie($zapytanie['sql']);
$linki = $stronicowanie->pobierzLinki($zapytanie['sql'], 'admin.autorzy.lista.php');
$select = $stronicowanie->dodajLimit($zapytanie['sql']);

$lista = $autorzy->pobierzWszystko($select, $zapytanie['parametry']);

include 'admin.header.php';
?>

<h2>
    Autorzy
    <small><a href="admin.autorzy.dodaj.php">dodaj</a></small>
</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
    <p class="alert alert-success">Autor został dodany.</p>
<?php endif; ?>
    <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="szukaj" placeholder="szukaj" class="form-control form-control-sm mr-2"
               value="<?= $_GET['szukaj'] ?? '' ?>" autocomplete="off"/>

        <select name="sortowanie" id="sortowanie" class="form-control form-control-sm mr-2">
            <option value="">sortowanie</option>
            <option value="a.nazwisko ASC"
                <?= ($_GET['sortowanie'] ?? '') == 'a.nazwisko ASC' ? 'selected' : '' ?>
            >Nazwiksu rosnąco
            </option>
            <option value="a.nazwisko DESC"
                <?= ($_GET['sortowanie'] ?? '') == 'a.nazwisko DESC' ? 'selected' : '' ?>
            >Nazwisku malejąco
            </option>
        </select>

        <button class="btn btn-sm btn-primary" type="submit">Szukaj</button>
    </form>



<table id="autorzy" class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Liczba książek</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista as $a): ?>
            <tr>
                <td><?= $a['id'] ?></td>
                <td><?= $a['imie'] ?></td>
                <td><?= $a['nazwisko'] ?></td>
                <td><?= $a['liczba'] ?></td>
                <td>
                    <a href="admin.autorzy.edycja.php?id=<?= $a['id'] ?>" title="edycja" class="aEdytujAutora"><em class="fas fa-pencil-alt"></em></a>
                    <?php if($a['liczba'] == 0): ?>
                    <a href="admin.autorzy.usun.php?id=<?= $a['id'] ?>" title="usuń" class="aUsunAutora"><em class="fas fa-trash"></em></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="p-2">
    <?= $podsumowanieListy ?>
</div>
<nav class="text-center">
    <?= $linki ?>
</nav>


<?php include 'admin.footer.php'; ?>
