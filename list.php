<?php include_once('functions.php');?>
<?php foreach ($data['items'] as $item): ?>
<tr>
    <td><img  width="100px" height="100px" src="<?= $item['url']; ?>"/></td>
    <td><?= $item['name']; ?></td>
    <td><?= $item['description']; ?></td>
    <td><?= $item['price']; ?></td>
    <td>
        <a href="index.php?action=delete&item_id=<?=$item['id'];?>">Удалить</a>&nbsp;
        <a href="form.php?item_id=<?=$item['id'];?>">Редактировать</a>
    </td>
</tr>
<? endforeach; ?>
<tr>
    <td colspan="5"> Страницы
        <?php
            $max = ceil($data['total'] / LIST_LIMIT);
            if ($page - 5 > 1) echo '...';
            for ($i = min($page - 5, 1); $i < min($page - 5, 1) + 10; $i++) {
	        if ($i >= 1 && $i <= $max) { ?>
		        <? if ($page == $i) : ?>
		            <?=$i;?>&nbsp;
		        <? else: ?>
		            <a href="javascript:void(0)" onClick="navigate(<?=$i;?>)"><?=$i;?></a>&nbsp;
		        <? endif; 
		}
            }
	    if ($max > $page + 5) echo '...';
	?>
    </td>
</tr>

