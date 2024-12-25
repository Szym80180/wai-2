<!DOCTYPE html>
<html>
<head>
    <title>Produkt</title>
    <link rel="stylesheet" href="static/css/styles.css"/>
</head>
<body>


<form action="save_image" method="post" class="wide" enctype="multipart/form-data">
    <input type="file" name="image"/>
    <input type="submit" value="Zapisz"/>    
</form>

<?php dispatch($routing, '/cart') ?>

</body>
</html>
