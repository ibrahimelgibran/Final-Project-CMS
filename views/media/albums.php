<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<style type="text/css">
.images {
   width: 250px;
   height: 250px;
   margin-right: 10px;
   margin-bottom: 10px;
}
</style>
<?php $this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'ALBUMS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'media/albums',
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
            return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Cover Album');
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-search-plus"></i>',
         renderer: function( row ) {
            var cover = "'" + row.album_cover + "'";
            return row.album_cover ?
            '<a title="Preview Album Cover" onclick="coverPreview(' + cover + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-upload"></i>',
         renderer: function( row ) {
            return '<a title="Upload Galeri Foto"  href="'+_BASE_URL + 'media/albums/form_upload/' + row.id +'"><i class="fa fa-upload"></i></a>';
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-desktop"></i>',
         renderer: function( row ) {
            return '<a title="Slide Show" onclick="galleryPreview(' + row.id + ')"  href="#"><i class="fa fa-desktop"></i></a>';
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Judul', renderer:'album_title' },
      { header:'Keterangan', renderer:'album_description' },
      { header:'Slug', renderer:'album_slug' }
   ],
   resize_column: 7,
   extra_buttons: '<a href="' + _BASE_URL + 'media/photos' + '" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="List Images"><i class="fa fa-picture-o"></i></a>'
});

new FormBuilder( _form , {
   controller:'media/albums',
   fields: [
      { label:'Judul', name:'album_title' },
      { label:'Keterangan', name:'album_description', type:'textarea' }
   ],
   upload_action: 'cover_upload'
});

function coverPreview( image ) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/albums/' + image
      },
      type: 'image'
   });
}

function galleryPreview( id ) {
   $.post(_BASE_URL + 'media/albums/list_images', {id: id}, function( response ) {
      var res = _H.StrToObject( response );
      if(res.count > 0) {
         $.magnificPopup.open({
            items: res.items,
            gallery: {
               enabled: true
            },
            type: 'image'
         });
      } else {
         _H.Notify('info', 'No images loaded.');
      }
   });
}
</script>
