<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
   <div class="row">
      <div class="col-md-2">
         <div class="box box-primary">
            <div class="box-body box-profile">
               <img class="profile-user-img img-responsive" <?=(isset($photo) && $photo) ? ('src="'.$photo.'"') : '' ?> alt="User profile picture">
               <h3 class="profile-username text-center"><?=$student->full_name?></h3>
               <p class="text-muted text-center"><?=$student->street_address?></p>
            </div>
         </div>
      </div>
      <div class="col-md-10">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">REGISTRASI <?=strtoupper(__session('_student'))?></a></li>
               <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">DATA PRIBADI</a></li>
               <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">DATA AYAH KANDUNG</a></li>
               <li class=""><a href="#tab_4" data-toggle="tab" aria-expanded="false">DATA IBU KANDUNG</a></li>
               <li class=""><a href="#tab_5" data-toggle="tab" aria-expanded="false">DATA WALI</a></li>
               <li class=""><a href="#tab_6" data-toggle="tab" aria-expanded="false">DATA PERIODIK</a></li>
               <?php if ($scholarships && $scholarships->num_rows() > 0) { ?>
                  <li class=""><a href="#tab_7" data-toggle="tab" aria-expanded="false">BEASISWA</a></li>
               <?php } ?>
               <?php if ($achievements && $achievements->num_rows() > 0) { ?>
                  <li class=""><a href="#tab_8" data-toggle="tab" aria-expanded="false">PRESTASI</a></li>
               <?php } ?>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="tab_1">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <?php if ($this->uri->segment(1) == 'admission') { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Nomor Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->registration_number?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=date('d M Y', strtotime($student->created_at))?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jenis Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->is_transfer?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Jalur Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->admission_type?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Gelombang Pendaftaran</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->phase_name?></p>
                              </div>
                           </div>

                           <?php if (__session('major_count') > 0) { ?>
                              <div class="form-group">
                                 <label class="col-sm-4 control-label">Pilihan I</label>
                                 <div class="col-sm-8">
                                    <p class="form-control-static"><?=$student->first_choice?></p>
                                 </div>
                              </div>

                              <div class="form-group">
                                 <label class="col-sm-4 control-label">Pilihan II</label>
                                 <div class="col-sm-8">
                                    <p class="form-control-static"><?=$student->second_choice?></p>
                                 </div>
                              </div>
                           <?php } ?>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Prestasi yang Pernah Diraih</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=$student->achievement?></p>
                              </div>
                           </div>

                        <?php } ?>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->identity_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Siswa Nasional / NISN</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nisn?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Sekolah Asal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_school_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alamat Sekolah Asal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_school_address?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Ujian Nasional Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_exam_number?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Pernah PAUD?</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->paud?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Apakah Pernah TK?</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->tk?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Seri SKHUN Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->skhun?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Seri Ijazah Sebelumnya</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->prev_diploma_number?></p>
                           </div>
                        </div>

                        <?php if ($this->uri->segment(2) == 'alumni') { ?>
                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Masuk</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=_isValidDate($student->start_date) ? indo_date($student->start_date) : ''?></p>
                              </div>
                           </div>

                           <div class="form-group">
                              <label class="col-sm-4 control-label">Tanggal Keluar</label>
                              <div class="col-sm-8">
                                 <p class="form-control-static"><?=_isValidDate($student->end_date) ? indo_date($student->end_date) : ''?></p>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_2">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Lengkap</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->full_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jenis Kelamin</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->gender?></p>
                           </div>
                        </div>



                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Induk Kependudukan/KTP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->nik?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->birth_place?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tanggal Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->birth_date?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Agama</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->religion?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->special_need?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Alamat Jalan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->street_address?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RT</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rt?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">RW</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->rw?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Dusun</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sub_village?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Kelurahan/Desa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->village?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kecamatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sub_district?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kota/Kabupaten</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->district?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kode Pos</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->postal_code?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tempat Tinggal</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->residence?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Moda Transportasi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->transportation?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Handphone</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mobile_phone?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Telepon</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->phone?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Email Pribadi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->email?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Indonesia Sehat (KIS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kis?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Indonesia Pintar (KIP)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kip?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Pra Sejahtera (KPS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kps?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Kartu Keluarga Sejahtera (KKS)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->kks?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nomor Surat Keterangan Tidak Mampu (SKTM)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sktm?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kewarganegaraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->citizenship?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Negara</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->country?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Hobi</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->hobby?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Cita-Cita</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->ambition?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_3">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_birth_year?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_monthly_income?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ayah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->father_special_need?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_4">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_birth_year?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_monthly_income?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Kebutuhan Khusus Ibu</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mother_special_need?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_5">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Nama Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_name?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tahun Lahir Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_birth_year?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pendidikan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_education?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Pekerjaan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_employment?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Penghasilan Bulanan Wali</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->guardian_monthly_income?></p>
                           </div>
                        </div>

                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_6">
                  <form class="form-horizontal">
                     <div class="box-body">

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Tinggi Badan (Cm)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->height?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Berat Badan (Kg)</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->weight?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jarak Tempat Tinggal ke <?=__session('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->mileage?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Waktu Tempuh ke <?=__session('school_level') >= 5 ? 'Kampus' : 'Sekolah'?></label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->traveling_time?></p>
                           </div>
                        </div>

                        <div class="form-group">
                           <label class="col-sm-4 control-label">Jumlah Saudara Kandung</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$student->sibling_number?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <?php if ($scholarships && $scholarships->num_rows() > 0) { ?>
                  <div class="tab-pane" id="tab_7">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th>NAMA BEASISWA</th>
                                 <th>JENIS BEASISWA</th>
                                 <th>TAHUN MULAI</th>
                                 <th>TAHUN SELESAI</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($scholarships->result() as $row) { ?>
                                 <tr>
                                    <td><?=$row->scholarship_description?></td>
                                    <td><?=scholarship_types($row->scholarship_type)?></td>
                                    <td><?=$row->scholarship_start_year?></td>
                                    <td><?=$row->scholarship_end_year?></td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
               <?php if ($achievements && $achievements->num_rows() > 0) { ?>
                  <div class="tab-pane" id="tab_8">
                     <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                           <thead>
                              <tr>
                                 <th width="10px">NO</th>
                                 <th>NAMA PRESTASI</th>
                                 <th>JENIS PRESTASI</th>
                                 <th>TINGKAT</th>
                                 <th>TAHUN</th>
                                 <th>PENYELENGGARA</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $no = 1; foreach($achievements->result() as $row) { ?>
                                 <tr>
                                    <td align="center"><?=$no?></td>
                                    <td><?=$row->achievement_description?></td>
                                    <td><?=achievement_types($row->achievement_type)?></td>
                                    <td><?=achievement_levels($row->achievement_level)?></td>
                                    <td><?=$row->achievement_year?></td>
                                    <td><?=$row->achievement_organizer?></td>
                                 </tr>
                              <?php $no++; } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </section>
