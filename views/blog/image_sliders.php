<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php $this->load->view('backend/grid_index');?>
<script type="text/javascript">
   var _grid = 'IMAGE_SLIDERS', _form = _grid + '_FORM';
   new GridBuilder( _grid , {
      controller:'blog/image_sliders',
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
         {
            header: '<i class="fa fa-file-image-o"></i>',
            renderer: function( row ) {
               return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Image');
            },
            exclude_excel: true,
            sorting: false
         },
         {
            header: '<i class="fa fa-search-plus"></i>',
            renderer: function( row ) {
               var image = "'" + row.image + "'";
               return row.image ?
               '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
            },
            exclude_excel: true,
            sorting: false
         },
         { header:'Keterangan', renderer:'caption' }
      ],
      resize_column: 5
   });

   new FormBuilder( _form , {
      controller:'blog/image_sliders',
      fields: [
         { label:'Keterangan', name:'caption', type:'textarea' }
      ]
   });

   function preview(image) {
      $.magnificPopup.open({
         items: {
            src: _BASE_URL + 'media_library/image_sliders/' + image
         },
         type: 'image'
      });
   }
</script>
