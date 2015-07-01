<?php include_once('functions.php');?>
<?php foreach ($data['items'] as $item): ?>
<tr>
    <td><img  width="100px" height="100px" src="<?= $item['url']; ?>"/></td>
    <td><?= $item['name']; ?></td>
    <td><?= $item['description']; ?></td>
    <td><?= $item['price']; ?></td>
    <td>
        <a href="/?action=delete&item_id=<?=$item['id'];?>">Удалить</a>&nbsp;
        <a href="/form.php?item_id=<?=$item['id'];?>">Редактировать</a>
    </td>
</tr>
<tr>
    <td colspan="5">
        <? $max = ceil($data['total'] / LIST_LIMIT);?>
        <? $count = 0; ?>
        <? if ($page - 5 > 1) {
            echo '...';
        } ?>
        <?php for ($i = $page - 5; $i < $page + 15, $count < 10; $i++) : ?>
            <? if ($i >= 1 && $i <= $max): ?>
                <? $count++;?>
                <? if ($page == $i) : ?>
                    <?=$i;?>&nbsp;
                <? else: ?>
                    <a href="/?page=<?=$i;?>"><?=$i;?></a>&nbsp;
                <? endif; ?>
            <? endif; ?>
        <? endfor; ?>
        <? if ($max > $page + 5) {
            echo '...';
        } ?>
    </td>
</tr>
<? endforeach; ?>