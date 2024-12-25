<?php
require_once 'business.php';
require_once 'controller_utils.php';


function main()
{
    return 'main_view';
}

function products(&$model)
{
    $products = get_products();
    $model['products'] = $products;

    return 'products_view';
}

function product(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        if ($product = get_product($id)) {
            $model['product'] = $product;
            return 'product_view';
        }
    }

    http_response_code(404);
    exit;
}



// function delete(&$model)
// {
//     if (!empty($_REQUEST['id'])) {
//         $id = $_REQUEST['id'];

//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             delete_product($id);
//             return 'redirect:products';

//         } else {
//             if ($product = get_product($id)) {
//                 $model['product'] = $product;
//                 return 'delete_view';
//             }
//         }
//     }

//     http_response_code(404);
//     exit;
// }

function cart(&$model)
{
    $model['cart'] = get_cart();
    return 'partial/cart_view';
}

function add_to_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $product = get_product($id);

        $cart = &get_cart();
        $amount = isset($cart[$id]) ? $cart[$id]['amount'] + 1 : 1;

        $cart[$id] = ['name' => $product['name'], 'amount' => $amount];

        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function upload(&$model)
{
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case 'upload_failed':
                $model["e_upload"] = "Wybierz plik";
                break;
            case 'invalid_data':
                $model["e_upload"] = "Podaj autora";
                break;
            case 'wrong_format':
                $model["e_upload"] = "Zły format pliku, dozwolone formaty to: .jpg, .png.";
                break;
            case "file_too_big":
                $model["e_upload"] = "Plik jest za duży, maksymalny rozmiar pliku to 1MB";
                break;
            default:
                $model["e_upload"] = "Nieznany błąd";
                break;
        }
    }
    return 'upload_view';
}
function save_image(&$model)
{
    //Obsługa błędów
    if($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if(!(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK))
        {
            return 'redirect:upload?error=upload_failed';
        }
        if($_POST['author']=='')
        {
            return 'redirect:upload?error=invalid_data';
        }
    }

    //Przetwarzanie zapytania
    if($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $file = $_FILES['image'];

        //Sprawdzenie rozmiaru pliku
        if($file['size'] > 1024*1024)
        {
            return 'redirect:upload?error=file_too_big';
        }


        //Sprawdzenie typu pliku, ustawienie zmiennej z rozszerzeniem pliku
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name = $file['tmp_name'];
        $mime_type = finfo_file($finfo, $file_name);
        if ($mime_type === 'image/jpeg') {
            $image_actual_format = ".jpg";
        }
        else if ($mime_type === "image/png") {
            $image_actual_format = ".png";
        }
        else {
            return 'redirect:upload?error=wrong_format'; //redirect na stronę z błędem jeśli nie jpg ani png
        }
        
        //Logika zapisu pliku
        $upload_dir = '/var/www/dev/src/web/uploads/';
        
        //Zwykły plik
        $file_name = basename($file['name']);
        $file_partial_name = basename($file_name, $image_actual_format);
        $unique_file_name = generate_unique_filename($upload_dir, $file_partial_name, $image_actual_format);
        $file_path = $upload_dir . $unique_file_name. $image_actual_format;        
        $tmp_path = $file['tmp_name'];
        //Miniaturka
        
        $thumb_path = $upload_dir . $unique_file_name . "_thumb" . $image_actual_format;
        //Wersja z watermarkiem
        $watermark_text = $_POST['watermark'];
        $watermark_name = $unique_file_name."_watermark" . $image_actual_format;
        $watermark_path = $upload_dir . $watermark_name;
        

        if(move_uploaded_file($tmp_path, $file_path))
        {
            $image = [
                'name'=> $unique_file_name. $image_actual_format,
                'author'=> $_POST['author'],
                'path'=> $file_path,
                'type'=> $mime_type,
                'format'=> $image_actual_format,
                'thumb_name'=> $unique_file_name."_thumb" . $image_actual_format,
                'thumb_path'=> $thumb_path,
                'watermark_name'=> $watermark_name,
                'watermark_path'=> $watermark_path,
                'watermark_text'=> $watermark_text
            ];
            save_image_details(null, $image);            
            return 'redirect:upload_success';
        }
        else {
            
            return 'redirect:upload?error=upload_failed';
        }
    }
    else {
        return 'redirect:upload?error=invalid_data';
    }
    
    
}

function clear_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['cart'] = [];
        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function gallery(&$model)
{
    $images = get_images();
    $model['images'] = $images;
    return 'gallery_view';
}



function upload_success()
{
    return 'upload_success_view';
}






function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_image($id);
            return 'redirect:gallery';

        } else {
            if ($image = get_image($id)) {
                $model['image'] = $image;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}