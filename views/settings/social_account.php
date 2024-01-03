<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'OPTIONS',
_form1 = _grid + '_FORM1', // facebook
_form2 = _grid + '_FORM2', // linked_in
_form3 = _grid + '_FORM3', // instagram
_form4 = _grid + '_FORM4', // twitter
_form5 = _grid + '_FORM5'; // youtube
new GridBuilder( _grid , {
   controller:'settings/social_account',
   fields: [
      {
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            if (row.setting_variable == 'facebook') {
               return A(_form1 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'linked_in') {
               return A(_form2 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'instagram') {
               return A(_form3 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'twitter') {
               return A(_form4 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'youtube') {
               return A(_form5 + '.OnEdit(' + row.id + ')');
            }
         },
         exclude_excel: true,
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

/**
 * facebook
 */
new FormBuilder( _form1 , {
   controller:'settings/social_account',
   fields: [
      { label:'Facebook', name:'setting_value' }
   ]
});

/**
 * linked_in
 */
new FormBuilder( _form2 , {
   controller:'settings/social_account',
   fields: [
      { label:'Linked In', name:'setting_value' }
   ]
});

/**
 * instagram
 */
new FormBuilder( _form3 , {
   controller:'settings/social_account',
   fields: [
      { label:'Instagram', name:'setting_value' }
   ]
});

/**
 * twitter
 */
new FormBuilder( _form4 , {
   controller:'settings/social_account',
   fields: [
      { label:'Twitter', name:'setting_value' }
   ]
});

/**
 * youtube
 */
new FormBuilder( _form5 , {
   controller:'settings/social_account',
   fields: [
      { label:'Youtube', name:'setting_value' }
   ]
});
</script>
