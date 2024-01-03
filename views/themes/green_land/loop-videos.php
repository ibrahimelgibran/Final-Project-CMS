<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('.more-videos').remove();
		}
	});

	function get_videos() {
		page++;
		var data = {
			page_number: page
		};
		if ( page <= parseInt(total_page) ) {
			$.post( _BASE_URL + 'public/gallery_videos/get_videos', data, function( response ) {
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="col-md-4 mb-3 videos">';
					str += '<div class="embed-responsive embed-responsive-16by9">';
					str += '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' + row.post_content + '" allowfullscreen></iframe>';
					str += '</div>';
					str += '</div>';
				}
				var el = $("div.videos:last");
				$( str ).insertAfter(el);
				if (page == parseInt(total_page)) $('.more-videos').remove();
			});
		}
	}
</script>
<div class="col-lg-12 col-md-12 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<div class="row">
		<?php foreach($query->result() as $row) { ?>
			<div class="col-md-4 mb-3 videos">
				<div class="embed-responsive embed-responsive-16by9">
					<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$row->post_content?>" allowfullscreen></iframe>
				</div>
			</div>
		<?php } ?>
	</div>
	<div class="justify-content-between align-items-center float-right mb-3 w-100 more-videos">
		<button type="button" onclick="get_videos(); return false;" class="btn action-button rounded-0 float-right"><i class="fa fa-refresh"></i> Tampilkan Lebih Banyak</button>
	</div>
</div>
