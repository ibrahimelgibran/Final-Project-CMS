<!-- CONTENT -->
<div class="col-lg-8 col-md-8 col-sm-12 ">
	<!-- TULISAN POPULER -->
	<?php $query = get_latest_posts(5); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mb-3">Tulisan Terbaru</h5>
		<?php foreach($query->result() as $row) { ?>
			<div class="card rounded-0 border border-secondary mb-3">
				<div class="row">
					<div class="col-md-5">
						<img src="<?=base_url('media_library/posts/medium/'.$row->post_image)?>" class="card-img rounded-0" alt="<?=$row->post_title?>">
					</div>
					<div class="col-md-7">
						<div class="card-body p-3">
							<h5 class="card-title"><a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>"><?=$row->post_title?></a></h5>
							<p class="card-text mb-0"><?=substr(strip_tags($row->post_content), 0, 165)?></p>
							<div class="d-flex justify-content-between align-items-center mt-1">
								<small class="text-muted"><?=date('d/m/Y H:i', strtotime($row->created_at))?> - Oleh <?=$row->post_author?> - Dilihat <?=$row->post_counter?> kali</small>
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="btn btn-sm action-button rounded-0"><i class="fa fa-search"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>

	<!-- Photo Terbaru -->
	<?php $query = get_albums(4); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mt-3 mb-3">Foto Terbaru</h5>
		<div class="row">
			<?php foreach($query->result() as $row) { ?>
				<div class="col-md-6 mb-3">
					<div class="card h-100 shadow-sm border border-secondary rounded-0">
						<img src="<?=base_url('media_library/albums/'.$row->album_cover)?>" class="ard-img rounded-0 img-fluid p-2">
						<div class="card-body pb-2">
							<h5 class="card-title"><?=$row->album_title?></h5>
							<p class="card-text"><?=$row->album_description?></p>
						</div>
						<div class="card-footer">
							<button type="button" onclick="photo_preview(<?=$row->id?>)" class="btn action-button rounded-0 float-right"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>

	<!-- Video Terbaru -->
	<?php $query = get_videos(2); if ($query->num_rows() > 0) { ?>
		<h5 class="page-title mt-3 mb-3">Video Terbaru</h5>
		<div class="row">
			<?php foreach($query->result() as $row) { ?>
				<div class="col-md-6 mb-3">
					<div class="embed-responsive embed-responsive-16by9">
						<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$row->post_content?>" allowfullscreen></iframe>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>

<?php $this->load->view('themes/green_land/sidebar')?>
