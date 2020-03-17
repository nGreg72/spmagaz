<?defined('_JEXEC') or die('Restricted access');?>

<?$sql = "SELECT * FROM sp_deleters";
$deleters = $DB->getAll($sql);?>

<style>
    tr:nth-child(2n) {background: #DAD9D9;}
    tr:nth-child(1) { background: #666; color: #fff;}
</style>

<h1 style="padding-left: 100px;">Удаляльщики</h1>

<div style="width: 1000px;">
    <table style="font-size: 13px;">
        <tr>
            <td>Пользователь</td>
            <td style="text-align:center;">Закупка</td>
            <td style="text-align:center;">Товар</td>
            <td style="text-align: center;">Дата</td>
            <td>Кол-во</td>
        </tr>

        <? foreach ($deleters AS $deleter) : ?>
            <tr style="height: 30px;">
                <td><?= $deleter['username'] ?></td>
                <td style="padding: 0 10px 0 10px"><?= $deleter['zp_name'] ?></td>
                <td style="padding: 0 10px 0 10px"><?= $deleter['ryad_name'] ?></td>
                <td style="padding: 0 10px 0 10px"><?= $deleter['date'] ?></td>
                <td style="text-align: center;"><?= $deleter['quantity'] ?></td>
            </tr>
        <? endforeach; ?>
    </table>
</div>
