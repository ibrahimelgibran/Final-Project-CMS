<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'PHOTOS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'media/photos',
   fields: [
      {
         header: '<input type="checkbox" class="check-all">',
         renderer: function( row ) {
            return CHECKBOX(row.id, 'id');
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header:'PHOTO',
         renderer: function( row ) {
            return '<img width="120px" src="' + _BASE_URL + 'media_library/albums/' + row.photo_name + '">';
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Album', renderer:'album_title' }
   ],
   can_add:false,
   can_restore:false,
   resize_column: 2,
   extra_buttons: '<a href="' + _BASE_URL + 'media/albums' + '" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Album Foto"><i class="fa fa-mail-forward"></i></a>'
});
</script>
