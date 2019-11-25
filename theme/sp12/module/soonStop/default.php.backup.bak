<? defined('_JEXEC') or die('Restricted access');?>

<?$sql = "SELECT `sp_zakup`.`id`, `sp_zakup`.`title`, `sp_zakup`.`status`, `sp_zakup`.`dateStop`,`sp_zakup`.`soonStop` AS `st` FROM `sp_zakup` "
        . "WHERE `sp_zakup`.`soonStop` = 1 AND (`sp_zakup`.`status` = 3  OR`sp_zakup`.`status` = 5) ORDER BY id DESC  LIMIT 15 ";
$soo = $DB->getAll($sql);?>

  <div class="menu-top2">Скоро стопЭ</div>
  <div class="menu-body2">
	<?$i=0;foreach($soo as $item_r):?>    
        <div class="item-product">	  
            <a class="link3" href="/com/org/open/<?=$item_r['id']?>/"><?=$item_r['title']?></a>
         <?if ($item_r['dateStop'] != 0): {?>
            <span style="font: 14px Arial; color: red;">Дата стопа:<?=$item_r['dateStop']?></span>
            <? } endif;?>
        </div>
	<?endforeach;?>
  </div>
  <!-- END BLOCK MENU-->