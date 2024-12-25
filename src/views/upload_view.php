<!DOCTYPE html>
<html>
<head>
    <title>Produkt</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>
    <h1>Prześlij zdjęcie</h1>
    <form action="/save_image" method="post" class="wide" enctype="multipart/form-data">
        <input type="file" name="image"/><br/>
        <input type="text" name="author" placeholder="Autor"/><br/>
        <input type="text" name="watermark" placeholder="Znak wodny"/><br/>
        <input type="submit" value="Zapisz"/><br/>
        <?php if (isset($model['e_upload'])): ?>
        <div class="error"><?php echo $model["e_upload"]; ?></div>
        <?php endif; ?>
    </form>
</body>
</html>