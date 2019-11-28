<?defined('_JEXEC') or die('Restricted access');?>
<?if(count($openzakup)>0):
        $openzakup[0]['russia']=unserialize($openzakup[0]['russia']);?>
<div class="menu-top5">Экспорт/импорт товаров - <?=$openzakup[0]['title'];?></div>
<div class="menu-body5">
   <div style="display:block" class="message"><?=$message;?></div>




<h2>Импорт из CSV</h2>

<form action="" method="post" enctype="multipart/form-data">

<div>
<div style="float:left;"><input type="file" name="file" />
<input type="hidden" value="1" name="import"/></div>
<div style="margin-left: 425px;"><input type="submit" value="Импортировать" /></div>
</div>
</form>


<h2>Экспорт</h2>

<a href="/com/org/exportr/<?=$_GET['value']?>/?togo=1" class="link1">Экспорт в CSV</a>

<br/><br/><br/>
<p>
<a href="/com/org/open/<?=$_GET['value']?>/" class="link4"><< Назад</a>
</p>

</div>

* Формат столбцов импортироваемого файла .CSV должен соответсвовать <a href="/tmp/example.csv" class="link4">данному шаблону</a>.

<?else:?>
<div class="menu-top5">Ошибка</div>
<div class="menu-body5">
<h1>Такой закупки не существует</h1>

<p><a href="/" class="link4">На главную.</a></p>

<p>Возможные причины ошибки:</p>
<ul>
<li>Закупка была удалена</li>
<li>Вы набрали неверный адрес страницы</li>
</ul>
</div>
<?endif;?>
