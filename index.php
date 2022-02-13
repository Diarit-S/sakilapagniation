<?php
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}else{
    $currentPage = 1;
}
require_once('connect.php');

$sql = 'SELECT COUNT(*) AS nb_movies FROM `film`;';

$query = $db->prepare($sql);

$query->execute();

$result = $query->fetch();

$nbMovies = (int) $result['nb_movies'];

$perPage = 10;

$pages = ceil($nbMovies / $perPage);

$firstMovie = ($currentPage * $perPage) - $perPage;

$sql = 'SELECT * FROM `film` ORDER BY `last_update` DESC LIMIT :firstMovie, :perPage;';

$query = $db->prepare($sql);

$query->bindValue(':firstMovie', $firstMovie, PDO::PARAM_INT);
$query->bindValue(':perPage', $perPage, PDO::PARAM_INT);

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
                    <ul class="pagination">
                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                            <a href="./?page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                        </li>
                        <?php for($page = 1; $page <= $pages; $page++): ?>
                            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                <a href="./?page=<?= $page ?>" class="page-link"><?= $page ?></a>
                            </li>
                        <?php endfor ?>
                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                            <a href="./?page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                        </li>
                    </ul>
                </nav>
            </section>
        </div>
    </main>
</body>
</html>