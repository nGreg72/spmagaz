<?defined('_JEXEC') or die('Restricted access');?>
<?
$grlink = $DB->getAll(' SELECT `groups_art`.`id`, `groups_art`.`title`
			FROM `groups_art`
			ORDER BY `groups_art`.`id` DESC
			LIMIT 7');
?>

  <div class="menu-top-green">Новое в группах</div>
  <div class="menu-body2">
	<?if(count($grlink)>0):?>
	<?foreach($grlink as $grl):?>
	<a href="/com/groups/viewit/<?=$grl['id']?>/" class="link3"><?=$grl['title']?></a>
	<?endforeach;?>
	<?else:?>
	Записей нет.
	<?endif;?>
  </div>
