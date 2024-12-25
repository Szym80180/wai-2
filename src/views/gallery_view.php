<!DOCTYPE html>
<html>
<head>
    <title>Galeria</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Nazwa</th>
        <th>Cena</th>
        <th>Operacje</th>
    </tr>
    </thead>

    <tbody>
    <?php if (count($images)): ?>
        <?php foreach ($images as $image): ?>
            <tr>
                <td>
                    <!-- <a href="view?id=<?= $image['_id'] ?>"><?= $image['name'] ?></a> -->
                    <a href="/uploads/<?= $image['name'] ?>" target="_blank">
                    <img src="/uploads/<?= $image['thumb_name'] ?>" alt="<?= $image['name'] ?>"/>
                    </a>
                </td>
                <td><?= $image['author'] ?></td>
                <td>
                    <a href="delete?id=<?= $image['_id'] ?>">Usuń</a>
                </td>
            </tr>
        <?php endforeach ?>
    <?php else: ?>
        <tr>
            <td colspan="3">Brak zdjęć</td>
        </tr>
    <?php endif ?>
    </tbody>

    <tfoot>
    <tr>
        <td colspan="2">Łącznie: <?= count($images) ?></td>
        <td>
            <a href="upload">nowe zdjęcie</a>
        </td>
    </tr>
    </tfoot>
</table>

<?php dispatch($routing, '/cart') ?>

</body>
</html>
