<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'MODULES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'users/modules',
   fields: [
      {
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Modul', renderer:'module_name' },
      { header:'Keterangan', renderer:'module_description' }
   ],
   can_add:false,
   can_delete:false,
   can_restore:false,
   resize_column:2
});

new FormBuilder( _form , {
   controller:'users/modules',
   fields: [
      { label:'Modul', name:'module_name' },
      { label:'Keterangan', name:'module_description' }
   ]
});
</script>
