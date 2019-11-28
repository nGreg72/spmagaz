<?defined('_JEXEC') or die('Restricted access');?>

<?if(count($openzakup)>0):?>

<div class="menu-top5"><?=$openzakup[0]['title'].' - '.$openzakup[0]['city_name_ru'];?></div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>

	<?if($openzakup[0]['user']==$user->get_property('userID') OR $user->get_property('gid')>=23):?>
		<p class="open-navi">
<a href="/com/org/delzp/<?=$_GET['value'];?>" class="link7 cross" style="float:none;margin:0;">Удалить закупку целиком</a><br/>
<a href="/com/org/delryad/<?=$_GET['value'];?>" class="link7 cross" style="float:none;margin:0;">Удалить только товары из закупки</a>
		</p>


	<?endif;?>



<br/><br/><br/>
<p>
<a href="/com/org/open/<?=$_GET['value']?>/" class="link4"><< Назад</a>
</p>


</div>

<?endif?>