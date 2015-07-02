<?php define("PROLOG_INCLUDE", true); include_once('functions.php');?><!DOCTYPE html><html>
<head>
<meta http-equiv=Content-Type content="text/html;charset=UTF-8">
</head>
<body>

<? if ($error) : ?>
    <font color="red"><?=$error;?></font><br/>
<? endif;?>

<?php
$new = false;
if (isset($_GET['item_id']) && $id = $_GET['item_id']) {
    $item = loadItem($id);
} else {
    $new = true;
    $item = array('name' => '', 'description' => '', 'url' => false, 'price' => '', 'id' => false);
}

foreach($item as $key => $value) {
    if (isset($_POST[$key])) $item[$key] = $_POST[$key];
    $value = htmlspecialchars($value, ENT_QUOTES);
}
?>

<form method="POST" action="?action=<?= $new ? 'add' : 'update';?>" enctype="multipart/form-data">

    <? if ($new): ?>
        <h1> Добавление товара </h1>
    <? else: ?>
        <h1> Обновление товара <?= $item['name']; ?> (ID: <?= $item['id'];?>) </h1>
        <input type="hidden" name="id" value="<?=$item['id'];?>"/>
    <? endif; ?>

    <table>
        <tr>
            <td> Название </td>
            <td>
                <input type="text" name="name" value="<?=htmlspecialchars($item['name'], ENT_QUOTES);?>"/>
            </td>
        </tr>
        <tr>
            <td> Описание </td>
            <td>
                <textarea name="description"><?=htmlspecialchars($item['description'], ENT_QUOTES);?></textarea>
            </td>
        </tr>
        <tr>
            <td> Цена </td>
            <td>
                <input type="text" name="price" value="<?=$item['price'];?>">
            </td>
        <tr>
            <td> Изображение </td>
            <td>
                <? if ($item['url']) { ?>
                    <img width="100px" height="100px" src="<?=$item['url'];?>"/><br/>Изменить:
                <? } ?>
                <input type="file" name="itemfile"/>
            </td>
        </tr>
    </table>
    <input type="submit" value="<?= $new ? 'Добавить' : 'Сохранить';?>"/>
</form>

<a href="/">Назад</a>
</body>
</html>
