<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<style type="text/css">
input[type="checkbox"] {
	width: 20px;
	height: 20px;
}
.table > thead > tr {
	background-color: #f9f9f9;
	border-bottom: 1px solid #d2d6de;
}
.table > thead > tr > th {
	text-align: center;
}
.table > thead > tr > th, .table > tbody > tr > td {
	border: 1px solid #d2d6de;
}
.number {
	text-align: right;
	font-weight: bold;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".select2").select2({ width: '100%' });
});

// Chek / unchek All Checkbox
function check_all(checked) {
	$('input[name="checkbox"]').prop('checked', checked);
}

/*
* @var Page Number
*/
var page_number = 0;

/*
* @var Per Page
*/
var per_page = 10;

/*
* @var Total Page
*/
var total_page = 0;

/*
* @var Total Rows
*/
var total_rows = 0;

/*
* button next
*/
function Next() {
	_H.Loading( true );
	page_number++;
	getProspectiveStudents();
};

/*
* button previous
*/
function Prev() {
	_H.Loading( true );
	page_number--;
	getProspectiveStudents();
};

/*
* button first
*/
function First() {
	_H.Loading( true );
	page_number = 0;
	getProspectiveStudents();
};

/*
* button last
*/
function Last() {
	_H.Loading( true );
	page_number = total_page - 1;
	getProspectiveStudents();
};

/*
* render Pagination Info
*/
PaginationInfo = function PaginationInfo() {
	var page_info = 'Page ' + ((total_rows == 0) ? 0 : (page_number + 1));
	page_info += ' of ' + total_page.to_money();
	page_info += ' &sdot; Total : ' + total_rows.to_money() + ' Rows.';
	$('.page-info').html(page_info);
};

/*
* Set pagination button
*/
function PaginationButton() {
	$('.box-footer').show();
	$('.next').attr('onclick', 'Next()');
	$('.previous').attr('onclick', 'Prev()');
	$('.first').attr('onclick', 'First()');
	$('.last').attr('onclick', 'Last()');
	$('.per-page').attr('onchange', 'SetPerPage()');
	$(".previous, .first").prop('disabled', page_number == 0);
	$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
};

/*
* select per-page
*/
function SetPerPage() {
	_H.Loading( true );
	page_number = 0;
	per_page = $('.per-page option:selected').val();
	getProspectiveStudents();
};

/**
* Get Origin Data From Class Group Settings
*/
function getProspectiveStudents() {
	_H.Loading( true );
	$('.save-excel').attr('disabled', 'disabled');
	var values = {
		admission_year_id: $('#admission_year_id').val(),
		admission_type_id: $('#admission_type_id').val(),
		major_id: $('#major_id').val() || 0,
		page_number: page_number,
		per_page: per_page
	};
	$.post(_BASE_URL + 'admission/selection_process/get_prospective_students', values, function( response ) {
		var res = _H.StrToObject( response );
		total_page = res.total_page;
		total_rows = res.total_rows;
		var students = res.students;
		var results = [];
		for (var x in students) {
			var row = {};
			var student = students[ x ];
			row['student_id'] = student.id;
			row['registration_number'] = student.registration_number;
			row['full_name'] = student.full_name;
			row['first_choice'] = student.first_choice;
			row['second_choice'] = student.second_choice;
			results.push(row);
		}
		var str = '';
		if (results.length) {
			PaginationButton();
			PaginationInfo();
			if (total_rows <= per_page) $(".next").prop('disabled', true);
			$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
			// Sort by Total
			results.sort(function(a,b) {
				if (a.total < b.total) return 1;
				if (a.total > b.total) return -1;
				return 0;
			});
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px" class="exclude_excel"><input type="checkbox" onclick="check_all(this.checked)" /></th>';
			str += '<th width="30px">NO.</th>';
			str += '<th>NO. DAFTAR</th>';
			str += '<th>NAMA LENGKAP</th>';
			if (_MAJOR_COUNT > 0) {
				str += '<th>PILIHAN I</th>';
				str += '<th>PILIHAN II</th>';
			}
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = (page_number * per_page) + 1;
			for (var z in results) {
				var res = results[ z ];
				str += '<tr>';
				str += '<td class="exclude_excel"><input type="checkbox" name="checkbox" value="' + res.student_id + '" /></td>';
				str += '<td>' + no + '.</td>';
				str += '<td>' + res.registration_number + '</td>';
				str += '<td>' + res.full_name + '</td>';
				if (_MAJOR_COUNT > 0) {
					str += '<td>' + res.first_choice + '</td>';
					str += '<td>' + res.second_choice + '</td>';
				}
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('table.source_list').empty().html(str);
			$('.save-excel').removeAttr('disabled');
		} else {
			$('table.source_list').empty();
		}
		_H.Loading( false );
	});
}

// Delete Permanent Data
function SelectionProcess() {
	var rows = $('input[name="checkbox"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		var selection_result = $('#selection_result option:selected').text();
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data calon ' + _STUDENT + ' baru akan diproses dengan hasil seleksi ' + selection_result + ' ?', 'Konfirmasi').then(function() {
			_H.Loading( true );
			var values = {
				admission_year_id: $('#admission_year_id').val(),
				admission_type_id: $('#admission_type_id').val(),
				selection_result: $('#selection_result').val(),
				student_ids: student_ids.join(',')
			};
			$.post(_BASE_URL + 'admission/selection_process/save', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				if (res.status == 'success') {
					getProspectiveStudents();
				}
				_H.Loading( false );
			});
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang terpilih');
	}
}

function save2excel() {
	var elementId = 'excel-report';
	var div = '<div id="' + elementId + '" style="display: none;"></div>';
	$( div ).appendTo( document.body );
	var table = $( '#data-table-renderer' ).html();
	$( '#' + elementId ).html( table );
	var fileName = 'REKAP-DATA-' + (_SCHOOL_LEVEL >= 5 ? 'PMB':'PPDB') + '-TAHUN-' + $('#admission_year_id option:selected').text() + '-JALUR-PENDAFTARAN-' + $('#admission_type_id option:selected').text();
	fileName += ($('#major_id').length ? '-PROGRAM-KEAHLIAN-' + $('#major_id option:selected').text() : '');
	ExportToExcel( elementId, fileName ); // Export to Excel
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
	<?php if (__session('major_count') > 0) { ?>
		<div class="callout callout-primary">
			<h4>Petunjuk Singkat</h4>
			<ul>
				<li><strong><?=__session('_major')?></strong> yang dipilih akan merujuk pada <strong>Pilihan I</strong> dari data <strong>Calon <?=__session('_student')?> Baru</strong>.</li>
			</ul>
		</div>
	<?php } ?>
	<div class="box">
		<div class="box-body">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="admission_year_id" class="col-sm-4 control-label"><?=__session('school_level') >= 5 ? 'PMB' : 'PPDB';?> Tahun</label>
							<div class="col-md-8">
								<?=form_dropdown('admission_year_id', $admission_year_dropdown, '', 'class="form-control select2" id="admission_year_id"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="admission_type_id" class="col-sm-4 control-label">Jalur Pendaftaran</label>
							<div class="col-md-8">
								<?=form_dropdown('admission_type_id', $admission_type_dropdown, '', 'class="form-control select2" id="admission_type_id"');?>
							</div>
						</div>
						<?php if (__session('major_count') > 0) { ?>
							<div class="form-group">
								<label for="major_id" class="col-sm-4 control-label"><?=__session('_major')?></label>
								<div class="col-md-8">
									<?=form_dropdown('major_id', $major_dropdown, '', 'class="form-control select2" id="major_id"');?>
								</div>
							</div>
						<?php } ?>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="button" onclick="getProspectiveStudents(); return false;" class="btn btn-sm btn-info"><i class="fa fa-search"></i> CARI DATA</button>
								<button disabled="disabled" type="button" onclick="save2excel(); return false;" class="btn btn-sm btn-warning save-excel"><i class="fa fa-file-excel-o"></i> EXPORT KE FILE EXCEL</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="selection_result" class="col-sm-4 control-label">Hasil Seleksi</label>
							<div class="col-md-8 col-sm-12 col-xs-12">
								<?=form_dropdown('selection_result', $options, '', 'class="form-control select2" id="selection_result"');?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="button" onclick="SelectionProcess(); return false;" class="btn btn-sm btn-success"><i class="fa fa-save"></i> SIMPAN HASIL SELEKSI</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div id="data-table-renderer" class="table-responsive">
				<table class="table table-bordered table-striped table-condensed source_list"></table>
			</div>
		</div>
		<div class="box-footer" style="display: none;">
			<div class="row">
				<div class="col-md-9">
					<em class="page-info"></em>
				</div>
				<div class="col-md-3">
					<div class="btn-group pull-right">
						<button type="button" class="btn bg-navy btn-sm first" title="First"><i class="fa fa-angle-double-left"></i></button>
						<button type="button" class="btn bg-navy btn-sm previous" title="Prev"><i class="fa fa-angle-left"></i></button>
						<button type="button" class="btn bg-navy btn-sm next" title="Next"><i class="fa fa-angle-right"></i></button>
						<button type="button" class="btn bg-navy btn-sm last" title="Last"><i class="fa fa-angle-double-right"></i></button>
						<div class="btn-group">
							<select class="btn bg-navy input-sm per-page" style="padding: 5px 5px" onchange="SetPerPage()">
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="0">All</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
