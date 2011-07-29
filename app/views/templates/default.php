<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="keywords" content="<?=$meta_keywords?>" />
	<meta name="description" content="<?=$meta_desc?>" />
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title><?=!empty($title_for_template) ? $title_for_template : ''?></title>
	
	<link rel="stylesheet" href="<?=Config::get('site.url')?>css/style.css?v=1">
	<link rel="shortcut icon" href="<?=Config::get('site.url')?>images/favicon.ico" />
	<link rel="apple-touch-icon" href="<?=Config::get('site.url')?>images/apple-touch-icon.png">
	<?php if (isset($canonical_link) && !empty($canonical_link)) echo '<link rel="canonical" href="'.$canonical_link.'" />';?>
</head>

<body>
	<div class="site-container">
		<div class="site-header">
			<a class="site-title" href="<?=Config::get('site.url')?>">Arpy Framework</a>
		</div>
		
		<div class="site-content">
			<?=$content_for_template?>
		</div>
		
		<div class="site-footer"><a href="<?=Config::get('site.url')?>dev/info">Info</a></div>
		<div id="inform_box"></div>
	</div>
	
	<!--
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
	<script>window.jQuery || document.write('<script src="<?=Config::get('site.url')?>js/libs/jquery-1.5.1.min.js">\x3C/script>')</script>
	<script src="<?=Config::get('site.url')?>js/plugins.js"></script>
	
	<?php
	if ($inform = Session::get('inform')){
		Session::set('inform',false);
		?>
		<script type="text/javascript">
		(function(){ inform('<?=$inform['message']?>','<?=$inform['type']?>'); })();
		</script>
		<?php
	}
	?>
	
	<script type="text/javascript">
	var _gaq=[["_setAccount","UA-XXXXX-X"],["_trackPageview"]];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
	s.parentNode.insertBefore(g,s)}(document,"script"));
	</script>
	-->
	
	<?php
	if (Config::get('debug')){
		?>
		<div id="debug">
			<ul>
				<li><label>Memory:</label> <?=round(memory_get_peak_usage(true)/1024)?> Kb</li>
				<li><label>Load time:</label> <?=microtime(true) - Config::get('sys.starttime')?></li>
				<li><label>MCache:</label> <?=count(MCache::$set_keys)?> set, <?=count(MCache::$fetched_keys)?> fetched</li>
				<li><label>FCache:</label> <?=count(FCache::$set_keys)?> set, <?=count(FCache::$fetched_keys)?> fetched</li>
				<?php
				if ($db = Config::get('database.conn')){
					?>
				<li><label>DB Queries:</label> <?=count($db->queries)?></li>
				<li><label>DB Queries:</label> <?php pr($db->queries); ?></li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}
	?>
</body>
</html>