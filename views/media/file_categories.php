<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'FILE_CATEGORIES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'media/file_categories',
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
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Kategori File', renderer:'category_name' },
      { header:'Keterangan', renderer:'category_description' },
      { header:'Slug', renderer:'category_slug' }
   ]
});

new FormBuilder( _form , {
   controller:'media/file_categories',
   fields: [
      { label:'Kategori File', name:'category_name' },
      { label:'Keterangan', name:'category_description' }
   ]
});
</script>
