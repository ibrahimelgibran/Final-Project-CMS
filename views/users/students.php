<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'USERS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'users/students',
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
            return A(_form + '.OnEdit(' + row.id + ')', 'Ubah Kata Sandi', '<i class="fa fa-edit"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Nama Pengguna', renderer:'user_name' },
      { header:'Nama Lengkap', renderer:'user_full_name' },
      { header:'Email', renderer:'user_email' }
   ],
   can_add: false
});

new FormBuilder( _form , {
   controller:'users/students',
   fields: [
      { label:'Kata Sandi', name:'user_password', type:'password' },
      { label:'Ulangi Kata Sandi', name:'password_confirm', type:'password' }
   ]
});
</script>
