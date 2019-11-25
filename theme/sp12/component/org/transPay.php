<?defined('_JEXEC') or die('Restricted access');?>
<?if ($user->get_property('gid')==25 OR $title[0]['user']==$user->get_property('userID')):?>
<style>
    tr:nth-child(1n) {
        background: #ededed;
    }
    tr:nth-child(2n) {
        background: #dddddd;
    }

    tr:nth-child(1) {
        background: #a2a2a2;
        color: #fff;
    }
</style>

<div class="menu-top5">Оплата за доставку в закупке <b><?= $title[0]['title'];?></b></div>

<div>
    В таблице представлены пользователи, <b>участвующие</b> в данной закупке и <b>оплатившие</b> заказы!!!
    <br> Всего пользователей = <b><?=count ($summ);?></b> штук.
</div><br>

<table style="border-spacing: 3px 10px; line-height: 20px;">
    <tr class="tab_order_name" style="text-align: center">
        <td width="30%">Пользователь</td>
        <td width="10%">Сумма</td>
        <td width="10%">Оплачено <br> пользователем</td>
        <td width="20%">Подтверждение<br>организатора</td>
        <td width="30%">Куды оплачено <br> Кем оплачено</td>
    </tr>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="transPay">
        <input type="hidden" name= orderID value="">
        <?$count_pay_user = 0?>
        <?$count_pay_money = 0?>
    <? foreach ($summ as $itm): ?>
        <tr>
            <td><?=$itm['username']?></td>
            <td>
                <input type="hidden" name=userID[] value="<?=$itm['user']?>">
                <input type="text" name=transpSumm[] value="<?=$itm['transp'];?>" style="width: 100px;">

            </td>
            <td>
                <div style="text-align: center"><?=$itm['transpUser'];?></div>
            </td>
            <td style="padding-left: 15px;">
                <div class=
                            <?if ($itm['transpStatus']==0):?> "styled-select-no"><?endif;?>
                            <?if ($itm['transpStatus']==1):?> "styled-select-yes"><?endif;?>
                    <select  rel=<?=$itm['id']?> class="select-id" name=transpStatus[]>
                        <option value="0" <?if($itm['transpStatus'] == 0): ?>selected<? endif ?>>Нет</option>
                        <option value="1" <?if($itm['transpStatus'] == 1): ?>selected<? endif ?>>Да</option>
                    </select>
                </div>
            </td>
            <td>
                <div style="float: left; padding-right: 5px;">
                <?if ($itm['bankNameTransp'] == 1):?><img src="/<?= $theme ?>images/bank_SB.png" title="Сбербанк" style="width: 24px; height: 24px;"><?endif;?>
                <?if ($itm['bankNameTransp'] == 2):?><img src="/<?= $theme ?>images/bank_VTB.png" title="ВТБ" style="width: 20px; height: 20px;"><?endif;?>
                <?if ($itm['bankNameTransp'] == 3):?><img src="/<?= $theme ?>images/bank_AEB.png" title="АлмазЭргиэнБанк" style="width: 20px; height: 20px;"><?endif;?>
                <?if ($itm['bankNameTransp'] == 4):?><img src="/<?= $theme ?>images/Cash.png" title="АлмазЭргиэнБанк" style="width: 20px; height: 20px;"><?endif;?>
                </div>
                <div style="display: flex;">
                <?=$itm['whoPayTransp'];?>
                </div>
            </td>
        </tr>

        <?if ($itm['transpStatus'] == 1) {$count_pay_user = $count_pay_user + 1;}?>
        <?if ($itm['transpStatus'] == 1) {$count_pay_money = $count_pay_money + $itm['transp'];}?>
    <?endforeach;?>
    <tr style="background: white">
        <td style="padding-top: 50px;"><a class="btn"  href="/com/org/open/<?=$itm['zp_id']?>">Вернуться к закупке</a></td>
        <td></td>
        <td style="padding-top: 50px;"><input class="btn" type="submit"  style="cursor: pointer"></td>
    </tr>
    </form>
</table>
    Всего оплатило:  <span style="font-size: 18px;"><?=$count_pay_user?></span> чел. <br>
    На сумму: <span style="font-size: 18px;"><?=$count_pay_money?></span> руб. <br><br>
    (Считаются только подтверждённые оплаты)
<br>


    <?else:?>Тут ничего нет!
<?endif;?>



<script>
    $(document).ready(function () {
        $('.select-id').change(function () {
            var rel = $(this).attr("rel");

           var transpStatus = $(this).val();

            $.post("/index.php?component=org&section=ajax", {event: "payForDelivery", id_zp:<?=$_GET['value']?>, transpStatus:transpStatus, rel: rel},
                function(payForDeliveryResult){
                    var buttonsStatus = $.parseJSON(payForDeliveryResult);
                    var buttonStatus = buttonsStatus[0];
                    var rowNum = buttonsStatus[1];
                });

            if ( $(this).parent().hasClass( "styled-select-no" ) ) {
                $(this).parent().removeClass("styled-select-no");
                $(this).parent().addClass("styled-select-yes");
            } else {
                $(this).parent().removeClass("styled-select-yes");
                $(this).parent().addClass("styled-select-no");
            }
        })
    })
</script>