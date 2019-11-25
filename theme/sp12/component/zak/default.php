<?defined('_JEXEC') or die('Restricted access');
?>
<div class="menu-top5">Горячие закупки</div>
  <div id="assort" style="margin:0"  class="assort_740"><br/>
    <?if(count($zakup)>0):?>
    <?$i=0;foreach($zakup as $zak):$i++;
	if(!empty($zak['foto'])){
	$split=explode('/',$zak['foto']);
	$img_path='/images/'.$split[2].'/229/190/1/'.$split[3];
	} else $img_path='/'.$theme.'images/no_photo229x190.png';?>
    <div class="item <?if($i==3):$i=0;?>closing<?endif?>">
	<a href="/com/org/open/<?=$zak['id']?>"><img src="<?=$img_path?>" border="0" width="229" height="190" alt="<?=$zak['title']?>" title="<?=$zak['title']?>"/></a>
	<span class="slash"></span>
	<div class="item-cont">
		<a href="/com/org/open/<?=$zak['id']?>" class="link3"><?=$zak['title']?></a><br/>
		<a href="/com/profile/default/<?=$zak['user']?>" class="link2"><?=$zak['username']?></a>, г. <?=$zak['city_name_ru']?>
		<span class="status"><?=$zak['namestat']?>, <b><?=$zak['res']?> заказа</b></span>
	</div>
   </div>
   <?endforeach;?>
<?if ($total>1) echo '<div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
		.$pervpage.$page2left.$page1left.'<span>'.$page.'</span>'.$page1right.$page2right
		.$nextpage.'</div>';?>

   <?else:?>
    <div class="menu-body5">
	<p>В этом городе, в данной категории закупок пока нет.</p>
	<p><a class="link4" href="/every/all">...Смотреть все закупки</a></p>
	<h1>Информация</h1>
	<ul>
	<li>Вы можете стать Организатором и добавить свою закупку</li>
	<li>Вашим предложениям будут рады на <a href="/forum" class="link4">Форуме</a></li>
	</ul>
   </div>
   <?endif;?>

 </div>

