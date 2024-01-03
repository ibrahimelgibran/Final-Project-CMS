<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
input[type="checkbox"] {
	width: 20px;
	height: 20px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".select2").select2({ width: '100%' });
	showAcademicYear();
	getFromSourceList();
	getFromTargetList();
});

// Chek / unchek All Checkbox
function check_all(checked, el) {
	$('input[name="' + el + '"]').prop('checked', checked);
}

function showAcademicYear() {
	var class_group_id = $('#from_class_group_id').val();
	if (class_group_id !== 'unset' && class_group_id !== 'show_all') {
		$('.from_academic_year_id').show();
		$('table.origin_class').empty();
		$('.set-alumni').show();
	} else {
		$('.from_academic_year_id').hide();
		$('.set-alumni').hide();
	}
	getFromSourceList();
}

// Set As Alumni
function SetAsAlumni() {
	var academic_year = $('#from_academic_year_id option:selected').text();
	var end_date = academic_year.split('-')[ 1 ];
	var rows = $('input[name="checkbox-origin-class"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data ' + _STUDENT + ' yang terceklis akan diatur sebagai alumni tahun ' + end_date +' ?', 'Konfirmasi').then(function() {
			var values = {
				student_ids: student_ids.join(','),
				end_date: end_date
			};
			$.post(_BASE_URL + 'academic/class_group_students/set_as_alumni', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				getFromSourceList();
				getFromTargetList();
			});
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang terpilih');
	}
}

/**
* Get Origin Data From Class Group Settings
*/
function getFromSourceList() {
	$('.origin-class-overlay').show();
	var values = {
		academic_year_id: $('#from_academic_year_id').val(),
		class_group_id: $('#from_class_group_id').val()
	}
	$.post(_BASE_URL + 'academic/class_group_students/get_students', values, function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		if (rows.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-origin-class\')" /></th>';
			str += '<th width="30px">NO</th>';
			str += '<th>NOMOR INDUK ' + _STUDENT + '</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for (var z in rows) {
				var row = rows[ z ];
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-origin-class" value="' + row.id + '" /></td>';
				str += '<td>' + no + '.</td>';
				str += '<td>' + row.identity_number + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
		}
		$('table.origin_class').empty().html(str);
		$('.origin-class-overlay').hide();
	});
}

// Save To Destination Class
function saveToDestinationClass() {
	var from_class_group_id = $('#from_class_group_id').val();
	var to_class_group_id = $('#to_class_group_id').val();
	var from_academic_year_id = $('#from_academic_year_id').val();
	var to_academic_year_id = $('#to_academic_year_id').val();
	if (from_class_group_id == to_class_group_id && from_academic_year_id == to_academic_year_id) {
		_H.Notify('warning', 'Kelas dan ' + _ACADEMIC_YEAR + ' tujuan tidak boleh sama dengan Kelas dan ' + _ACADEMIC_YEAR + ' asal.');
	} else {
		var class_name = $('#to_class_group_id option:selected').text();
		var academic_year = $('#to_academic_year_id option:selected').text();
		var rows = $('input[name="checkbox-origin-class"]:checked');
		var student_ids = [];
		rows.each(function() {
			student_ids.push($(this).val());
		});
		if (student_ids.length) {
			eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data ' + _STUDENT + ' yang terceklis akan diatur sebagai ' + _STUDENT + ' Kelas ' + class_name + ' ' + _ACADEMIC_YEAR + ' ' + academic_year + ' ?', 'Konfirmasi').then(function() {
				$('.origin-class-overlay').show();
				var values = {
					student_ids: student_ids.join(','),
					academic_year_id:$('#to_academic_year_id').val(),
					class_group_id:$('#to_class_group_id').val()
				};
				$.post(_BASE_URL + 'academic/class_group_students/save_to_destination_class', values, function( response ) {
					var res = _H.StrToObject( response );
					_H.Notify(res.status, res.message);
					getFromSourceList();
					getFromTargetList();
					$('.origin-class-overlay').hide();
				});
			});
		} else {
			_H.Notify('info', 'Tidak ada data yang terpilih');
		}
	}
}

// Delete Permanent Data
function DeleteFromDestinationClass() {
	var rows = $('input[name="checkbox-destination-class"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data ' + _STUDENT + ' yang terceklis akan dihapus ?', 'Konfirmasi').then(function() {
			$('.destination-class-overlay').show();
			var values = {
				student_ids: student_ids.join(','),
				academic_year_id:$('#to_academic_year_id').val(),
				class_group_id:$('#to_class_group_id').val()
			};
			$.post(_BASE_URL + 'academic/class_group_students/delete_permanently', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				if (res.status == 'success') {
					getFromSourceList();
					getFromTargetList();
				}
				$('.destination-class-overlay').hide();
			});
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang terpilih');
	}
}

/**
* Get Destination Data From Class Group Settings
*/
function getFromTargetList() {
	$('.destination-class-overlay').show();
	var values = {
		academic_year_id:$('#to_academic_year_id').val(),
		class_group_id:$('#to_class_group_id').val()
	}
	$.post(_BASE_URL + 'academic/class_group_students/get_students', values, function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		if (rows.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-destination-class\')" /></th>';
			str += '<th width="30px">NO</th>';
			str += '<th>' + _IDENTITY_NUMBER + '</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for (var z in rows) {
				var row = rows[ z ];
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-destination-class" value="' + row.id + '" /></td>';
				str += '<td>' + no + '</td>';
				str += '<td>' + row.identity_number + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
		}
		$('table.destination_class').empty().html(str);
		$('.destination-class-overlay').hide();
	});
}
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<p>'.$sub_title.'</p>' : ''?>
   </div>
</section>
<section class="content">
	<div class="callout callout-primary">
		<h4>Petunjuk Singkat</h4>
		<ul>
			<li>Untuk mengatur rombongan belajar, silahkan pilih <strong>KELAS ASAL</strong> pada tabel sebelah kiri.</li>
			<li>Peserta Didik yang ditampilkan hanya peserta didik dengan status <strong>"Aktif"</strong></li>
			<li>Jika kelas asal yang dipilih yaitu <strong>"BELUM DIATUR"</strong> maka akan menampilkan semua peserta didik yang belum pernah diatur kelasnya. Biasanya digunakan untuk menampilkan peserta didik baru.</li>
			<li>Jika yang dipilih <strong>"TAMPILKAN SEMUA"</strong>, maka akan menampilkan semua peserta didik yang aktif, termasuk peserta didik baru yang belum pernah diatur kelasnya.</li>
			<li>Jika yang dipilih <strong>"NAMA KELASNYA"</strong>, maka akan diminta untuk memilih <strong>"<?=strtoupper(__session('_academic_year'))?>"</strong></li>
			<li>Setelah data peserta didik tampil, silahkan <strong>"CEKLIS"</strong> peserta didik yang akan diatur kelasnya</li>
			<li>Setelah selesai memilih peserta didik yang akan dipindah kelasnya, silahkan pilih <strong>"KELAS TUJUAN"</strong> dan <strong>"<?=strtoupper(__session('_academic_year'))?>"</strong> pada tabel sebelah kanan.</li>
			<li>Terakhir klik tombol <strong>"PINDAH KE KELAS TUJUAN"</strong></li>
			<li>Jika terjadi kelasahan penginputan data, silahkan pilih <strong>"KELAS TUJUAN"</strong> dan <strong>"<?=strtoupper(__session('_academic_year'))?>"</strong> pada tabel sebelah kanan, kemudian ceklis peserta didik yang akan dihapus dari kelas tersebut dan terakhir klik tombol <strong>"HAPUS"</strong></li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<i class="fa fa-sign-out"></i>
					<div class="box-tools">
						<button class="btn btn-sm btn-danger set-alumni" onclick="SetAsAlumni()"><i class="fa fa-graduation-cap"></i> ATUR SEBAGAI ALUMNI</button>
						<button class="btn btn-sm btn-primary" onclick="saveToDestinationClass()"><i class="fa fa-arrow-right"></i> PINDAH KE KELAS TUJUAN</button>
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="class_group_id" class="col-sm-4 control-label">Pilih Kelas Asal</label>
							<div class="col-sm-8">
								<?=form_dropdown('class_group_id', ['unset' => 'Belum Diatur', 'show_all' => 'Tampilkan Semua'] + $class_group_dropdown, '', 'class="form-control select2" id="from_class_group_id" onchange="showAcademicYear()"');?>
							</div>
						</div>
						<div class="form-group from_academic_year_id" style="display: none;">
							<label for="from_academic_year_id" class="col-sm-4 control-label"><?=__session('_academic_year')?></label>
							<div class="col-sm-8">
								<?=form_dropdown('academic_year_id', $academic_year_dropdown, __session('current_academic_year_id'), 'class="form-control select2" id="from_academic_year_id" onchange="getFromSourceList()"');?>
							</div>
						</div>
					</form>
					<table class="table table-striped table-condensed origin_class"></table>
				</div>
				<div class="overlay origin-class-overlay" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<i class="fa fa-sign-out"></i>
					<div class="box-tools">
						<div class="btn-group">
							<button class="btn btn-sm btn-danger" onclick="DeleteFromDestinationClass()"><i class="fa fa-trash"></i> HAPUS</button>
						</div>
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="class_group_id" class="col-sm-4 control-label">Pilih Kelas Tujuan</label>
							<div class="col-sm-8">
								<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="to_class_group_id" onchange="getFromTargetList()"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="from_academic_year_id" class="col-sm-4 control-label"><?=__session('_academic_year')?></label>
							<div class="col-sm-8">
								<?=form_dropdown('academic_year_id', $academic_year_dropdown, __session('current_academic_year_id'), 'class="form-control select2" id="to_academic_year_id" onchange="getFromTargetList()"');?>
							</div>
						</div>
					</form>
					<table class="table table-striped table-condensed destination_class"></table>
				</div>
				<div class="overlay destination-class-overlay" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
			</div>
		</div>
	</div>
</section>
