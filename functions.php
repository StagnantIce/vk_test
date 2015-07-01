<?php
session_start();


define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', 'root');
define('DBNAME', 'vk');

define('LIST_LIMIT', 20);

$bd = mysql_connect(DBHOST, DBUSER, DBPASS) or die(mysql_error());
mysql_select_db(DBNAME, $bd) or die(mysql_error());

function preparePrice($price) {
    $price = trim(str_replace(',', '.', $price));
    if ($price === "" || !is_numeric($price)) {
        throw new Exception("Price invalid");
    }
    return floatval($price);
}

function prepareText($text, $max) {
    $text = trim($text);
    if (mb_strlen($text) > $max || $text === "") {
        throw new Exception("Недопустимая длина текста");
    }
    return mysql_real_escape_string($text);
}

function prepareFile(Array $file, $new = false) {
    $url = false;
    if (isset($file['name']) && $file['name'] !== "") {
        $tmpfile = $file['tmp_name'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $url = '/files/'.uniqid().'.'.$ext;
        $localName = __DIR__ . $url;
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($tmpfile);
        if (!in_array($detectedType, $allowedTypes)) {
            throw new Exception("Неверный тип файла.");
        }
        if (!move_uploaded_file($tmpfile, $localName)) {
            throw new Exception("Ошибка копирования");
        }
    } else if ($new) {
        throw new Exception("Файл не выбран");
    }
    return $url;
}

function updateItem() {
    $name = prepareText($_POST['name'], 255);
    $description = prepareText($_POST['description'], 4000);
    $price = preparePrice($_POST['price']);
    $url = prepareFile($_FILES['itemfile'], false);
    if (isset($_POST['id']) && $id = intval($_POST['id'])) {
        mysql_query("UPDATE `items` SET name = '$name', description='$description', price=$price" . ($url ? ", url='$url'" : ''). " WHERE id = $id") or die(mysql_error());
    } else {
        throw new Exception("Не указан товар для обновления");
    }
}

function addItem() {
    $name = prepareText($_POST['name'], 255);
    $description = prepareText($_POST['description'], 4000);
    $price = preparePrice($_POST['price']);
    $url = prepareFile($_FILES['itemfile'], true);
    $query = "INSERT INTO `items` (`name`, `description`, `price`, `url`) values ('".$name."', '".$description."', ".$price.",'".$url."')";
    mysql_query($query) or die($query . mysql_error());
}


function deleteItem($id) {
    $id = intval($id);
    mysql_query("DELETE FROM `items` WHERE id = $id");
}

function loadItem($id) {
    $id = intval($id);
    return mysql_fetch_assoc(mysql_query("SELECT * FROM `items` WHERE id = $id"));
}

function listCount() {
    $arr = mysql_fetch_assoc(mysql_query("SELECT FOUND_ROWS() as CNT"));
    return intval($arr['CNT']);
}

function listItem($sort, $order, $page) {
    $result = array('items' => array());
    $res = mysql_query("SELECT SQL_CALC_FOUND_ROWS id, name, description, url, price from `items` ORDER BY $sort $order LIMIT " . (($page - 1) * LIST_LIMIT) . ", " . LIST_LIMIT) or die(mysql_error());
    if ($res) {
        while($row = mysql_fetch_assoc($res)) {
            $result['items'][] = $row;
        }
    }
    $result['total'] = listCount();
    return $result;
}

$error = false;

try {
    $action = 'list';

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    }

    $sort = 'id';
    $order = 'asc';
    $page = 1;
    $data = array();

    if (isset($_GET['sort']) && in_array($_GET['sort'], array('price', 'name'))) {
        $sort = $_GET['sort'];
    }
    if (isset($_GET['order']) && in_array($_GET['order'], array('asc', 'desc'))) {
        $order = $_GET['order'];
    }
    if (isset($_GET['page'])) {
        $page = intval($_GET['page']);
    }

    switch($action) {
        case 'delete':
            deleteItem($_GET['item_id']);
            $_SESSION['message'] = 'Товар удален';
            Header("Location: /");
        break;
        case 'add':
            addItem();
            $_SESSION['message'] = 'Товар добавлен';
            Header("Location: /");
        break;
        case 'update':
            updateItem();
            $_SESSION['message'] = 'Товар обновлен';
            Header("Location: /");
        break;
        default:
            $data = listItem($sort, $order, $page);
        break;
    }
}
catch(Exception $e) {
    $error = $e->getMessage();
}
