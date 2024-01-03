<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'USER_GROUPS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'users/user_groups',
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
      { header:'Grup Pengguna', renderer:'user_group' },
   ]
});

new FormBuilder( _form , {
   controller:'users/user_groups',
   fields: [
      { label:'Grup Pengguna', name:'user_group' }
   ]
});
</script>
