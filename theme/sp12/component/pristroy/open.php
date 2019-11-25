<? defined('_JEXEC') or die('Restricted access'); ?>

<script src="/<?=$theme?>js/sweetalert2/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/<?=$theme?>js/sweetalert2/dist/sweetalert2.css">
<script type="text/javascript">
    function confirm(ln) {
        var link = ln.href; // Получаем значение тега href
        var title = ln.title; // Получаем значение тега title
        var name = "<?
            /** @var TYPE_NAME $hvastics */
            echo $hvastics[0]['title']?>";
        swal({
                title: 'Удалить пристрой ?', // Заголовок окна
                text: name, // Текст в окне
                type: "question", // Тип окна
                showCancelButton: true, // Показывать кнопку отмены
            }).then(function () {
            window.location.href = link;
        })
        return false;
    }

    function confirm_del_order(ln) {
        var link = ln.href; // Получаем значение тега href
        var title = ln.title; // Получаем значение тега title
        var name = "<? echo $hvastics[0]['title']?>";
        swal({
                title: 'Удалить заказ в пристрое ?', // Заголовок окна
                text: name, // Текст в окне
                type: "question", // Тип окна
                showCancelButton: true, // Показывать кнопку отмены
            }).then(function () {
            window.location.href = link;
        })
        return false;
    }
</script>

<style>
    tr:nth-child(2n) {
        background: #f0f0f0;
    }

    tr:nth-child(1) {
        background: #666;
        color: #fff;
    }
</style>

<? if (count($hvastics) > 0): ?>
    <div class="menu-top5">Пристрой:
        <span style="font: bold 18px Tahoma;"><?= $hvastics[0]['title'] ?></span></div>
    <div class="menu-body5" style="margin:0">
        <? if ($hvastics[0]['user'] == $user->get_property('userID') || $user->get_property('gid') == 25): ?>
            <p class="open-navi" style="height:40px">
                <a href="/com/pristroy/del/<?= $hvastics[0]['id']; ?>" class="link7 cross"
                   onclick="return confirm(this)">удалить пристрой</a>
                <a href="/com/pristroy/edit/<?= $hvastics[0]['id']; ?>" class="link7 editzp">редактировать</a>
            </p>
            <div class="open-slash" style="top:40px"></div>
        <? endif; ?>
        <span class="dateo"><?= date('d.m.Y', $hvastics[0]['date']) ?></span><br/>
-----------------------------------------------------------------------------
        <? $pth = 'img/uploads/pristroy/' . $hvastics[0]['id'];
        $extension = ['.jpg', '.jpeg', '.gif', '.png'];
        $img_path = '';
        foreach ($extension as $ext) {
            if (file_exists($pth . $ext)) {
                $img_path = '/images/pristroy/0/0/1/' . $hvastics[0]['id'] . $ext;
                break;
            }
        }
        ?>
        <? if ($img_path > ''): ?><img src="<?= $img_path ?>" alt="" /><br/><? endif ?>

        <?= $hvastics[0]['text'] ?>
----------------------------------------------------------------------------
        <p>
            <span class="status"><b>Цена: </b><?= $hvastics[0]['price'] ?> <?= $registry['valut_name'] ?></span><br/>
            <? if (!empty($hvastics[0]['size'])): ?>
                <span class="status"><b>Размер: </b><?= $hvastics[0]['size'] ?></span>
                <br/>
            <? endif; ?>
            <? if (!empty($hvastics[0]['cause'])): ?>
                <span class="status"><b>Причина: </b><?= $hvastics[0]['cause'] ?></span>
                <br/>
            <? endif; ?>
            <? if ($left > 0): ?>
                <span class="status" style="font: bold 25px Tahoma"><b>В наличии: </b><?= $left ?> шт.</span>
                <br/>
            <? else: ?>
                <span style="font: bold 24px Tahoma; color: red;"> Товар распродан! </span>
            <? endif; ?>
        </p>
        <p>
            <b>Пользователь</b>: <a href="/com/profile/default/<?= $hvastics[0]['user'] ?>"
                                    class="link2"><?= $hvastics[0]['username'] ?></a><br/>
            <b>Категория</b>: <a href="/com/pristroy/default/<?= $hvastics[0]['catid'] ?>"
                                 class="link4"><?= $hvastics[0]['name'] ?></a></p>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="customer_buy">
    <?if ($user->get_property('gid') > 0){?>
            <?if ($left != 0):?> <input type="submit" name="Buy" value="Я беру!"> <?endif;?>
            <? if ($left > 1): ?><input type="number" name="customer_qnt"
                                                             value="Скока">штук<? endif; ?>
        </form>
        <? if ($left > 1): ?>
            <span style="font: 16px Tahoma;">Если количество не указано, будет заказана 1 штука.</span>
        <? endif; }?>

        <div style="font: bold 40px Tahoma; color: red"><?= $message; ?>
            </div> <!-- todo Вывод ошибки, желание превышают наличие товара у орга -->

                <!--    <? /*if (!empty($ok)):*/ ?>
                <span style="font: bold 16px Tahoma;">Новый заказ успешно оформлен!</span>
            --><? /*endif;*/ ?>
                <? //@include('comments.php');?>
            </div>
        <div class="line3"></div>
        <? if ($user->get_property('gid') == 18 OR $hvastics[0]['user'] == $user->get_property('userID')): ?>
            <div class="menu-top5">У меня купили:</div>
            <div style="margin-top: 15px;">
                <table style="width: 740px; text-align: center;">
                    <tr class="tab_order_name">
<td>ID</td>                                                                                                             <!--todo Delete Me-->
                    <td>Покупатель</td>
                    <td>Кол-во</td>
                    <td>Дата офомления</td>
                    <td>Сумма</td>
                    <td>Удалить</td>
                    <td>Оплачено</td>
                    </tr>
                    <? foreach ($ordered AS $order): ?>
                        <tr>
<td style="text-align: left;"><?= $order['id']; ?></td>                                                                 <!--todo Delete Me-->
                            <td style="text-align: left;"><?= $order['customer_name']; ?></td>
                            <td><?= $order['quantity']; ?></td>
                            <td><?= date('d/m/Y H.i', $order['date']); ?></td>
                            <td><?=$hvastics[0]['price'] * $order['quantity'];?> </td>
                            <td><a href="/com/pristroy/delOrder/<?=$order['id'];?>" class="link7 cross"
                                onclick="return confirm_del_order(this)">удалить</a> </td>
                            <td id="isPaid" <?if ($order['paid'] == 1):?> style="background-color: #3d803a" <?endif?> >
                                <select class="paidStatus" rel="<?=$order['id']?>">
                                    <option value="0" <?if ($order['paid'] == 0):?> selected <?endif?>> No </option>
                                    <option value="1" <?if ($order['paid'] == 1):?> selected <?endif?>> Yes </option>
                                </select>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
         <? endif; ?>

<div class="line3"></div>

<?if ($user->get_property('gid') >= 18):?>
    <div class="menu-top5">Я купил(а):</div>
    <? $query = "SELECT `id`, `customer_id`, `title`, `customer_name`, `quantity`, `date` FROM `sp_pristroy_order` WHERE `id_pristroy`=" . $_GET['value'] . " 
                AND  `sp_pristroy_order`.`customer_id` =" .$user->get_property('userID'). " ORDER BY `sp_pristroy_order`.`id` ASC ";
                $customer_section = $DB->getAll($query);?>

    <div style="margin-top: 15px;">
        <table style="width: 740px">
            <tr class="tab_order_name">
                <td>Название</td>
                <td>Количество</td>
                <td>Дата офомления</td>
                <td>Сумма</td>
            </tr>
            <? foreach ($customer_section AS $cust): ?>
                <tr>
                    <td><?= $cust['title']; ?></td>
                    <td><?= $cust['quantity']; ?></td>
                    <td><?= date('d/m/y H.i', $cust['date']); ?></td>
                    <td><?=$hvastics[0]['price'] * $cust['quantity'];?></td>
                </tr>
            <? endforeach; ?>
        </table>
    </div>
<?endif;?>
<? else: ?>
    <div class="menu-top5">Ошибка</div>
    <div class="menu-body5">
        <h1>Такого пристроя в природе не существует</h1>

        <p><a href="/" class="link4">На главную.</a></p>

        <p>Возможные причины ошибки:</p>
        <ul>
            <li>Пристрой был удален</li>
            <li>Вы набрали неверный адрес страницы</li>
        </ul>
    </div>
<? endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".paidStatus").change(function () {
            let rel = $(this).attr("rel");
            let paidStatus = $(this).val();

            if ($('.paidStatus').parent().css('backgroundColor'))
            {
                $(this).parent().removeAttr('style')
            }
            else {
                $(this).parent().css('backgroundColor', "red")
            }

            $.post("/theme/sp12/component/pristroy/ajax.php",{
                id: rel,
                newPaidStatus: paidStatus,
                event: "changePaidStatus"
            },)
        })


    })
</script>