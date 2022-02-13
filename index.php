<?php
$currentPage = 1;
if(isset($_GET['page']) && !empty($_GET['page'])){
    $currentPage = (int) strip_tags($_GET['page']);
}

$perPage = 10;
if(isset($_GET['limit']) && !empty($_GET['limit'])){
    $perPage = (int) strip_tags($_GET['limit']);
}

$sort = 'title';
if(isset($_GET['sort']) && !empty($_GET['sort'])){
    $sort = (string) strip_tags($_GET['sort']);
}

$direction = 'ASC';
if(isset($_GET['direction']) && !empty($_GET['direction'])){
    $direction = (string) strip_tags($_GET['direction']);
}

require_once('connect.php');

$sql = 'SELECT COUNT(*) AS nb_movies FROM `film`;';

$query = $db->prepare($sql);

$query->execute();

$result = $query->fetch();

$nbMovies = (int) $result['nb_movies'];

$nbPages = ceil($nbMovies / $perPage);

$sql = 'SELECT * FROM `film` ORDER BY :sort :direction LIMIT :perPage;';

$query = $db->prepare($sql);

$query->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$query->bindValue(':sort', $sort, PDO::PARAM_STR);
$query->bindValue(':direction', $direction, PDO::PARAM_STR);

var_dump($direction);
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <main class="container mt-5">
        <div class="row">
            <section class="col-12">
                <h1>Liste des films</h1>

                <form class="d-flex flex-column align-items-start" action="index.php?test=test">
                    <div class="mb-2">
                        <label class="form-label">Nombre de resultats par pages :</label>
                        <select class="form-select w-auto" name="limit">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Trier selon :</label>
                        <select class="form-select w-auto" name="sort">
                            <option value="title">Le nom du film</option>
                            <option value="type">Le genre du film</option>
                            <option value="rentals">Le nombre de location</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Par ordre :</label>
                        <select class="form-select w-auto" name="direction">
                            <option value="ASC">Croissant</option>
                            <option value="DESC">Décroissant</option>
                        </select>
                    </div>
                    <input type="hidden" name="page" value="<?php $currentPage ?>">
                    <input class="btn btn-primary mt-2" type="submit" value="Envoyer">
                </form>

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