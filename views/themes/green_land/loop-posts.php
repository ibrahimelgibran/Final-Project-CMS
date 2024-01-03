<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
	function strip_tags(input) {
		return input.replace(/(<([^>]+)>)/ig,"");
	}
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('div.more-posts').remove();
		}
	});

	function get_posts() {
		page++;
		var segment_1 = '<?=$this->uri->segment(1)?>';
		var segment_2 = '<?=$this->uri->segment(2)?>';
		var segment_3 = '<?=$this->uri->segment(3)?>';
		var url = '';
		var data = {
			page_number: page
		};
		if (segment_1 == 'kategori') {
			data['category_slug'] = segment_2;
			url = _BASE_URL + 'public/post_categories/get_posts';
		} else if (segment_1 == 'tag') {
			data['tag'] = segment_2;
			url = _BASE_URL + 'public/post_tags/get_posts';
		} else if (segment_1 == 'arsip') {
			data['year'] = segment_2;
			data['month'] = segment_3;
			url = _BASE_URL + 'public/archives/get_posts';
		}
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( url, data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="card rounded-0 border border-secondary mb-3 posts">';
					str += '<div class="row">';
					str += '<div class="col-md-5">';
					str += '<img src="' + _BASE_URL + 'media_library/posts/medium/' + row.post_image + '" class="card-img rounded-0">';
					str += '</div>';
					str += '<div class="col-md-7">';
					str += '<div class="card-body p-3">';
					str += '<h5 class="card-title"><a href="' + _BASE_URL + 'read/' + row.id + '/' + row.post_slug + '">' + row.post_title + '</a></h5>';
					str += '<p class="card-text mb-0">' + strip_tags(row.post_content, '').substr(0, 165) + '</p>';
					str += '<div class="d-flex justify-content-between align-items-center mt-1">';
					str += '<small class="text-muted">';
					str += row.created_at.substr(8, 2) + '/' + row.created_at.substr(5, 2) + '/' + row.created_at.substr(0, 4);
					str += ' ' + row.created_at.substr(11, 5);
					str += ' - Oleh ' + row.post_author;
					str += ' - Dilihat ' + row.post_counter + ' kali';
					str += '</small>';
					str += '<a href="' + _BASE_URL + 'read/' + row.id + '/' + row.post_slug + '" class="btn btn-sm action-button rounded-0"><i class="fa fa-search"></i></a>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				}
				var elementId = $("div.posts:last");
				$( str ).insertAfter( elementId );
				if (page == parseInt(total_page)) $('div.more-posts').remove();
			});
		}
	}
</script>
<div class="col-lg-8 col-md-8 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<?php foreach($query->result() as $row) { ?>
		<div class="card rounded-0 border border-secondary mb-3 posts">
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
	<div class="justify-content-between align-items-center float-right mb-3 w-100 more-posts">
		<button type="button" onclick="get_posts()" class="btn action-button rounded-0 float-right"><i class="fa fa-refresh"></i> Tampilkan Lebih Banyak</button>
	</div>
</div>
<?php $this->load->view('themes/green_land/sidebar')?>
