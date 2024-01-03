<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/nestable/jquery.nestable.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/nestable/jquery.nestable.js')?>"></script>
<script type="text/javascript">
DS.MenuTypes = {
	'modules': 'Modul',
	'links': 'Tautan',
	'post_categories':'Kategori Tulisan',
	'file_categories': 'Kategori File',
	'pages': 'Halaman'
};

// Global Variable Menus
window.serialize_menus = null;

/**
* Edit Menu
* @param Number
*/
function OnEdit( id ) {
	$('.modal-form').modal('show');
	$('#record_id').val(id);
	$.post(_BASE_URL + 'appearance/menus/find_id', {'id':id}, function( response ) {
		var res = _H.StrToObject( response );
		$('#menu_title').val(res.menu_title);
		$('#menu_url').val(res.menu_url);
		$('#menu_target option[value="' + res.menu_target + '"]').attr('selected','selected');
		$('#is_deleted option[value="' + res.is_deleted + '"]').attr('selected','selected');
	});
}

/**
* Update Menu
*/
function SubmitForm() {
	var values = {
		menu_title: $('#menu_title').val(),
		menu_url: $('#menu_url').val(),
		menu_target: $('#menu_target').val(),
		is_deleted: $('#is_deleted').val(),
		id: $('#record_id').val()
	};
	_H.Loading( true );
	$.post(_BASE_URL + 'appearance/menus/save', values, function( response ) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		if (res.status == 'success') {
			_H.Notify('success', _H.Message(res.message));
			$('.modal-form').modal('hide');
			get_menus();
		} else {
			_H.Notify('error', _H.Message(res.message));
		}
	});
}

/**
* Save Custom Links
*/
function save_links() {
	var values = {
		menu_url: $('#c_menu_url').val(),
		menu_title: $('#c_menu_title').val(),
		menu_target: $('#c_menu_target').val()
	};
	if (values['menu_url'] && values['menu_title'] && values['menu_target']) {
		_H.Loading( true );
		$.post(_BASE_URL + 'appearance/menus/save_links', values, function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			$('#c_menu_url, #c_menu_title').val('');
			nested_menus();
		});
	} else {
		_H.Notify('warning', 'Form belum diisi');
	}
}

/**
* Save Menu From Pages
*/
function save_pages() {
	var inputs = $('#list-pages').find('input[type="checkbox"]:checked');
	var ids = [];
	inputs.each(function() {
		var value = $(this).val();
		ids.push(value);
	});
	var values = {
		'ids': ids.join(',')
	};
	if (ids.length) {
		_H.Loading( true );
		$.post(_BASE_URL + 'appearance/menus/save_pages', values, function( response ) {
			_H.Loading( false );
			inputs.each(function() {
				$( this ).prop('checked', false);
			});
			$( '.checkall-pages' ).prop('checked', false);
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			nested_menus();
			get_menus();
		});
	} else {
		_H.Notify('warning', 'Tidak ada item yang terpilih');
	}
}

/**
* Save Menu From Posts Categories
*/
function save_post_categories() {
	var inputs = $('#list-post-categories').find('input[type="checkbox"]:checked');
	var ids = [];
	inputs.each(function() {
		var value = $(this).val();
		ids.push(value);
	});
	var values = {
		'ids': ids.join(',')
	};
	if (ids.length) {
		_H.Loading( true );
		$.post(_BASE_URL + 'appearance/menus/save_post_categories', values, function( response ) {
			_H.Loading( false );
			inputs.each(function() {
				$( this ).prop('checked', false);
			});
			$( '.checkall-post-categories' ).prop('checked', false);
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			nested_menus();
			get_menus();
		});
	} else {
		_H.Notify('warning', 'Tidak ada item yang terpilih');
	}
}

/**
* Save Menu From File Categories
*/
function save_file_categories() {
	var inputs = $('#list-file-categories').find('input[type="checkbox"]:checked');
	var ids = [];
	inputs.each(function() {
		var value = $(this).val();
		ids.push(value);
	});
	var values = {
		'ids': ids.join(',')
	};
	if (ids.length) {
		_H.Loading( true );
		$.post(_BASE_URL + 'appearance/menus/save_file_categories', values, function( response ) {
			_H.Loading( false );
			inputs.each(function() {
				$( this ).prop('checked', false);
			});
			$( '.checkall-file-categories' ).prop('checked', false);
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			nested_menus();
			get_menus();
		});
	} else {
		_H.Notify('warning', 'Tidak ada item yang terpilih');
	}
}

/**
* Save List Modules
*/
function save_modules() {
	var inputs = $('#modules').find('input[type="checkbox"]:checked');
	var modules = [];
	inputs.each(function() {
		var value = $(this).val();
		modules.push(value);
	});
	var values = {
		'modules': modules.join(',')
	};
	if (modules.length) {
		_H.Loading( true );
		$.post(_BASE_URL + 'appearance/menus/save_modules', values, function( response ) {
			_H.Loading( false );
			inputs.each(function() {
				$( this ).prop('checked', false);
			});
			$( '.checkall-modules' ).prop('checked', false);
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			nested_menus();
			get_menus();
		});
	} else {
		_H.Notify('warning', 'Tidak ada item yang terpilih');
	}
}

/**
* Get Pages
*/
function get_pages() {
	$('.overlay-pages').show();
	$.get(_BASE_URL + 'appearance/menus/get_pages', function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		for(var z in rows) {
			var row = rows[ z ];
			str += '<div class="checkbox">'
			+ '<label>'
			+ '<input type="checkbox" class="list-pages" value="' + row.id +'">' + row.post_title
			+ '</label>'
			+ '</div>';
		}
		$('#list-pages').html( str );
		$('.overlay-pages').hide();
	});
}

/**
* Get Post Categories
*/
function get_post_categories() {
	$('.overlay-post-categories').show();
	$.get(_BASE_URL + 'appearance/menus/get_post_categories', function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		for(var z in rows) {
			var row = rows[ z ];
			str += '<div class="checkbox">'
			+ '<label>'
			+ '<input type="checkbox" class="list-post-categories" value="' + row.id +'">' + row.category_name
			+ '</label>'
			+ '</div>';
		}
		$('#list-post-categories').html( str );
		$('.overlay-post-categories').hide();
	});
}

/**
* Get File Categories
*/
function get_file_categories() {
	$('.overlay-file-categories').show();
	$.get(_BASE_URL + 'appearance/menus/get_file_categories', function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		for(var z in rows) {
			var res = rows[ z ];
			str += '<div class="checkbox">'
			+ '<label>'
			+ '<input type="checkbox" class="list-file-categories" value="' + res.id +'">' + res.category_name
			+ '</label>'
			+ '</div>';
		}
		$('#list-file-categories').html( str );
		$('.overlay-file-categories').hide();
	});
}

/**
* Get All Menus
*/
function get_menus() {
	$.get(_BASE_URL + 'appearance/menus/get_menus', function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '<table class="table table-hover table-striped table-condensed">'
		+ '<thead>'
		+ '<tr>'
		+ '<th width="10px">No.</th>'
		+ '<th>Menu</th>'
		+ '<th>URL</th>'
		+ '<th>Type</th>'
		+ '<th>Aktif</th>'
		+ '<th width="10px"></th>'
		+ '<th width="10px"></th>'
		+ '</tr>'
		+ '</thead>'
		+ '<tbody>';
		var no = 1;
		for (var z in rows) {
			var row = rows[ z ];
			str += '<tr>'
			+ '<td>' + no + '.</td>'
			+ '<td>' + row.menu_title + '</td>'
			+ '<td>' + row.menu_url + '</td>'
			+ '<td>' + DS.MenuTypes[ row.menu_type ] + '</td>'
			+ '<td>' + (row.is_deleted == 'true' ? '<i class="fa fa-warning text-warning"></i>':'<i class="fa fa-check text-green"></i>') + '</td>'
			+ '<td><a class="text-info" href="javascript:void(0)" onclick="OnEdit(' + row.id + ')"><i class="fa fa-edit"></i></a></td>'
			+ '<td><a class="text-danger" href="javascript:void(0)" onclick="delete_permanently(' + row.id + ')"><i class="fa fa-trash"></i></a></td>'
			+ '</tr>';
			no++;
		}
		str += '</tbody>'
		+ '</table>';
		$('#list-menus').html(str);
	});
}

/**
* Delete Menus
* @param Number
*/
function delete_permanently( id ) {
	eModal.confirm('Apakah anda yakin akan menghapus menu ?', 'Konfirmasi').then(function() {
		$.post( _BASE_URL + 'appearance/menus/delete_permanently', {id:id}, function( response ) {
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			get_menus();
			nested_menus();
		});
	});
}

/**
* Delete All Menus
*/
function truncate_table() {
	eModal.confirm('Apakah anda yakin akan menghapus semua menu ?', 'Konfirmasi').then(function() {
		$.get(_BASE_URL + 'appearance/menus/truncate_table', function( response ) {
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			get_menus();
			nested_menus();
		});
	});
}

/**
* Nested List for drag n drop menu position
*/
function nested_menus() {
	$.get(_BASE_URL + 'appearance/menus/nested_menus', function( response ) {
		var menus = _H.StrToObject( response );
		var NestedList = function( menus ) {
			var str = '';
			for ( var z in menus ) {
				var menu = menus[ z ];
				str += '<li class="dd-item" data-id="' + menu.id + '">';
				str += '<div class="dd-handle">'+ menu.menu_title.toUpperCase() +'</div>';
				var sub_menu = NestedList(menu.children);
				if (sub_menu) {
					str += '<ol class="dd-list">' + sub_menu + '</ol>';
				}
				str += '</li>';
			}
			return str;
		}
		if ( menus.length ) {
			var menu = '<ol class="dd-list">';
			menu += NestedList( menus );
			menu += '</ol>';
			$( '.dd' ).html( menu );
		}
	});
}

/**
* Check all checkbox
* @param String
* @param Boolean
* Check all checkbox
*/
function checkAll( target, isTrue ) {
	$( document ).find('input[type="checkbox"].' + target).prop('checked', isTrue);
}

/**
* Save Menu
*/
function save_menu_position() {
	_H.Loading( true );
	var serialize_menus = window.serialize_menus;
	$.post(_BASE_URL + 'appearance/menus/save_menu_position', {"menus":serialize_menus}, function( response ) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		_H.Notify(res.status, res.message);
	});
}

$( document ).ready(function() {
	get_pages();
	get_post_categories();
	get_file_categories();
	nested_menus();
	get_menus();
	var updateOutput = function(e) {
		var list = e.length ? e : $(e.target), output = list.data('output');
		if ( window.JSON ) window.serialize_menus = window.JSON.stringify(list.nestable('serialize'));
	};
	$('#nestable').nestable().on('change', updateOutput);
});
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<small>'.$sub_title.'</small>' : ''?>
   </div>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-4">
			<div class="box box-primary box-solid collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> TAUTAN</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body">
					<div class="form-group">
						<label for="menu_url">URL</label>
						<input type="text" class="form-control" id="c_menu_url">
					</div>
					<div class="form-group">
						<label for="menu_title">Link Text</label>
						<input type="text" class="form-control" id="c_menu_title">
					</div>
					<div class="form-group">
						<label for="menu_target">Target</label>
						<select class="form-control" id="c_menu_target">
							<option value="_blank">Blank</option>
							<option value="_self">Self</option>
							<option value="_parent">Parent</option>
							<option value="_top">Top</option>
						</select>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" onclick="save_links(); return false;" class="btn btn-sm btn-primary pull-right"><i class="fa fa-save"></i> SIMPAN</button>
				</div>
			</div>
			<div class="box box-primary box-solid collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> HALAMAN</h3>
					<div class="box-tools pull-right">
						<button onclick="get_pages()" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Reload" class="btn btn-box-tool"><i class="fa fa-refresh"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body" id="list-pages"></div>
				<div class="overlay overlay-pages" style="display: none">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-md-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" onclick="checkAll('list-pages', this.checked)"> Pilih Semua
								</label>
							</div>
						</div>
						<div class="col-md-8">
							<div class="btn-group pull-right">
								<button onclick="save_pages()" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> SIMPAN</button>
								<a href="<?=site_url('blog/pages')?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary box-solid collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> KATEGORI TULISAN</h3>
					<div class="box-tools pull-right">
						<button onclick="get_post_categories()" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Reload" class="btn btn-box-tool"><i class="fa fa-refresh"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body" id="list-post-categories"></div>
				<div class="overlay overlay-post-categories" style="display: none">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-md-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" onclick="checkAll('list-post-categories', this.checked)"> Pilih Semua
								</label>
							</div>
						</div>
						<div class="col-md-8">
							<div class="btn-group pull-right">
								<button onclick="save_post_categories()" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> SIMPAN</button>
								<a href="<?=site_url('blog/post_categories')?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary box-solid collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> KATEGORI FILE</h3>
					<div class="box-tools pull-right">
						<button onclick="get_file_categories()" type="button" data-toggle="tooltip" data-placement="top" data-original-title="Reload" class="btn btn-box-tool"><i class="fa fa-refresh"></i></button>
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body" id="list-file-categories"></div>
				<div class="overlay overlay-file-categories" style="display: none">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-md-4">
							<div class="checkbox">
								<label>
									<input type="checkbox" onclick="checkAll('list-file-categories', this.checked)"> Pilih Semua
								</label>
							</div>
						</div>
						<div class="col-md-8">
							<div class="btn-group pull-right">
								<button onclick="save_file_categories()" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> SIMPAN</button>
								<a href="<?=site_url('media/file_categories')?>" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box box-primary box-solid collapsed-box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> MODUL</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body" id="modules">
					<?php
					foreach(modules() as $key => $value) {
						echo '<div class="checkbox">';
						echo '<label>';
						echo '<input type="checkbox" class="modules" value="'.$key.'">'.$value;
						echo '</label>';
						echo '</div>';
					}
					?>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-md-6">
							<div class="checkbox">
								<label>
									<input type="checkbox" onclick="checkAll('modules', this.checked)"> Pilih Semua
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<button onclick="save_modules()" type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-save"></i> SIMPAN</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#menu_structure" data-toggle="tab" aria-expanded="true"><i class="fa fa-sort-alpha-asc"></i> STRUKTUR MENU</a></li>
					<li><a href="#menu_manager" data-toggle="tab" aria-expanded="false"><i class="fa fa-wrench"></i> KELOLA MENU</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="menu_structure">
						<div class="cf nestable-lists">
							<div class="dd" id="nestable"></div>
						</div>
						<button onclick="save_menu_position()" style="margin-top:20px;" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> SIMPAN STRUKTUR MENU</button>
					</div>
					<div class="tab-pane" id="menu_manager">
						<div id="list-menus" style="margin-bottom:10px;"></div>
						<button onclick="truncate_table(); return false;" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> HAPUS SEMUA MENU</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal modal-form">
	<div class="modal-dialog modal-lg">
		<form class="form-horizontal form-dialog" role="form">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-edit"></i> EDIT MENU</h4>
				</div>
				<div class="modal-body">
					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-4 control-label" for="menu_title">Title</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-sm" id="menu_title" name="menu_title">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="menu_url">URL</label>
							<div class="col-sm-8">
								<input type="text" class="form-control input-sm" id="menu_url" name="menu_url">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="target">Target</label>
							<div class="col-sm-8">
								<select id="menu_target" class="form-control">
									<option value="_selft">Self</option>
									<option value="_blank">Blank</option>
									<option value="_top">Top</option>
									<option value="_parent">Parent</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label" for="target">Aktif ?</label>
							<div class="col-sm-8">
								<select id="is_deleted" class="form-control">
									<option value="false">Ya</option>
									<option value="true">Tidak</option>
								</select>
							</div>
						</div>
						<input type="hidden" name="record_id" id="record_id">
					</div>
					<div class="form-group" style="margin-top: 10px;padding: 10px 0;">
						<div class="btn-group col-md-8 col-md-offset-4">
							<button type="button" class="btn btn-primary btn-sm" onclick="SubmitForm(); return false;"><i class="fa fa-save"></i> UPDATE</button>
							<button class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-mail-forward"></i> CANCEL</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
