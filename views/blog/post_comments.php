<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
   var _grid = 'POST_COMMENTS', _form = _grid + '_FORM';
   new GridBuilder( _grid , {
      controller:'blog/post_comments',
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
            exclude_excel: true ,
            sorting: false
         },
         {
            header: '<i class="fa fa-comments-o"></i>',
            renderer: function( row ) {
               return A('find_id(' + row.id +')', 'Lihat Komentar', '<i class="fa fa-comments-o"></i>');
            },
            exclude_excel: true,
            sorting: false
         },
         { header:'Penulis', renderer:'comment_author' },
         { header:'Email', renderer:'comment_email' },
         { header:'URL', renderer:'comment_url' },
         {
            header:'Komentar Untuk',
            renderer: function( row ) {
               if ( row.post_title ) {
                  return '<a target="_blank" href="' + _BASE_URL + 'read/' + row.comment_post_id + '/' + row.post_slug + '">' + row.post_title + '</a>';
               }
               return '';
            },
            sort_field: 'post_title'
         },
         { header:'Tanggal Kirim', renderer:'created_at' },
         {
            header:'Status',
            renderer: function( row ) {
               return DS.CommentStatus[ row.comment_status ];
            },
            sort_field: 'comment_status'
         },
      ],
      resize_column: 4,
      can_add: false
   });

   new FormBuilder( _form , {
      controller:'blog/post_comments',
      fields: [
         { label:'Komentar', name:'comment_content', type:'textarea' },
         { label:'Balas Komentar', name:'comment_reply', type:'textarea' },
         { label:'Status', name:'comment_status', type:'select', datasource:DS.CommentStatus },
      ]
   });

   function find_id( id ) {
      $.post(_BASE_URL + 'blog/post_comments/find_id', {id:id}, function(response) {
         $('.modal-preview').modal({
            show:true
         });
         var message = response.comment_content;
         $('.modal-preview .modal-body').empty().html('<p>' + message + '</p>');
         $('.modal-preview .modal-title').empty().html('<i class="fa fa-comments-o" aria-hidden="true"></i> Komentar');
      });
   }
</script>
