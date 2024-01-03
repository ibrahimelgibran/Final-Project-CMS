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
		filterSubjects();
		getSubjects();
		getCourseClasses();
	});

	// Chek / unchek All Checkbox
	function check_all(checked, el) {
		$('input[name="' + el + '"]').prop('checked', checked);
	}

	 // Source Filter Form
	function filterSubjects() {
		var copy_data = $('#copy_data').val();
		if (copy_data == 'true') {
			$('.from_academic_year_id').show();
			$('.from_semester').show();
			$('.from_class_group_id').show();
		} else {
			$('.from_academic_year_id').hide();
			$('.from_semester').hide();
			$('.from_class_group_id').hide();
		}
		getSubjects();
	}

	/**
	* Get Origin Data From Course Classes
	*/
	function getSubjects() {
		$('.source-overlay').show();
		var values = {
			copy_data: $('#copy_data').val(),
			academic_year_id: $('#from_academic_year_id').val(),
			semester: $('#from_semester').val(),
			class_group_id: $('#from_class_group_id').val()
		}
		$.post(_BASE_URL + 'academic/course_classes/get_subjects', values, function( response ) {
			var res = _H.StrToObject( response );
			var subjects = res.subjects;
			var str = '';
			if (subjects.length) {
				str += '<thead class="header">';
				str += '<tr>';
				str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-source-data\')" /></th>';
				str += '<th width="30px">NO</th>';
				str += '<th>' + _SUBJECT + '</th>';
				str += '</tr>';
				str += '</thead>';
				str += '<tbody>';
				var no = 1;
				for (var z in subjects) {
					var row = subjects[ z ];
					str += '<tr>';
					str += '<td><input type="checkbox" name="checkbox-source-data" value="' + row.id + '" /></td>';
					str += '<td>' + no + '.</td>';
					str += '<td>' + row.subject_name + '</td>';
					str += '</tr>';
					no++;
				}
				str += '</tbody>';
			}
			$('table.source-data').empty().html(str);
			$('.source-overlay').hide();
		});
	}

	// Save To Destination Subjects
	function Save() {
		var from_academic_year_id = $('#from_academic_year_id').val();
		var to_academic_year_id = $('#to_academic_year_id').val();
		var from_semester = $('#from_semester').val();
		var to_semester = $('#to_semester').val();
		var from_class_group_id = $('#from_class_group_id').val();
		var to_class_group_id = $('#to_class_group_id').val();
		var copy_data = $('#copy_data').val();
		if (copy_data == 'true') {
			if (from_class_group_id == to_class_group_id && from_semester == to_semester && from_academic_year_id == to_academic_year_id) {
				_H.Notify('warning', _ACADEMIC_YEAR + ', semester dan kelas asal tidak boleh sama dengan ' + _ACADEMIC_YEAR + ', semester dan kelas tujuan.');
				return;
			}
		}
		var academic_year = $('#to_academic_year_id option:selected').text();
		var semester = $('#to_semester option:selected').text();
		var class_name = $('#to_class_group_id option:selected').text();
		var rows = $('input[name="checkbox-source-data"]:checked');
		var subject_ids = [];
		rows.each(function() {
			 subject_ids.push($(this).val());
		});
		if (subject_ids.length) {
			eModal.confirm('Apakah anda yakin ' + subject_ids.length + ' ' + _SUBJECT + ' yang terceklis akan disimpan sebagai ' + _SUBJECT + ' pada <b>' + _ACADEMIC_YEAR + ' ' + academic_year +' Semester ' + semester + ' Kelas  ' + class_name + '</b> ?', 'Konfirmasi').then(function() {
			$('.source-overlay').show();
			var values = {
				subject_ids: subject_ids.join(','),
				academic_year_id: to_academic_year_id,
				semester:to_semester,
				class_group_id: to_class_group_id
			};
			$.post(_BASE_URL + 'academic/course_classes/save', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				getSubjects();
				getCourseClasses();
				$('.source-overlay').hide();
			});
		});
		} else {
			_H.Notify('info', 'Tidak ada data yang terpilih');
		}
	 }

	// Delete /Restore
	function changeDeletedStatus(is_deleted) {
		var rows = $('input[name="checkbox-target-data"]:checked');
		var ids = [];
		rows.each(function() {
			ids.push($(this).val());
		});
		if (ids.length) {
			var academic_year = $('#to_academic_year_id option:selected').text();
			var semester = $('#to_semester option:selected').text();
			var class_name = $('#to_class_group_id option:selected').text();
			var message = 'Data ' + _SUBJECT + ' ini berelasi dengan data kehadiran ' + (_SCHOOL_LEVEL >= 5 ? 'dosen' : 'guru') + ' dan ' + _STUDENT.toUpperCase() + ', jika data ini dihapus maka <b><font color="red">DATA KEHADIRAN ' + (_SCHOOL_LEVEL >= 5 ? 'DOSEN' : 'GURU') + ' DAN ' + _STUDENT.toUpperCase() + ' AKAN IKUT TERHAPUS</font></b>. Apakah anda yakin ' + ids.length + ' ' + _SUBJECT + ' pada ' + _ACADEMIC_YEAR + ' <b>' + academic_year +'</b> semester <b>' + semester + '</b> kelas <b>' + class_name + '</b> yang terceklis akan dihapus ?';
			var message_type = 'Peringatan !';
			if (is_deleted == 'false') {
				message = 'Apakah anda yakin ' + ids.length + ' ' + _SUBJECT + ' pada ' + _ACADEMIC_YEAR + ' <b>' + academic_year +'</b> semester <b>' + semester + '</b> kelas <b>' + class_name + '</b> yang terceklis akan direstore ?';
				var message_type = 'Konfirmasi !';
			}
			eModal.confirm(message, message_type).then(function() {
				$('.target-overlay').show();
				var values = {
					is_deleted: is_deleted,
					ids: ids.join(','),
					academic_year_id:$('#to_academic_year_id').val(),
					semester:$('#to_semester').val(),
					class_group_id:$('#to_class_group_id').val()
				};
				$.post(_BASE_URL + 'academic/course_classes/change_deleted_status', values, function( response ) {
					var res = _H.StrToObject( response );
					_H.Notify(res.status, res.message);
					if (res.status == 'success') {
						getCourseClasses();
					}
					$('.target-overlay').hide();
				});
			});
		} else {
			_H.Notify('info', 'Tidak ada data yang terpilih');
		}
	}

	/**
	* Get Course Classes
	*/
	function getCourseClasses() {
		$('.target-overlay').show();
		var values = {
			academic_year_id: $('#to_academic_year_id').val(),
			semester: $('#to_semester').val(),
			class_group_id: $('#to_class_group_id').val()
		}
		$.post(_BASE_URL + 'academic/course_classes/get_course_classes', values, function( response ) {
			var res = _H.StrToObject( response );
			var course_classes = res.course_classes;
			var str = '';
			if (course_classes.length) {
				str += '<thead class="header">';
				str += '<tr>';
				str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-target-data\')" /></th>';
				str += '<th width="30px">NO</th>';
				str += '<th>' + _SUBJECT + '</th>';
				str += '<th>' + (_SCHOOL_LEVEL >= 5 ? 'DOSEN PENGAMPU' : 'GURU PENGAJAR') + '</th>';
				str += '</tr>';
				str += '</thead>';
				str += '<tbody>';
				var no = 1;
				for (var z in course_classes) {
					var row = course_classes[ z ];
					str += '<tr ' + (row.is_deleted == 'true' ? 'class="delete"' : '') + '>';
					str += '<td><input type="checkbox" name="checkbox-target-data" value="' + row.id + '" /></td>';
					str += '<td>' + no + '</td>';
					str += '<td>' + row.subject_name + '</td>';
					str += '<td>' + row.full_name + '</td>';
					str += '</tr>';
					no++;
				}
				str += '</tbody>';
			}
			$('table.target-data').empty().html(str);
			$('.target-overlay').hide();
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
		<p>Menu ini digunakan untuk mengatur daftar <?=__session('_subject')?> pada tiap semester. Pastikan sebelum proses pembelajaran dimulai sudah mengatur daftar <?=__session('_subject')?> yang akan diajarkan dengan cara sebagai berikut :</p>
		<ol>
			<li>Pilih <?=__session('_subject')?> dengan cara menceklis daftar <?=__session('_subject')?> pada tabel disebelah kiri.</li>
			<li>Pilih <b><?=__session('_academic_year')?>, Semester</b>, dan <b>Kelas</b> pada tabel di sebelah kanan sebagai target penyimpanan <?=__session('_subject')?> yang terpilih / terceklis.</li>
			<li>Klik tombol <b>"SIMPAN KE DAFTAR <?=(__session('school_level') >= 5 ? 'MATA KULIAH' : 'MATA PELAJARAN');?>"</b>.</li>
		</ol>
		<p>Jika ingin menduplikasi <?=__session('_subject')?> pada <?=__session('_academic_year')?>, semester, dan kelas sebelumnya, pada bagian <b>"Copy Data"</b> silahkan pilih <b>Ya</b>, kemudian pilih/ceklis <?=__session('_subject')?> dari <?=__session('_academic_year')?>, Semester, dan Kelas yang akan diduplikasi.</p>
	</div>
	<div class="row">
		<div class="col-md-5 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> PILIH <?=strtoupper(__session('_subject'))?></h3>
					<div class="box-tools">
						<div class="btn-group">
							<button class="btn btn-sm btn-success" onclick="Save()">SIMPAN KE DAFTAR <?=strtoupper(__session('_subject'))?> <i class="fa fa-arrow-right"></i></button>
						</div>
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="copy_data" class="col-sm-5 control-label">Copy Data</label>
							<div class="col-sm-7">
								<?=form_dropdown('copy_data', ['false' => 'Tidak', 'true' => 'Ya'], '', 'class="form-control select2" id="copy_data" onchange="filterSubjects()"');?>
							</div>
						</div>
						<div class="form-group from_academic_year_id" style="display: none;">
							<label for="from_academic_year_id" class="col-sm-5 control-label"><?=__session('_academic_year')?></label>
							<div class="col-sm-7">
								<?=form_dropdown('from_academic_year_id', $academic_year_dropdown, '', 'class="form-control select2" id="from_academic_year_id" onchange="filterSubjects()"');?>
							</div>
						</div>
						<div class="form-group from_semester" style="display: none;">
							<label for="from_semester" class="col-sm-5 control-label">Semester</label>
							<div class="col-sm-7">
								<?=form_dropdown('from_semester', ['odd' => 'Ganjil', 'even' => 'Genap'], '', 'class="form-control select2" id="from_semester" onchange="filterSubjects()"');?>
							</div>
						</div>
						<div class="form-group from_class_group_id" style="display: none;">
							<label for="from_class_group_id" class="col-sm-5 control-label">Kelas</label>
							<div class="col-sm-7">
								<?=form_dropdown('from_class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="from_class_group_id" onchange="filterSubjects()"');?>
							</div>
						</div>
					</form>
					<table class="table table-striped table-condensed source-data"></table>
				</div>
				<div class="overlay source-overlay" style="display: none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title"><i class="fa fa-sign-out"></i> PENGATURAN <?=strtoupper(__session('_subject'))?></h3>
					<div class="box-tools">
						<div class="btn-group">
							<button class="btn btn-sm btn-danger" onclick="changeDeletedStatus('true')"><i class="fa fa-trash"></i> DELETE</button>
							<button class="btn btn-sm btn-primary" onclick="changeDeletedStatus('false')"><i class="fa fa-mail-reply-all"></i> RESTORE</button>
						</div>
					</div>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="to_academic_year_id" class="col-sm-4 control-label"><?=__session('_academic_year')?></label>
							<div class="col-sm-8">
								<?=form_dropdown('to_academic_year_id', $academic_year_dropdown, '', 'class="form-control select2" id="to_academic_year_id" onchange="getCourseClasses()"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="to_semester" class="col-sm-4 control-label">Semester</label>
							<div class="col-sm-8">
								<?=form_dropdown('to_semester', ['odd' => 'Ganjil', 'even' => 'Genap'], '', 'class="form-control select2" id="to_semester" onchange="getCourseClasses()"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="to_class_group_id" class="col-sm-4 control-label">Kelas</label>
							<div class="col-sm-8">
								<?=form_dropdown('to_class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="to_class_group_id" onchange="getCourseClasses()"');?>
							</div>
						</div>
					</form>
					<table class="table table-striped table-condensed target-data"></table>
				</div>
				 <div class="overlay target-overlay" style="display: none;">
					 <i class="fa fa-refresh fa-spin"></i>
				</div>
			</div>
	  	</div>
	</div>
 </section>
