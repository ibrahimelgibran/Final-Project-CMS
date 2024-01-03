<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('.more-albums').remove();
		}
	});
	function get_albums() {
		page++;
		var data = {
			page_number: page
		};
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( _BASE_URL + 'public/gallery_photos/get_albums', data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="col-md-4 mb-4 albums">';
					str += '<div class="card h-100 shadow border border-secondary rounded-0">';
					str += '<img src="' + _BASE_URL + 'media_library/albums/' + row.album_cover + '" class="card-img-top rounded-0">';
					str += '<div class="card-body">';
					str += '<h5 class="card-title">' + row.album_title + '</h5>';
					str += '<p class="card-text">' + row.album_description + '</p>';
					str += '<div class="d-flex justify-content-between align-items-center">';
					str += '<button type="button" onclick="photo_preview(' + row.id + ')" class="btn action-button rounded-0"><i class="fa fa-search"></i></button>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				}
				var elementId = $("div.albums:last");
				$( str ).insertAfter( elementId );
				if (page == parseInt(total_page)) $('.more-albums').remove();
			});
		}
	}
</script>
<div class="col-lg-12 col-md-12 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<div class="row">
		<?php foreach($query->result() as $row) { ?>
			<div class="col-md-4 mb-4 albums">
				<div class="card h-100 shadow border border-secondary rounded-0">
					<img src="<?=base_url('media_library/albums/'.$row->album_cover)?>" class="ard-img rounded-0 img-fluid p-2">
					<div class="card-body pb-2">
						<h5 class="card-title"><?=$row->album_title?></h5>
						<p class="card-text text-justify"><?=$row->album_description?></p>
					</div>
					<div class="card-footer">
						<button type="button" onclick="photo_preview(<?=$row->id?>)" class="btn action-button rounded-0 float-right"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="justify-content-between align-items-center float-right mb-3 w-100 more-albums">
		<button type="button" onclick="get_albums(); return false;" class="btn action-button rounded-0 float-right"><i class="fa fa-refresh"></i> Tampilkan Lebih Banyak</button>
	</div>
</div>
