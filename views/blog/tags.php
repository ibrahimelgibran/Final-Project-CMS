<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'TAGS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'blog/tags',
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
      { header:'Tag', renderer:'tag' },
      { header:'Slug', renderer:'slug' }
   ]
});

new FormBuilder( _form , {
   controller:'blog/tags',
   fields: [
      { label:'Tag', name:'tag' }
   ]
});
</script>
