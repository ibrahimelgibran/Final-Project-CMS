<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content">
	<?php if (__session('user_type') === 'super_user' || __session('user_type') === 'administrator') { ?>
		<?php if (ENVIRONMENT == 'development') { ?>
			<div class="callout">
				<h4>INFORMASI !</h4>
				<p>Website masih dalam mode <b>"DEVELOPMENT"</b>. Jika website sudah online <b>SANGAT DISARANKAN</b> untuk mengubahnya menjadi mode <b>"PRODUCTION"</b>. Mode development hanya diperbolehkan jika masih dijalankan pada server offline.</p>
				<p>Untuk mengubah menjadi mode <b>PRODUCTION</b>, silahkan buka file <b>INDEX.PHP</b> yang berada di root direktori CMS Sekolahku. Pada baris 56, silahkan ubah tulisan <b>development</b> menjadi <b>production</b> seperti dibawa ini :</p>
				<code>define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : '<font style="color: yellow;">production</font>');</code>
			</div>
		<?php } ?>

		<?php if ($warning) { ?>
			<div class="callout callout-danger">
				<h4>PERINGATAN !</h4>
				<ul>
					<?php if (!$recaptcha_site_key OR !$recaptcha_secret_key) { ?>
						<li><b>Recaptcha Site Key</b> dan <b>Secret Key</b> belum diatur. Silahkan atur terlebih dahulu pada menu <a href="<?=site_url('settings/general')?>"><b>Pengaturan / Umum</b></a>. Untuk mendapatkan <b>Recaptcha Site Key</b> dan <b>Recaptcha Secret Key</b> silahkan ikuti tutorial <a href="https://sekolahku.web.id/read/5/mengaktifkan-recaptcha" target="_blank"> disini</a>.</li>
					<?php } ?>
				</ul>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/messages');?>">
						<span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Pesan Masuk</span>
						<span class="info-box-number"><?=$widget_box->messages;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/post_comments');?>">
						<span class="info-box-icon bg-green"><i class="fa fa-comments-o"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Komentar</span>
						<span class="info-box-number"><?=$widget_box->comments;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/posts');?>">
						<span class="info-box-icon bg-yellow"><i class="fa fa-edit"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Tulisan</span>
						<span class="info-box-number"><?=$widget_box->posts;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/pages');?>">
						<span class="info-box-icon bg-red"><i class="fa fa-file-o"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Halaman</span>
						<span class="info-box-number"><?=$widget_box->pages;?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/post_categories');?>">
						<span class="info-box-icon bg-red"><i class="fa fa-list-ul"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Kategori <br>Tulisan</span>
						<span class="info-box-number"><?=$widget_box->categories;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/tags');?>">
						<span class="info-box-icon bg-yellow"><i class="fa fa-tags"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Tags</span>
						<span class="info-box-number"><?=$widget_box->tags;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/links');?>">
						<span class="info-box-icon bg-green"><i class="fa fa-link"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Tautan</span>
						<span class="info-box-number"><?=$widget_box->links;?></span>
					</div>
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<a href="<?=site_url('blog/quotes');?>">
						<span class="info-box-icon bg-aqua"><i class="fa fa-quote-right"></i></span>
					</a>
					<div class="info-box-content">
						<span class="info-box-text">Kutipan</span>
						<span class="info-box-number"><?=$widget_box->quotes;?></span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="box">
					<div class="box-header">
						<div class="box-title">
							INFORMASI PENERIMAAN <?=strtoupper(__session('_student'))?> BARU
						</div>
					</div>
					<div class="box-body p-0">
						<table class="table table-striped" style="border:0;">
							<tbody>
								<tr>
									<td width="20%"><i class="fa fa-sign-out text-red"></i> <?=__session('_academic_year')?> Aktif</td>
									<td width="1px">:</td>
									<td><?=__session('current_academic_year')?> / <?=__session('current_academic_semester')=='odd' ? 'Ganjil':'Genap'?></td>
									<td width="30%"><i class="fa fa-sign-out text-red"></i> Tahun Penerimaan <?=__session('_student')?> Baru</td>
									<td width="1px">:</td>
									<td><?=__session('admission_year')?></td>
								</tr>
								<tr>
									<td><i class="fa fa-sign-out text-red"></i> Gelombang Pendaftaran</td>
									<td>:</td>
									<td><?=__session('admission_phase')?></td>
									<td><i class="fa fa-sign-out text-red"></i> Status Penerimaan <?=__session('_student')?> Baru</td>
									<td>:</td>
									<td><?=__session('admission_status') == 'open' ? 'Dibuka':'Ditutup'?></td>
								</tr>
								<tr>
									<td><i class="fa fa-sign-out text-red"></i> Tanggal Mulai Pendaftaran</td>
									<td>:</td>
									<td><?=indo_date(__session('admission_start_date'))?></td>
									<td><i class="fa fa-sign-out text-red"></i> Tanggal Selesai Pendaftaran</td>
									<td>:</td>
									<td><?=indo_date(__session('admission_end_date'))?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

	<?php } ?>
	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="box">
				<div class="box-header">
					<div class="box-title">
						PROFIL <?=__session('school_level') >= 5 ? 'KAMPUS' : 'SEKOLAH'?>
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-5 control-label">Nama <?=__session('school_level') >= 5 ? 'Kampus' : 'Sekolah'?> :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('school_name')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">NPSN :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('npsn')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label"><?=__session('_headmaster')?> :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('headmaster')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Alamat Jalan :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('street_address')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Dusun :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('sub_village')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Kelurahan / Desa :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('village')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Kecamatan :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('sub_district')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Kota/Kabupaten :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('district')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Telp :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('phone')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Fax :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('fax')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Email :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('email')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Website :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('website')?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-5 control-label">Kode Pos :</label>
							<div class="col-sm-7">
								<p class="form-control-static"><?=__session('postal_code')?></p>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="box-title">
						INFORMASI SITUS
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-sm-4 control-label">Sistem Operasi</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=$this->agent->platform();?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Browser</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=$this->agent->browser();?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Versi PHP</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=phpversion();?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Versi Database</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=ucwords($this->db->platform()).' '.$this->db->version();?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Tanggal Server</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=indo_date(date('Y-m-d'));?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Jam Server</label>
							<div class="col-sm-8">
								<p class="form-control-static"><?=date('H:i:s');?></p>
							</div>
						</div>

					</form>

				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="box-title">
						LOGIN PENGGUNA
					</div>
				</div>
				<div class="box-body p-0">
					<table class="table table-striped" style="border:0;">
						<tbody>
							<?php foreach($last_logged_in->result() as $row) { ?>
								<tr>
									<td><?=$row->full_name;?></td>
									<td><i class="fa fa-calendar"></i> <?=$row->last_logged_in;?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<div class="box">
				<div class="box-header">
					<div class="box-title">
						TULISAN TERBARU
					</div>
				</div>
				<div class="box-body">
					<ul class="products-list product-list-in-box">
						<?php $posts = get_latest_posts(10); foreach($posts->result() as $row) { ?>
							<li class="item">
								<span class="product-description">
									Oleh <?=$row->post_author?> &sdot; Pada <?=date('d/m/Y H:i', strtotime($row->created_at))?> &sdot; Dilihat <?=$row->post_counter?> kali
								</span>
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" target="_blank" class="product-title"><?=$row->post_title;?></a>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
			<div class="box">
				<div class="box-header">
					<div class="box-title">
						KOMENTAR TERBARU
					</div>
				</div>
				<div class="box-body">
					<ul class="products-list product-list-in-box">
						<?php foreach($recent_comments->result() as $row) { ?>
							<li class="item">
								<span class="product-description">
									Pengirim : <a href="<?=$row->comment_url;?>" target="_blank"><?=$row->comment_author;?></a> on <a href="<?=site_url('read/'.$row->comment_post_id.'/'.$row->post_slug);?>" target="_blank"><?=$row->post_title;?></a>
								</span>
								<span><?=strip_tags($row->comment_content);?></span>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>
