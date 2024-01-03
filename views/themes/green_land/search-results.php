<div class="col-lg-8 col-md-8 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<?php if ($query && $query->num_rows() > 0) { ?>
		<?php foreach($query->result() as $row) { ?>
			<div class="card rounded-0 border border-secondary mb-3">
				<div class="card-body p-3">
					<h5 class="card-title"><a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>"><?=$row->post_title?></a></h5>
					<p class="card-text mb-0"><?=substr(strip_tags($row->post_content), 0, 165)?></p>
					<div class="d-flex justify-content-between align-items-center mt-1">
						<small class="text-muted"><?=date('d/m/Y H:i', strtotime($row->created_at))?> - Oleh <?=$row->post_author?> - Dilihat <?=$row->post_counter?> kali</small>
						<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="btn btn-sm action-button rounded-0"><i class="fa fa-search"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
</div>
<?php $this->load->view('themes/green_land/sidebar')?>
