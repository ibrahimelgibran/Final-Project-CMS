<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
	var _grid = 'POSTS';
	new GridBuilder( _grid , {
		controller:'posts',
		fields: [
			{
				header: '<i class="fa fa-edit"></i>',
				renderer: function( row ) {
					if (row.post_status == 'draft') {
						return Ahref(_BASE_URL + 'posts/create/' + row.id, 'Edit');
					}
					return '';
				},
				exclude_excel: true,
				sorting: false
			},
			{
				header: '<i class="fa fa-search"></i>',
				renderer: function( row ) {
					return A('find_id(' + row.id +')', 'Lihat Pesan', '<i class="fa fa-search"></i>');
				},
				exclude_excel: true,
				sorting: false
			},
			{ header:'Judul', renderer:'post_title' },
			{ header:'Penulis', renderer:'post_author' },
			{
				header:'Status',
				renderer: function( row ) {
					return row.post_status.charAt(0).toUpperCase() + row.post_status.slice(1);
				},
				sort_field: 'post_status'
			},
			{ header:'Tanggal', renderer:'created_at', type:'date' }
		],
		can_add: false,
		can_delete: false,
		can_restore: false,
		resize_column: 3,
		extra_buttons: '<a class="btn btn-default btn-sm add" href="' + _BASE_URL + 'posts/create'+'" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i></a>'
	});

	function find_id( id ) {
		$.post(_BASE_URL + 'posts/find_id', {id:id}, function(response) {
			$('.modal-form').modal({
				show:true
			});
			var post_content = response.post_content;
			$('.modal-body').empty().html('<p>' + post_content + '</p>');
			$('.modal-title').empty().html('<i class="fa fa-search" aria-hidden="true"></i> Tulisan');
		});
	}
</script>
