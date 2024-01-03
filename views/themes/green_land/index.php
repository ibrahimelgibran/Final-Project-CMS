<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= isset($page_title) ? $page_title . ' | ' : '' ?><?= __session('school_name') ?></title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="keywords" content="<?= __session('meta_keywords'); ?>" />
	<meta name="description" content="<?= __session('meta_description'); ?>" />
	<meta name="subject" content="Situs Pendidikan">
	<meta name="copyright" content="<?= __session('school_name') ?>">
	<meta name="language" content="Indonesia">
	<meta name="robots" content="index,follow" />
	<meta name="revised" content="Sunday, Des, 24th, 2024, 5:15 pm" />
	<meta name="Classification" content="Education">
	<meta name="author" content="Smk Muh Gamping, info@smkmuhgamping.sch.id">
	<meta name="designer" content="Smk Muh Gamping, info@smkmuhgamping.sch.id">
	<meta name="reply-to" content="info@smkmuhgamping.sch.id">
	<meta name="owner" content="Smk Muh Gamping">
	<meta name="url" content="https://www.smkmuhgamping.sch.id">
	<meta name="identifier-URL" content="https://www.smkmuhgamping.sch.id">
	<meta name="category" content="Admission, Education">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Copyright" content="<?= __session('school_name'); ?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="7" />
	<meta name="webcrawlers" content="all" />
	<meta name="rating" content="general" />
	<meta name="spiders" content="all" />
	<meta itemprop="name" content="<?= __session('school_name'); ?>" />
	<meta itemprop="description" content="<?= __session('meta_description'); ?>" />
	<meta itemprop="image" content="<?= base_url('media_library/images/' . __session('logo')); ?>" />
	<meta name="csrf-token" content="<?= __session('csrf_token') ?>">
	<?php if (isset($post_type) && $post_type == 'post') { ?>
		<meta property="og:url" content="<?= current_url() ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?= $query->post_title ?>" />
		<meta property="og:description" content="<?= word_limiter(strip_tags($query->post_content), 30) ?>" />
		<meta property="og:image" content="<?= base_url('media_library/posts/large/' . $query->post_image) ?>" />
	<?php } ?>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="icon" href="<?= base_url('media_library/images/' . __session('favicon')); ?>">
	<link rel="alternate" type="application/rss+xml" title="<?= __session('school_name'); ?> Feed" href="<?= base_url('feed') ?>" />
	<?= link_tag('assets/plugins/bootstrap-4/bootstrap.min.css') ?>
	<?= link_tag('assets/css/font-awesome.min.css') ?>
	<?= link_tag('assets/plugins/toastr/toastr.css') ?>
	<?= link_tag('assets/plugins/datetimepicker/datetimepicker.css'); ?>
	<?= link_tag('assets/plugins/jquery.smartmenus/jquery.smartmenus.bootstrap-4.css') ?>
	<?= link_tag('assets/plugins/jquery.smartmenus/sm-core.css') ?>
	<?= link_tag('assets/plugins/jquery.smartmenus/sm-clean.css') ?>
	<?= link_tag('assets/plugins/magnific-popup/magnific-popup.css') ?>
	<?= link_tag('assets/css/loading.css') ?>
	<?= link_tag('views/themes/green_land/style.css') ?>
	<?= link_tag('views/themes/share-buttons.css') ?>
	<script type="text/javascript">
		const _BASE_URL = '<?= base_url(); ?>';
		const _CURRENT_URL = '<?= current_url(); ?>';
		const _SCHOOL_LEVEL = '<?= __session('school_level'); ?>';
		const _ACADEMIC_YEAR = '<?= __session('_academic_year'); ?>';
		const _STUDENT = '<?= __session('_student'); ?>';
		const _IDENTITY_NUMBER = '<?= __session('_identity_number'); ?>';
		const _EMPLOYEE = '<?= __session('_employee'); ?>';
		const _HEADMASTER = '<?= __session('_headmaster'); ?>';
		const _MAJOR = '<?= __session('_major'); ?>';
		const _SUBJECT = '<?= __session('_subject'); ?>';
		const _RECAPTCHA_STATUS = '<?= (NULL !== __session('recaptcha_status') && __session('recaptcha_status') == 'enable') ? 'true' : 'false'; ?>' == 'true';
	</script>
	<?php if (NULL !== __session('recaptcha_status') && __session('recaptcha_status') == 'enable') { ?>
		<script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
	<?php } ?>
	<script src="<?= base_url('assets/js/frontend.min.js') ?>"></script>
</head>

<body>
	<noscript>
		You need to enable javaScript to run this app.
	</noscript>
	<header>
		<div class="container-fluid top-bar">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-5 col-sm-12 col-xs-12">
						<div class="top-header">
							<a href="<?= base_url() ?>">
								<img src="<?= base_url('media_library/images/' . __session('logo')) ?>" width="70" height="70" class="mt-2 mr-4 mb-3">
							</a>
							<ul class="list-unstyled top-left">
								<li>
									<h5 class="font-weight-bold brand"><?= strtoupper(__session('school_name')) ?></h5>
								</li>
								<li><small><?= __session('tagline') ?></small></li>
							</ul>
						</div>
					</div>
					<div class="col-md-7 col-sm-12 col-xs-12">
						<ul class="list-inline float-right top-right">
						<a href="<?= base_url() ?>">
							<img src="<?= base_url('assets/img/logo.png') ?>" width="100" height="50" class="mt-2 mr-4 mb-3">
						</a>
							<li class="list-inline-item pl-3 pr-0"><a href="https://www.facebook.com/smkmuhammadiyahgamping"><i class="fa fa-facebook social-icon facebook"></i></a></li>
							<li class="list-inline-item pl-3 pr-0"><a href="https://www.instagram.com/smkmuhgamping/"><i class="fa fa-instagram social-icon instagram"></i></a></li>
							<li class="list-inline-item pl-3 pr-0"><a href="https://www.youtube.com/@smkmuhammadiyahgamping5495"><i class="fa fa-youtube social-icon youtube"></i></a></li>
								<a href="https://www.tiktok.com/@smkmuhgamping/">
									<img src="<?= base_url('assets/img/tiktok.png') ?>" width="42" height="42" class="mt-3 mr-4 mb-3">
								</a>
							<li class="list-inline-item pl-3 pr-0"><a href="#search_form"><i class="fa fa-search" style="font-size: 20px;"></i> </a></li>
							<br>
							<li class="list-inline-item pl-3 pr-0 mt-4	"><i class="fa fa-envelope"></i> </i> <?= __session('email'); ?></li>
							<li class="list-inline-item pl-3 pr-0"><i class="fa fa-phone"></i> <?= __session('phone'); ?></li>
						</ul>
					</div>

				</div>
			</div>
		</div>
		<!--  NAVIGATION MENU -->
		<div class="container-fluid menu-bar mb-3" data-toggle="sticky-menu">
			<div class="container p-0">
				<nav class="navbar navbar-expand-lg p-0">
					<a class="navbar-brand" href="#"></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="fa fa-align-justify text-white"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNavDropdown">
						<ul id="main-menu" class="sm sm-clean">
							<li><a href="<?= base_url() ?>"><i class="fa fa-home"></i></a></li>
							<?php
							$menus = get_menus();
							foreach ($menus as $menu) {
								echo '<li>';
								$url = $menu['menu_url'] == '#' ? $menu['menu_url'] : base_url() . $menu['menu_url'];
								if ($menu['menu_type'] == 'links') $url = $menu['menu_url'];
								echo '<a href="' . $url . '" target="' . $menu['menu_target'] . '">' . strtoupper($menu['menu_title']) . '</a>';
								$sub_nav = recursive_list($menu['children']);
								if ($sub_nav) echo '<ul>' . $sub_nav . '</ul>';
								echo '</li>';
							} ?>
						</ul>
					</div>
				</nav>
			</div>
		</div>

		<!--  /NAVIGATION MENU -->
		<?php if (!$this->uri->segment(1)) { ?>
			<!-- IMAGE SLIDERS -->
			<?php $query = get_image_sliders();
			if ($query->num_rows() > 0) { ?>
				<div class="container p-0 <?php $quotes = get_quotes();
											echo $quotes->num_rows() == 0 ? 'mb-3' : '' ?>">
					<div id="slide-indicators" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators mt-3 mb-3">
							<?php $idx = 0;
							foreach ($query->result() as $row) { ?>
								<li data-target="#slide-indicators" data-slide-to="<?= $idx ?>" <?= $idx == 0 ? 'class="active"' : '' ?>></li>
							<?php $idx++;
							} ?>
						</ol>
						<div class="carousel slide" data-ride="carousel">
							<div class="carousel-inner pt-0">
								<?php $idx = 0;
								foreach ($query->result() as $row) { ?>
									<div class="carousel-item <?= $idx == 0 ? 'active' : '' ?>">
										<img src="<?= base_url('media_library/image_sliders/' . $row->image); ?>" class="img-fluid w-100">
										<div class="carousel-caption d-none d-md-block">
											<p class="text-center mb-3"><?= $row->caption; ?></p>
										</div>
									</div>
								<?php $idx++;
								} ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<!-- /IMAGE SLIDERS -->

			<!-- QUOTE -->
			<?php $query = get_quotes();
			if ($query->num_rows() > 0) { ?>
				<div class="container p-0 mb-3">
					<div class="quote">
						<div class="quote-title"><i class="fa fa-comments"></i> KUTIPAN</div>
						<ul id="quote" class="quote">
							<?php foreach ($query->result() as $row) { ?>
								<li><?= $row->quote ?>. <span class="font-weight-bold"><?= $row->quote_by ?></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			<?php } ?>
			<!--  /QUOTE -->
		<?php } ?>

	</header>
	<section class="content">
		<div class="container">
			<div class="row">
				<?php $this->load->view($content) ?>
			</div>
		</div>
	</section>
	<footer>
		<div class="container-fluid primary-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-4 col-xs-12 text-md-left mb-2 mt-2">
						<h6 class="page-title">Hubungi Kami</h6>
						<p><?= __session('school_name') ?> &sdot; <?= __session('tagline') ?></p>
						<dl class="row">
							<dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-map-marker"></span> Alamat</dt>
							<dd class="col-lg-8 col-md-8 col-sm-12"><?= __session('street_address') ?></dd>
							<dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-phone"></span> Telepon</dt>
							<dd class="col-lg-8 col-md-8 col-sm-12"><?= __session('phone') ?></dd>
							<dt class="col-lg-4 col-md-4 col-sm-12"><span class="fa fa-envelope"></span> Email</dt>
							<dd class="col-lg-8 col-md-8 col-sm-12"><?= __session('email') ?></dd>
						</dl>
					</div>
					<div class="col-md-4 col-xs-12 text-md-left mb-2 mt-2">
						<h6 class="page-title">Tags</h6>
						<div class="tag-content-block tag">
							<?php $query = get_tags(10);
							if ($query->num_rows() > 0) { ?>
								<?php foreach ($query->result() as $row) { ?>
									<a href="<?= site_url('tag/' . $row->slug) ?>"><?= $row->tag ?></a>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-4 col-xs-12 text-md-left mb-2 mt-2">
						<h6 class="page-title">Ikuti Kami</h6>
						<ul class="list-unstyled">
							<?php if (NULL !== __session('facebook') && __session('facebook')) { ?>
								<li class="float-left"><a href="<?= __session('facebook') ?>" title="Facebook"><i class="fa fa-facebook social-icon facebook" aria-hidden="true"></a></i></li>
							<?php } ?>
							<?php if (NULL !== __session('twitter') && __session('twitter')) { ?>
								<li class="float-left"><a href="<?= __session('twitter') ?>" title="Twitter"><i class="fa fa-twitter social-icon twitter" aria-hidden="true"></a></i></li>
							<?php } ?>
							<?php if (NULL !== __session('instagram') && __session('instagram')) { ?>
								<li class="float-left"><a href="<?= __session('instagram') ?>" title="Instagram"><i class="fa fa-instagram social-icon instagram" aria-hidden="true"></a></i></li>
							<?php } ?>
							<?php if (NULL !== __session('youtube') && __session('youtube')) { ?>
								<li class="float-left"><a href="<?= __session('youtube') ?>" title="Instagram"><i class="fa fa-youtube social-icon youtube" aria-hidden="true"></a></i></li>
							<?php } ?>
							<a href="https://www.tiktok.com/@smkmuhgamping/">
									<img src="<?= base_url('assets/img/tiktok.png') ?>" width="42" height="42">
								</a>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid secondary-footer">
			<div class="container copyright">
				<div class="row pt-1 pb-1">
					<div class="col-md-6 col-xs-12 text-md-left text-center">
						<?= copyright(2023, base_url(), __session('school_name')) ?>
					</div>
					<div class="col-md-6 col-xs-12 text-md-right text-center">
						Powered by <a href="http://smkmuhgamping.sch.id">smkmuhgamping.sch.id</a>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<div id="search_form">
		<form action="<?= site_url('hasil-pencarian') ?>" method="POST">
			<input type="search_form" name="keyword" autocomplete="off" placeholder="Masukan kata kunci pencarian" />
			<button type="submit" class="btn btn-lg btn btn-outline-light rounded-0"><i class="fa fa-search"></i> CARI</button>
		</form>
	</div>
	<a href="javascript:" id="return-to-top" class="rounded-lg"><i class="fa fa-angle-double-up"></i></a>
</body>

</html>