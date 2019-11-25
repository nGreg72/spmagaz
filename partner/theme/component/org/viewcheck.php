<?defined('_JEXEC') or die('Restricted access');?>

<h1>Закрепленые закупки Организатора</h1>

<style>
tr:nth-child(2n) {background: #f0f0f0;} 
tr:nth-child(1) { background: #666; color: #fff;} 
</style>

<div>
<div>
<table><tr><td>Пользователь</td><td>URL</td><td>Описание</td><td>Бренд</td><td>Дата</td></tr>
<?foreach($all_check as $all):?>
<tr><td><a href="/com/message/new/<?=$all['userID']?>"><?=$all['username']?></a></td>
<td><?=$all['url']?></td><td><?=$all['desk']?></td><td><?=$all['brand']?></td><td><?=date('d.m.Y',$all['date'])?></td></tr>
<?endforeach;?>
</table>
</div>

<div class="pagenation" style="margin: 20px 0px 0px -10px;"><a href="index.php?component=org">...Назад</a></div>
</div>