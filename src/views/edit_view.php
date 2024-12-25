<!DOCTYPE html>
<html>
<head>
    <title>Edycja</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>

<form method="post" class="wide">
    <label>
        <span>Nazwa:</span>
        <input type="text" name="name" value="<?= $image['name'] ?>" required/>
    </label>
    <label>
        <span>Autor:</span>
        <input type="text" name="price" value="<?= $image['author'] ?>" required/>
    </label>


    <input type="hidden" name="id" value="<?= $image['_id'] ?>">

    <div>
        <a href="gallery" class="cancel">Anuluj</a>
        <input type="submit" value="Zapisz"/>
    </div>
</form>

</body>
</html>
