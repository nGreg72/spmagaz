<h1>Закупки чисто-конкретного Организатора</h1>

<style type="text/css">
.tab-admin-orgi {
	border-collapse: collapse;
}
.tab-td-1 {
	background-color: #f4f4f4;
	border: 1px solid #808080;
}
.tab-td-2 {
	background-color: #c8c8c8;
	border: 1px solid #5c5c5c;
}
</style>
<div>
<table class="tab-admin-orgi">
<tr><td class="tab-td-2" style="width:50px">Фото</td>
	<td class="tab-td-2" style="width:80px">Имя (Ник)</td>
	<td class="tab-td-2" style="width:100px">Регион</td>
	<td class="tab-td-2" style="width:60px">Город</td>
	<td class="tab-td-2" style="width:150px">Название</td>
	<td class="tab-td-2">Описание</td>
<!--<td class="tab-td-2">Действие</td></tr> -->

	<?if (count($items)==0):?><tr><td>Закупок нет</td></tr><?endif?>

	<?foreach ($items as $item):
	if(!empty($item['foto'])){
	$split=explode('/',$item['foto']);
	$img_path='/images/'.$split[2].'/125/100/1/'.$split[3];
	} else $img_path='/'.$theme.'images/no_photo125x100.png';?>
		<tr>
		<td class="tab-td-1"><img src="<?=$img_path?>" alt=""/></td>
		<td class="tab-td-1"><a href="/com/profile/default/<?=$item['user']?>">
		<?=$item['username']?></a></td>
		<td class="tab-td-1"><?=$item['region_name_ru']?></td>
		<td class="tab-td-1"><?=$item['city_name_ru']?></td><td class="tab-td-1"><a href="/com/org/open/<?=$item['id']?>"><?=$item['title']?></a></td>
		<td class="tab-td-1"><?=utf8_substr(strip_tags(html_entity_decode($item['text'])),0,150)?></td>
		<!--<td class="tab-td-1">321321321
		<?/*
		<a href="?component=zakup&section=add&id=<?=$item['id']?>" class="news_body_a">Принять</a><br/>
		<a href="?component=zakup&section=del&id=<?=$item['id']?>" class="news_body_a">Удалить</a>
		<a href="/com/message/new/<?=$item['user']?>" class="news_body_a">Написать</a>*/?>-->
		</td>
		</tr>
	<?endforeach?>
</table>

<div class="pagenation" style="margin-left: -10px;"><a href="index.php?component=org">...Назад</a></div>
</div>