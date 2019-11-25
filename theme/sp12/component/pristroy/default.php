<?defined('_JEXEC') or die('Restricted access');?>
<style>
.filter {height:30px;border-bottom:1px solid #D1D1D1;}
.filter a {float:left;margin:0 15px;color:#0669AB;text-decoration:underline;}
.filter b {float:left;}
.filter a:hover {text-decoration:none;}
.add-hv a {float:right;	margin:0 15px;color:#0669AB;text-decoration:underline;}
.abs {position:absolute;right:20px;width:150px;height:30px;top:10px;}
</style>
<div class="menu-top5"><b>Пристрой</b></div>
<div class="menu-body5" style="margin:0">
	<div class="filter">
	<b>Фильтр:</b>
	<a href="/com/pristroy/" <?if(empty($_GET['value'])):?>style="font:bold 12px arial"<?endif?>>все</a>
<?if ($user->get_property('userID')>0 OR $user->get_property('gid')>=18):?>
	<a href="/com/pristroy/default/my" <?if($_GET['value']=='my'):?>style="font:bold 12px arial"<?endif?>>мои</a>
<?endif;?>
	<a href="">участник</a>
	<a href="">название</a>
	</div>
<?if ($user->get_property('userID')>0 OR $user->get_property('gid')>=18):?>
	<div class="open-navi abs">
		<a href="/com/pristroy/add" class="link7 addr">добавить пристрой</a>
		<!--<a href="/com/org/editzp/<?=$openzakup[0]['id'];?>" class="link7 editzp">редактировать закупку</a>-->
	</div>
<?endif;?>
</div>
<div id="sidebar">
	<? $cat_zp = '';
    foreach($cat_zp as $catz):?>
	<a href="/com/pristroy/default/<?=$catz[0]['id']?>"
		<? $sort_catz_scroll = '';
        if($catz[0]['id']==$sort_catz_scroll):?>class="active"<?endif;?>><?=$catz[0]['name']?></a>
	<?endforeach;?>
</div>
  <? $sort_catz = '';
  if($sort_catz>0):?>
  <div id="sidebar2" <?if(count($cat_zp[$sort_catz_scroll])>7):?>style="height:60px"<?endif?>>
	<?$i=0;foreach($cat_zp[$sort_catz_scroll] as $catz):$i++;
	if($i==1)continue;?>
	<a href="/com/pristroy/default/<?=$catz['id']?>"
		<?if($catz['id']==$sort_catz):?>class="active"<?endif;?>><?=$catz['name']?></a>
	<?endforeach;?>
  </div>
  <?endif;?>

  <div id="assort" class="assort_740">
    <?if(count($hvastics) > 0):?>
        <?$i=0;
            foreach($hvastics as $zak): $i++;
                $pth='img/uploads/pristroy/'.$zak['id'];
                $extension= ['.jpg','.jpeg','.gif','.png'];
                $img_path='';
                foreach($extension as $ext){
                    if(file_exists($pth.$ext)){
                    $img_path='/images/pristroy/229/190/1/'.$zak['id'].$ext;
                    break;
                    }}

                if($img_path=='') {
                    preg_match_all('/(img|src)=(["\'])[^"\'>]+/i', $zak['text'], $media);
                    unset($data);
                    $data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);
                    $images= [];
                        foreach($data as $url)
                        {
                            $info = pathinfo($url);

                            if (isset($info['extension']) and !strpos($url,'/sp12/js/tiny_mce/plugins/emotions'))
                            {
                                if (($info['extension'] == 'jpg') ||
                                ($info['extension'] == 'jpeg') ||
                                ($info['extension'] == 'gif') ||
                                ($info['extension'] == 'png'))
                                $images[]=$url;
                            }
                        }

                    if(count($images)>0)$img_path=$images[0];
                }

                if($img_path=='')$img_path='/'.$theme.'images/no_photo229x190.png';?>

                    <div class="item <?if($i==3):$i=0;?>closing<?endif?>">
                        <div style="text-align: center;"><a href="/com/pristroy/open/<?=$zak['id']?>"><img src="<?=$img_path?>" height="190" alt="<?=$zak['title']?>" title="<?=$zak['title']?>"/></a></div>
                        <span class="slash"></span>
                        <div class="item-cont">
                            <span class="status"><b>Цена: </b><?=$zak['price']?> <?=$registry['valut_name']?></span>
                            <?if(!empty($zak['size'])):?><span class="status"><b>Размер: </b><?=$zak['size']?></span><?endif;?>

                        <div style="padding-top: 16px;"><a href="/com/pristroy/open/<?=$zak['id']?>" class="link3"><?=utf8_substr($zak['title'],0,100)?>...</a>
                        </div>
                        <div style="padding-top: 25px;"><?=date('d.m.Y',$zak['date'])?>
                            <a href="/com/profile/default/<?=$zak['user']?>" class="link2"><?=$zak['username']?></a>
                        </div>
                        </div>
                    </div>
           <?endforeach;?>
       <?else:?>
        <p>В выбранной вами категории нет товаров</p>
   <?endif;?>

        <?if ($total>1) echo '<div class="pagenation" align="center" style="margin-bottom:10px; margin-top:10px;">'
                .$pervpage.$page2left.$page1left.'<span>'.$page.'</span>'.$page1right.$page2right
                .$nextpage.'</div>';?>
  </div>

    <table style="width: 100%;">
        <caption style="font: bold 16px Arial;">Мини-таблица</caption>
        <tr style="background-color: #c4c8ca">
            <td>Кто</td>
            <td>Что</td>
            <td>Сколько</td>
            <td>Кака закупка</td>
            <td>Когда</td>
            <td>Оплата</td>
        </tr>
        <tr>
            <td>
                111
            </td>
            <td>
                222
            </td>
            <td>
                333
            </td>
            <td>
                444
            </td>
            <td>
                555
            </td>
            <td>
                666
            </td>
        </tr>
    </table>
