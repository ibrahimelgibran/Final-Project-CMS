<div class="col-lg-4 col-md-4 col-sm-12 sidebar">
	<!-- Sambutan Kepala Sekolah  -->
	<?php if ( ! in_array($this->uri->segment(1), ['sambutan-kepala-sekolah', 'sambutan-rektor', 'sambutan-ketua', 'sambutan-direktur'])) { ?>
		<div class="card rounded-0 border border-secondary mb-3">
			<img src="<?=base_url('media_library/images/').__session('headmaster_photo');?>" class="card-img-top rounded-0">
			<div class="card-body">
				<h5 class="card-title text-center text-uppercase"><?=__session('headmaster')?></h5>
				<p class="card-text text-center mt-0 text-muted">- <?=__session('_headmaster')?> -</p>
				<p class="card-text text-justify"><?=word_limiter(strip_tags(get_opening_speech()), 20);?></p>
			</div>
			<div class="card-footer text-center">
				<small class="text-muted text-uppercase"><a href="<?=site_url(opening_speech_route());?>">Selengkapnya</a></small>
			</div>
		</div>
	<?php } ?>

	<?php $links = get_links(); if ($links->num_rows() > 0) { ?>
		<h5 class="page-title mb-3 <?=!in_array($this->uri->segment(1), ['sambutan-kepala-sekolah', 'sambutan-rektor', 'sambutan-ketua', 'sambutan-direktur']) ? 'mt-3' : ''?>">Tautan</h5>
		<div class="list-group">
			<?php foreach($links->result() as $row) { ?>
				<a href="<?=$row->link_url?>" class="list-group-item list-group-item-action rounded-0" target="<?=$row->link_target?>"><?=$row->link_title?></a>
			<?php } ?>
		</div>
	<?php } ?>

	<?php $query = get_archives(date('Y')); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mt-3 mb-3">Arsip <?=date('Y')?></h5>
		<?php foreach($query->result() as $row) { ?>
			<a href="<?=site_url('arsip/'.date('Y').'/'.$row->code)?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0 mb-1">
				<?=bulan($row->code)?>
				<small class="border border-secondary pt-1 pb-1 pr-2 pl-2"><?=$row->count?></small>
			</a>
		<?php } ?>
	<?php } ?>

	<!-- Paling Dikomentari -->
	<?php $query = get_most_commented(5); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mt-3 mb-3">Paling Dikomentari</h5>
		<div class="list-group mt-3 mb-3">
			<?php foreach($query->result() as $row) { ?>
				<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="list-group-item list-group-item-action rounded-0">
					<div class="d-flex w-100 justify-content-between">
						<h6 class="card-text font-weight-bold"><?=$row->post_title?></h6>
					</div>
					<small class="text-muted"><?=date('d/m/Y H:i', strtotime($row->created_at))?> - Oleh <?=$row->post_author?> - Dilihat <?=$row->post_counter?> kali</small>
				</a>
			<?php } ?>
		</div>
	<?php } ?>

	<?php $query = get_active_question(); if ( $query ) { ?>
		<h5 class="page-title mt-3 mb-3">Jajak Pendapat</h5>
		<div class="card rounded-0 border border-secondary mb-3">
			<div class="card-body">
				<p><?=$query->question?></p>
				<?php $options = get_answers($query->id); foreach($options->result() as $option) { ?>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="answer_id" id="answer_id_<?=$option->id?>" value="<?=$option->id?>">
						<label class="form-check-label" for="answer_id_<?=$option->id?>"><?=$option->answer?></label>
					</div>
				<?php } ?>
			</div>
			<div class="card-footer">
				<div class="btn-group">
					<button type="button" name="button" onclick="vote(); return false;" class="btn action-button rounded-0"><i class="fa fa-send"></i> Submit</button>
					<a href="<?=site_url('hasil-jajak-pendapat')?>" class="btn action-button rounded-0"><i class="fa fa-bar-chart"></i> Hasil</a>
				</div>
			</div>
		</div>
	<?php } ?>

	<h5 class="page-title mt-3 mb-3">Berlangganan</h5>
	<form class="card p-1 border border-secondary mt-2 mb-2 rounded-0">
		<div class="input-group">
			<input type="text" id="subscriber" onkeydown="if (event.keyCode == 13) { subscribe(); return false; }" class="form-control rounded-0 border border-secondary" placeholder="Email Address...">
			<div class="input-group-append">
				<button type="button" onclick="if (event.keyCode == 13) { subscribe(); return false; }" class="btn action-button rounded-0"><i class="fa fa-envelope"></i></button>
			</div>
		</div>
	</form>

	<!--  Banner -->
	<?php $query = get_banners(); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mt-3 mb-3">Iklan</h5>
		<?php foreach($query->result() as $row) { ?>
			<a href="<?=$row->link_url?>" title="<?=$row->link_title?>"><img src="<?=base_url('media_library/banners/'.$row->link_image)?>" class="img-fluid mb-2 w-100" alt="<?=$row->link_title?>"></a>
		<?php } ?>
	<?php } ?>
</div>
<!-- /CONTENT -->
