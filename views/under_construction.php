<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
	<title><?=__session('school_name')?></title>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="keywords" content="<?=__session('meta_keywords');?>"/>
	<meta name="description" content="<?=__session('meta_description');?>"/>
	<meta name="subject" content="Situs Pendidikan">
	<meta name="copyright" content="<?=__session('school_name')?>">
	<meta name="language" content="Indonesia">
	<meta name="robots" content="index,follow" />
	<meta name="revised" content="Sunday, July 18th, 2010, 5:15 pm" />
	<meta name="Classification" content="Education">
	<meta name="author" content="Anton Sofyan, 4ntonsofyan@gmail.com">
	<meta name="designer" content="Anton Sofyan, 4ntonsofyan@gmail.com">
	<meta name="reply-to" content="4ntonsofyan@gmail.com">
	<meta name="owner" content="Anton Sofyan">
	<meta name="url" content="https://www.sekolahku.web.id">
	<meta name="identifier-URL" content="https://www.sekolahku.web.id">
	<meta name="category" content="Admission, Education">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Copyright" content="<?=__session('school_name');?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="7" />
	<meta name="webcrawlers" content="all" />
	<meta name="rating" content="general" />
	<meta name="spiders" content="all" />
	<meta itemprop="name" content="<?=__session('school_name');?>" />
	<meta itemprop="description" content="<?=__session('meta_description');?>" />
	<meta itemprop="image" content="<?=base_url('media_library/images/'. __session('logo'));?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="icon" href="<?=base_url('media_library/images/'.__session('favicon'));?>">
</head>
<body>
	<style>
	body { text-align: center; padding: 150px; }
	h1 { font-size: 50px; }
	body { font: 20px Helvetica, sans-serif; color: #333; }
	article { display: block; text-align: left; width: 650px; margin: 0 auto; }
	a { color: #dc8100; text-decoration: none; }
	a:hover { color: #333; text-decoration: none; }
	</style>
	<article>
		<h1>We&rsquo;ll be back soon!</h1>
		<div>
			<p>Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment.</p>
			<p>&mdash; IT Team <?=__session('school_name');?></p>
		</div>
	</article>
</body>
</html>