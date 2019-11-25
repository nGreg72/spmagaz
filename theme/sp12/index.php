<?@include('header.php')?>
<!--<?@include('corusel.php')?>-->

<div>
<div class="content-body">

<p id="back-top">
	<a href="#top" id="back-top-a"><span id="back-top-span"></span>Вверх</a>
</p>
</p>
<p id="toEnd">
    <a href="#footer" id="toEnd-a"><span id="toEnd-span"></span>Вниз</a>
</p>

<div id="content">
     <?//if ($err>0) @include($theme.'component/error/default.php');?>
     <?@include($contents_view)?>

	<?foreach($registry['ads1'] as $link):?>
	<?if($link['type']==1):?>
	 <?if($link['noindex']==1):?><noindex><?endif;?>
	 <?if($link['show']==1):?><a class="link1" target="_blank" href="<?=$link['url']?>" <?if($link['nofollow']==1):?>rel="nofollow"<?endif;?>><?=$link['ankor']?></a><?endif;?>
	 <?if($link['noindex']==1):?></noindex><?endif;?>
	<?endif;?>
	<?if($link['type']==2):?>
	 <?if($link['noindex']==1):?><noindex><?endif;?>
	 <?if($link['show']==1):?><a class="link1" target="_blank" href="<?=$link['url']?>" <?if($link['nofollow']==1):?>rel="nofollow"<?endif;?>><img src="<?=str_replace('../','/',$link['photo'])?>" title="<?=$link['ankor']?>" alt="<?=$link['ankor']?>" /></a><?endif;?>
	 <?if($link['noindex']==1):?></noindex><?endif;?>
	<?endif;?>
	<?if($link['type']==3):?>
	 <?if($link['show']==1):?><?=$link['ankor']?><?endif;?>
	<?endif;?>
	<?endforeach;?>
</div>
</div>

<div>
	<?  include($theme.'module/mlogin/default.php')?>
	<?  include($theme.'module/zakup/default.php')?>	
	<?  include($theme.'module/soonStop/default.php')?> <!-- скоро стоп -->
	<?  include($theme.'module/online/default.php')?>
<!--<?@	include($theme.'module/chat/default.php')?>-->
	<? include($theme.'module/lastorder/default.php')?> <!-- последние заказы -->	
<!--<?@include($theme.'module/freshblog/default.php')?>
<!--<?@include($theme.'module/groups/default.php')?>-->
    <?  include($theme.'module/news/default.php')?>
    <?  include($theme.'module/articles/default.php')?>
    <?//@include($theme.'module/lastforum/default.php')?>
	<?@include($theme.'module/reviews/default.php')?>

<!-- <?@include($theme.'module/votes/default.php')?>-->
</div>

<div id="extra">


	<?foreach($registry['ads2'] as $link):?>
	<?if($link['type']==1):?>
	 <?if($link['noindex']==1):?><noindex><?endif;?>
	 <?if($link['show']==1):?><a class="link1" target="_blank" href="<?=$link['url']?>" <?if($link['nofollow']==1):?>rel="nofollow"<?endif;?>><?=$link['ankor']?></a><?endif;?>
	 <?if($link['noindex']==1):?></noindex><?endif;?>
	<?endif;?>
	<?if($link['type']==2):?>
	 <?if($link['noindex']==1):?><noindex><?endif;?>
	 <?if($link['show']==1):?><a class="link1" target="_blank" href="<?=$link['url']?>" <?if($link['nofollow']==1):?>rel="nofollow"<?endif;?>><img src="<?=str_replace('../','/',$link['photo'])?>" title="<?=$link['ankor']?>" alt="<?=$link['ankor']?>" /></a><?endif;?>
	 <?if($link['noindex']==1):?></noindex><?endif;?>
	<?endif;?>
	<?if($link['type']==3):?>
	 <?if($link['show']==1):?><?=$link['ankor']?><?endif;?>
	<?endif;?>
	<?endforeach;?>
</div>
</div>
	<?@include('footer.php')?>