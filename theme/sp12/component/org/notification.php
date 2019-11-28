<?defined('_JEXEC') or die('Restricted access');
if ($user->get_property('gid')==25 OR $title[0]['user']==$user->get_property('userID')):?>
    <div class="menu-top5">Уведомление за пользователя в закупке <b><?= $title[0]['title'];?></b></div>
    <br><br>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" value="notification" name="action">

            <select name="forUser" id="">
                <option value="" selected disabled>За кого?</option>
                <?foreach ($listAllUsers AS $value):?>
                    <?if (empty($value['paidUser'])):?>
                    <option value="<?=$value['user']?>"><?=$value['username']?></option>
                    <?endif;?>
                <?endforeach;?>
            </select>

            <span style="margin-left: 20px;">Сумма = <input type="number" name="summ"></span>

        <select name="bank" style="margin-left: 20px;">
            <option value="" selected disabled>Банк</option>
            <option value="1">СБ</option>
            <option value="2">ВТБ</option>
            <option value="3">АЭБ</option>
            <option value="4">Наличка</option>
        </select>
        <br><br><br>
        <span style="margin-left: 120px;">Отправитель = <input type="text" name="whoPay"></span>

        <input type="submit" style="margin-left: 30px;">
    </form>
    <br>
    <span>В выпадающем списке присутствуют пользователи, не отправившие уведомление! После уведомления, такой пользователь исчезает из списка! </span>
    <br>
    <span style="font-size: 30px; color: red">
            <?=$error1;?>
            <?=$error2;?>
            <?=$error3;?>
            <?=$error4;?>
    </span>
<?endif;?>