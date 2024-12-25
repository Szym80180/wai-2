<?php


use MongoDB\BSON\ObjectID;


function get_db()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]);

    $db = $mongo->wai;

    return $db;
}

function get_products()
{
    $db = get_db();
    return $db->products->find()->toArray();
}

function get_products_by_category($cat)
{
    $db = get_db();
    $products = $db->products->find(['cat' => $cat]);
    return $products;
}

function get_product($id)
{
    $db = get_db();
    return $db->products->findOne(['_id' => new ObjectID($id)]);
}

function save_product($id, $product)
{
    $db = get_db();

    if ($id == null) {
        $db->products->insertOne($product);
    } else {
        $db->products->replaceOne(['_id' => new ObjectID($id)], $product);
    }

    return true;
}

function delete_product($id)
{
    $db = get_db();
    $db->products->deleteOne(['_id' => new ObjectID($id)]);
}


function get_images()
{
    $db = get_db();
    return $db->images->find()->toArray();
}

function get_image($id)
{
    $db = get_db();
    return $db->images->findOne(['_id' => new ObjectID($id)]);
}


function create_image_thumbnail($image)
{
    $path = $image['path'];
    $thumb_path = $image['thumb_path'];
    if($image['type'] == 'image/jpeg')
    {
        $image = imagecreatefromjpeg($path);
        $image = imagescale($image, 200, 175);
        imagejpeg($image, $thumb_path);
    }
    else if($image['type'] == 'image/png')
    {
        $image = imagecreatefrompng($path);
        $image = imagescale($image, 200, 175);
        imagepng($image, $thumb_path);
    }
}

function create_image_watermark($image)
{
    $path = $image['path'];
    $watermark_path = $image['watermark_path'];
    $text = "testing watermark";
    $font = "/var/www/dev/src/web/static/fonts/Roboto-Black.ttf";
    $font_size = 16;
    
    if($image['type'] == 'image/jpeg')
    {
        $image = imagecreatefromjpeg($path);
        $image_width = imagesx($image);
        $image_height = imagesy($image);
        $white = imagecolorresolvealpha($image,255,255,255, 50);
        $black = imagecolorresolvealpha($image,0,0,0, 50);        
        imagettftext($image, $font_size,0,20,($image_height/2),$white,$font,$text);
        imagejpeg($image, $watermark_path);
    }
    else if($image['type'] == 'image/png')
    {
        $image = imagecreatefrompng($path);
        $image_width = imagesx($image);
        $image_height = imagesy($image);
        $white = imagecolorresolvealpha($image,255,255,255, 50);
        $black = imagecolorresolvealpha($image,0,0,0,50);        
        imagettftext($image, $font_size,0,20,($image_height/2),$black,$font,$text);
        imagepng($image, $watermark_path);
    }
}




function save_image_details($id, $image)
{
    $db = get_db();

    if ($id == null) {
        $db->images->insertOne($image);  
        create_image_thumbnail($image);
        create_image_watermark($image);
        } else {
        $db->images->replaceOne(['_id' => new ObjectID($id)], $image);
    }
    

    return true;
}


function delete_image($id)
{
    $db = get_db();
    $file = $db->images->findOne(['_id'=> new ObjectID($id)]);
    if(file_exists($file['path']))
    {
        unlink($file['path']);
    }
    if(file_exists($file['thumb_path']))
    {
        unlink($file['thumb_path']);
    }
    if(file_exists($file['watermark_path']))
    {
        unlink($file['watermark_path']);
    }
   
    $db->images->deleteOne(['_id' => new ObjectID($id)]);

}



function generate_unique_filename($upload_dir, $file_name, $extension)
{
    $file_base_name = basename($file_name, $extension);
    $unique_name = $file_base_name;
    $counter = 1;

    while (file_exists($upload_dir . $unique_name .$extension)) {
        $unique_name = $file_base_name . '_' . $counter;
        $counter++;
    }

    return $unique_name;
}