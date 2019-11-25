<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('gid')>18):?>
<h1>История счетов</h1>

<form action="" method="post">
ФИО или Логин: 
	<input type="hidden" name="filter" value="1" />
	<input type="text" class="inputbox" name="realname" value="<?=$_POST['realname']?>" />
	<input type="submit"  value="ок" />
</form>

<style>
.tb1 {border:1px solid #eee;border-bottom:none}
.tb1 td {padding:2px 5px;border-bottom:1px solid #eee}
</style>

                <?if(count($registry['history'])>0):?>
		<table class="tb1">
		<tr><td width="100"><b>Пользователь</b></td><td width="100"><b>Дата</b></td><td width="250"><b>Примечание</b></td><td width="100"><b>Приход</b></td><td width="100"><b>Расход</b></td><td width="100"><b>Баланс</b></td></tr>
		<?foreach($registry['history'] as $item):?>

		<tr>
		<td><a href="/com/profile/default/<?=$item['user']?>"><?=$item['username']?></a></td>
		<td><?=date('d/m/Y H:i',$item['date'])?></td>
		<td><small><?=$item['mess']?></small></td>
		<td style="color:green"><?if($item['type']==1):?>+ <?=$item['summ']?> руб.<?endif?></td>
		<td style="color:red"><?if($item['type']==0):?>- <?=$item['summ']?> руб.<?endif?></td>	
		<td style="color:#222"><?=$item['balance']?> руб.</td>	
		</tr>

        	<?endforeach;?>
		</table>
		<?else:?>
			<p>Событий нет.</p>
		<?endif?>
<br/>

<?if ($total>1) echo '<div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
		.$pervpage.$page2left.$page1left.'<span>'.$page.'</span>'.$page1right.$page2right
		.$nextpage.'</div>';?>




<?else:?>
<?@include('.access.php');?>
<?endif?>