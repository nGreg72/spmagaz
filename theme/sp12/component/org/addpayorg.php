<?defined('_JEXEC') or die('Restricted access');?>
<style>
.newstitle:hover {text-decoration:none;}
.shop-items {
	margin:10px 0 0 0;
}
.shop-items td {
	padding:5px 10px;
	color:#4C4C4C;
}
.shop-items .title {
	background-color:#F2F3F3;
}
.anonim {
	color:#076AAD;
	font:11px arial;
}
.next {
	background-image:none;	
	padding:10px 10px 50px 10px;
}
.fixbasket {
	padding:10px 10px 50px 10px;
}
.shop-items .kolvo {color:#076AAD}
table.addpay td {vertical-align:top}
table.addpay input.text {
	border:1px solid #D7D7D7;
	width:200px;
}
table.addpay input.text:hover {
	border:1px solid #B61426;
	width:200px;
}

</style>

<?if ($user->get_property('userID')>0):?>
<div class="menu-top5">Уведомление организатору об оплате выставленного счета</div>
<div class="menu-body5">
<center>
<table class="addpay"><tr><td class="tl"></td><td class="color"></td><td class="tr"></td></tr><tr>
<td class="color"></td>
<td class="color">
<form action="" method="post">
<input name="shopid" value="<?=intval($_GET['value'])?>" type="hidden">
<table summary=""><tbody><tr><td class="title">Сумма</td><td>
<input class="text int  amount" name="amount" value="<?=$registry['orgorder'][0]['sum'];?>" type="text"> р.</td></tr>
<tr><td class="title">Дата оплаты</td><td><input class="text int " name="date" value="<?=date('d.m.Y')?> час:мин" type="text">
</td></tr><tr><td class="title">Номер вашей карты</td><td>
<input class="text int " name="desc" value="" type="text">
<div class="note">пример: 1234 56** **** 1590 (сбр-онлайн)<br>наличными <span class="delim">|</span> касса <span class="delim">|</span> 
другое</div></td></tr><tr class="last"><td class="title"></td><td>
<a class="button-cancel a-button" href="/com/basket/">Отмена</a>
<input class="button" value="Отправить" type="submit"></td></tr></tbody></table></form></td>
<td class="color"></td></tr><tr><td class="bl"></td><td class="color"></td>
<td class="br"></td></tr></table>
<center>
</div>
<?else:?>
<?@include('.access.php');?>
<?endif?>