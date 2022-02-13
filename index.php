<?php
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}

if(isset($_GET['limit']) && !empty($_GET['limit'])){
    $perPage = (int) strip_tags($_GET['limit']);
}else{
    $perPage = 10;
}

if(isset($_GET['sort']) && !empty($_GET['sort'])){
    $sort = (int) strip_tags($_GET['sort']);
}else{
    $sort = 'title';
}

if(isset($_GET['direction']) && !empty($_GET['direction'])){
    $direction = (string) strip_tags($_GET['direction']);
}else{
    $direction = 'ASC';
}

require_once('connect.php');

$sql = 'SELECT COUNT(*) AS nb_movies FROM `film`;';

$query = $db->prepare($sql);

$query->execute();

$result = $query->fetch();

$nbMovies = (int) $result['nb_movies'];

$nbPages = ceil($nbMovies / $perPage);

$currentPageFirstMovie = ($currentPage * $perPage) - $perPage;

$sql = 'SELECT * FROM `film` ORDER BY :sort :direction LIMIT :currentPageFirstMovie, :perPage;';

$query = $db->prepare($sql);

$query->bindValue(':currentPageFirstMovie', $currentPageFirstMovie, PDO::PARAM_INT);
$query->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$query->bindValue(':sort', $sort, PDO::PARAM_STR);
$query->bindValue(':direction', $direction, PDO::PARAM_STR);

$query->execute();

$movies = $query->fetchAll(PDO::FETCH_ASSOC);

require_once('close.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>Liste des films</h1>

                <label class="form-label">Nombre de resultats par pages :</label>
                <select class="form-select">
                    <option value="10">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= 10 ?>&sort=<?= $sort ?>&<?= $direction ?>" class="page-link">10</a>
                    </option>
                    <option value="20">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= 20 ?>&sort=<?= $sort ?>&<?= $direction ?>" class="page-link">20</a>
                    </option>
                    <option value="10">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= 30 ?>&sort=<?= $sort ?>&<?= $direction ?>" class="page-link">30</a>
                    </option>
                </select>

                <label class="form-label">Trier selon :</label>
                <select class="form-select">
                    <option value="10">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= $perPage ?>&sort=<?= 'title' ?>&<?= $direction ?>" class="page-link">Le nom du film</a>
                    </option>
                    <option value="20">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= $perPage ?>&sort=<?= 'type' ?>&<?= $direction ?>" class="page-link">Le genre du film</a>
                    </option>
                    <option value="10">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= $perPage ?>&sort=<?= 'rentals' ?>&<?= $direction ?>" class="page-link">Le nombre de location</a>
                    </option>
                </select>

                <label class="form-label">Par ordre :</label>
                <select class="form-select">
                    <option value="10">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= $perPage ?>&<?= 'ASC' ?>" class="page-link">Croissant</a>
                    </option>
                    <option value="20">
                        <a href="./?page=<?= $currentPage ?>&limit=<?= $perPage ?>&<?= 'DESC' ?>" class="page-link">Décroissant</a>
                    </option>
                </select>

                

                <table class="table">
                    <thead>
                        <th>Titre</th>
                        <th>Classement</th>
                        <th>Prix location</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach($movies as $movie){
                        ?>
                            <tr>
                                <td><?= $movie['title'] ?></td>
                                <td><?= $movie['rating'] ?></td>
                                <td><?= $movie['rental_rate'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination mr-2">
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="./?page=<?= $currentPage - 1 ?>" class="page-link">Page Précédente</a>
                        </li>
                        <li class="page-item <?= ($currentPage == $nbPages) ? "disabled" : "" ?>">
                            <a href="./?page=<?= $currentPage + 1 ?>" class="page-link">Page Suivante</a>
                        </li>
                    </ul>
                    <span>Page <?= $currentPage ?> sur <?= $nbPages ?></span>
                </nav>
            </section>
        </div>
    </main>
</body>
</html>