<?php include_once('functions.php');?><!DOCTYPE html><html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<? if (isset($_SESSION['message'])) : ?>
<font color="green"><? echo $_SESSION['message']; unset($_SESSION['message']);?></font><br/>
<? endif;?>

<a href="form.php">Добавить товар</a><br/><br/>

<table>
    <tr>
        <th>Изображение</th>
        <th><a href="javascript:void(0)" onClick="sort(this, 'name');">Название</a></th>
        <th>Описание</th>
        <th><a href="javascript:void(0)" onClick="sort(this, 'price')">Цена</a></th>
        <th>Действия</th>
    </tr>
    <tbody id="tableBody">
        <? include('list.php');?>
    </tbody>
</table>


<script src="script.js"></script>
</body>
</html>
