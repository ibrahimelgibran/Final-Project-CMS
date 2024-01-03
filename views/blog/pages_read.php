<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
   var _grid = 'PAGES', _form = _grid + '_FORM';
   new GridBuilder( _grid , {
      controller:'blog/pages',
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
               return Ahref(_BASE_URL + 'blog/pages/create/' + row.id,'Edit');
            },
            exclude_excel: true,
            sorting: false
         },
         {
            header: '<i class="fa fa-calendar"></i>',
            renderer: function( row ) {
               return A(_form + '.OnEdit(' + row.id + ')', 'Ubah Tanggal Posting', '<i class="fa fa-calendar"></i>');
            },
            exclude_excel: true,
            sorting: false
         },
         {
            header: '<i class="fa fa-search"></i>',
            renderer: function( row ) {
               return A('find_id(' + row.id +')', 'Lihat Pesan', '<i class="fa fa-search"></i>');
            },
            exclude_excel: true,
            sorting: false
         },
         { header:'Judul', renderer:'post_title' },
         { header:'Penulis', renderer:'post_author' },
         { header:'Tanggal', renderer:'created_at', type:'date' }
      ],
      can_add: false,
      resize_column: 5,
      extra_buttons: '<a class="btn btn-default btn-sm add" href="' + _BASE_URL + 'blog/pages/create'+'" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i></a>'
   });

   new FormBuilder( _form , {
      controller:'blog/pages',
      fields: [
         { label:'Tanggal Posting', name:'created_at', type:'datetime' }
      ],
      save_action: 'set_created_at'
   });

   function find_id( id ) {
      $.post(_BASE_URL + 'blog/pages/find_id', {id:id}, function(response) {
         $('.modal-preview').modal({
            show:true
         });
         var post_content = response.post_content;
         $('.modal-preview .modal-body').empty().html('<p>' + post_content + '</p>');
         $('.modal-preview .modal-title').empty().html('<i class="fa fa-search" aria-hidden="true"></i> Halaman');
      });
   }
</script>
