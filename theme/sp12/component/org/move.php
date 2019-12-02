<?defined('_JEXEC') or die('Restricted access');?>

<?include_once ('db_requests.php')?>

<?if ($user->get_property('gid')==25 OR $openzakup[0]['user']==$user->get_property('userID')):?>

<div class="menu-top5">Перенести заказ <?=$_GET['value2']?> в другую закупку/другой выкуп</div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>
  <?if(count($allsize)>0 and count($openzakup)>0):?>

    <?if(!$message):?>
        <?if($_GET['u']):?><br/><a href="?u=<?=$itm[4]?>" class="link4"><< заказы</a><?endif?>

                <form name="" method="post" action="">
                    <input type="hidden" name="move" value="1">
                    <input type="hidden" name="id_o" value="<?= $_GET['value2'] ?>">
                    Список закупок:
                    <select name="id_zp" class="inputbox" style="width:350px;">
                        <? foreach ($allZP as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['title'] ?></option>
                        <? endforeach ?>
                    </select>
                    <input type="submit" value="Перенести">
                </form>
            <? endif ?>

            <div class="line3"></div>
            <p><a href="/com/org/open/<?= intval($_GET['value']) ?>" class="link4">...Вернуться к закупке</a></p>
            </div>
        <? else: ?>

            <h1>Такого заказа нет</h1>
            <p><a href="javascript:history.back()" class="link4"><< Назад</a></p>

        <? endif ?>
<? $test = new db_requests();
$arr = $test->get_orders_for_move();
$a = 1;?>
<?endif?>