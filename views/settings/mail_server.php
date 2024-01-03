<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'OPTIONS',
_form1 = _grid + '_FORM1', // smtp_host
_form2 = _grid + '_FORM2', // smtp_user
_form3 = _grid + '_FORM3'; // smtp_pass
_form4 = _grid + '_FORM4'; // smtp_port

new GridBuilder( _grid , {
   controller:'settings/mail_server',
   fields: [
      {
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            if (row.setting_variable == 'smtp_host') {
               return A(_form1 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'smtp_user') {
               return A(_form2 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'smtp_pass') {
               return A(_form3 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'smtp_port') {
               return A(_form4 + '.OnEdit(' + row.id + ')');
            }
         },
         exclude_excel : true,
         sorting: false
      },
      { header:'Setting Name', renderer: 'setting_description' },
      { header:'Setting Value', renderer: 'setting_value' },
   ],
   can_add: false,
   can_delete: false,
   can_restore: false,
   resize_column: 2,
   per_page: 50,
   per_page_options: [50, 100]
});

new FormBuilder( _form1 , {
   controller:'settings/mail_server',
   fields: [
      { label:'SMTP Server Address', name:'setting_value' }
   ]
});

new FormBuilder( _form2 , {
   controller:'settings/mail_server',
   fields: [
      { label:'SMTP Username', name:'setting_value' }
   ]
});

new FormBuilder( _form3 , {
   controller:'settings/mail_server',
   fields: [
      { label:'SMTP Password', name:'setting_value' }
   ]
});

new FormBuilder( _form4 , {
   controller:'settings/mail_server',
   fields: [
      { label:'SMTP Port', name:'setting_value' }
   ]
});
</script>