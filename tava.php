<?php
require("conf.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login.php');
    exit();
}

require("functions.php");
$sort = "eesnimi";
$search_term = "";
if(isset($_REQUEST["sort"])) {
    $sort = $_REQUEST["sort"];
}
if(isset($_REQUEST["search_term"])) {
    $search_term = $_REQUEST["search_term"];
}

if(isset($_REQUEST["inimese_lisamine"])) {
    addPerson($_REQUEST["eesnimi"], $_REQUEST["perekonnanimi"], $_REQUEST["maakonna_id"]);
    header("Location: tava.php");
    exit();
}
if(isset($_REQUEST["delete"])) {
    deletePerson($_REQUEST["delete"]);
}
if(isset($_REQUEST["save"])) {
    savePerson($_REQUEST["changed_id"], $_REQUEST["eesnimi"], $_REQUEST["perekonnanimi"], $_REQUEST["maakonna_id"]);
}
$people = countyData($sort, $search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Inimesed ja maakonnad</title>
</head>
<body>
<header class="header">
    <p>Kasutaja on sisse logitud</p>
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
    <div class="container">
        <h1>Tabelid | Inimesed ja maakond</h1>
    </div>
</header>
<main class="main">
    <div class="container">
        <form action="tava.php">
            <input type="text" name="search_term" placeholder="Otsi...">
        </form>
    </div>
    <?php if(isset($_REQUEST["edit"])): ?>
        <?php foreach($people as $person): ?>
            <?php if($person->id == intval($_REQUEST["edit"])): ?>
                <div class="container">
                    <form action="tava.php">
                        <input type="hidden" name="changed_id" value="<?=$person->id ?>"/>
                        <input type="text" name="eesnimi" value="<?=$person->eesnimi?>">
                        <input type="text" name="perekonnanimi" value="<?=$person->perekonnanimi?>">
                        <?php echo createSelect("SELECT id, maakonna_nimi FROM maakond", "maakonna_id"); ?>
                        <a title="Katkesta muutmine" class="cancelBtn" href="tava.php" name="cancel">X</a>
                        <input type="submit" name="save" value="&#10004;">
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th>Id</th>
                <th><a href="tava.php?sort=eesnimi">Eesnimi</a></th>
                <th><a href="tava.php?sort=perekonnanimi">Perekonnanimi</a></th>
                <th><a href="tava.php?sort=maakonna_nimi">Maakond</a></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($people as $person): ?>
                <tr>
                    <td><strong><?=$person->id ?></strong></td>
                    <td><?=$person->eesnimi ?></td>
                    <td><?=$person->perekonnanimi ?></td>
                    <td><?=$person->maakonna_nimi ?></td>
                    <td>
                        <a title="Kustuta inimene" class="deleteBtn" href="tava.php?delete=<?=$person->id?>"
                           onclick="return confirm('Oled kindel, et soovid kustutada?');">X</a>
                        <a title="Muuda inimest" class="editBtn" href="tava.php?edit=<?=$person->id?>">&#9998;</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <form action="tava.php">
            <h2>Inimese lisamine:</h2>
            <dl>
                <dt>Eesnimi:</dt>
                <dd><input type="text" name="eesnimi" placeholder="Sisesta eesnimi..."></dd>
                <dt>Perekonnanimi:</dt>
                <dd><input type="text" name="perekonnanimi" placeholder="Sisesta perekonna nimi..."></dd>
                <dt>Maakond</dt>
                <dd><?php
                    echo createSelect("SELECT id, maakonna_nimi FROM maakond", "maakonna_id");
                    ?></dd>
                <input type="submit" name="inimese_lisamine" value="Lisa inimene">
            </dl>
        </form>
    </div>
</main>
</body>
</html>

<?php
/*CREATE TABLE maakond(
id int not null PRIMARY KEY AUTO_INCREMENT,
maakonna_nimi varchar(100),
maakonna_keskus varchar(100)
);
 * CREATE TABLE inimene(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    eesnimi varchar(100),
    perekonnanimi varchar(100),
    maakonna_id int,
    FOREIGN KEY (maakonna_id) REFERENCES maakond(id)
);*/

?>