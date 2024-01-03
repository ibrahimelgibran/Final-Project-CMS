<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.Questions = _H.StrToObject('<?=$parent_data;?>');
var _grid = 'ANSWERS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'plugins/answers',
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
      { header:'Pertanyaan', renderer:'question' },
      { header:'Jawaban', renderer:'answer' },
   ]
});

new FormBuilder( _form , {
   controller:'plugins/answers',
   fields: [
      { label:'Pertanyaan', name:'question_id', type:'select', datasource:DS.Questions },
      { label:'Jawaban', name:'answer' }
   ]
});
</script>
