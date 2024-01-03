<div class="col-lg-12 col-md-12 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title)?></h5>
	<div class="card rounded-0 border border-secondary mb-3">
		<div class="card-body">
			<form>
				<div class="form-group row mb-2">
					<label for="full_name" class="col-sm-3 control-label">Nama Lengkap <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="full_name" name="full_name">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="gender" class="col-sm-3 control-label">Jenis Kelamin <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<?=form_dropdown('gender', ['' => 'Pilih :', 'M' => 'Laki-laki', 'F' => 'Perempuan'], '', 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="gender"')?>
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="birth_date" class="col-sm-3 control-label">Tanggal Lahir <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" readonly class="form-control form-control-sm rounded-0 border border-secondary date" id="birth_date" name="birth_date">
							<div class="input-group-append">
								<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="end_date" class="col-sm-3 control-label">Tahun Lulus <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="end_date" name="end_date">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="identity_number" class="col-sm-3 control-label"><?=__session('_identity_number')?></label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="identity_number" name="identity_number">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="street_address" class="col-sm-3 control-label">Alamat <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<textarea rows="5" class="form-control form-control-sm rounded-0 border border-secondary" id="street_address" name="street_address"></textarea>
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="email" class="col-sm-3 control-label">Email <span style="color: red">*</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="email" name="email">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="phone" class="col-sm-3 control-label">Telepon</label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="phone" name="phone">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="mobile_phone" class="col-sm-3 control-label">Handphone</label>
					<div class="col-sm-9">
						<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="mobile_phone" name="mobile_phone">
					</div>
				</div>
				<div class="form-group row mb-2">
					<label for="file" class="col-sm-3 control-label">Foto</label>
					<div class="col-sm-9">
						<input type="file" id="photo" name="photo">
						<small class="form-text text-muted">Foto harus JPG dan ukuran file maksimal 1 Mb</small>
					</div>
				</div>
				<?php if (NULL !== __session('recaptcha_status') && __session('recaptcha_status') == 'enable') { ?>
					<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
				<?php } ?>
			</form>
		</div>
		<div class="card-footer">
			<div class="form-group row mb-0">
				<div class="offset-sm-3 col-sm-9">
					<button type="button" onclick="alumni_registration(); return false;" class="btn action-button rounded-0"><i class="fa fa-send"></i> Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
