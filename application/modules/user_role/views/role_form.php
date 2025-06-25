<style>
    .table tr td {
        vertical-align: middle !important;
    }
</style>

<div class="main-content">
    <section class="section">
    	<form class="access-form" action="<?= $formURL ?>" method="post" enctype="multipart/form-data">
    		<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Manajemen User > User Role > <?= $title ?></span>
					</div>
				</div>
		        <div class="row">
		        	<div class="col-12 col-lg-6">
		        		<div class="card">
							<div class="card-body">
								<div class="form-group row">
									<label for="group_name">Nama Role</label>
									<input type="text" class="form-control" id="role_name" name="role_name" value="<?= !empty($roles->name) ? $roles->name : '' ?>" placeholder="Input Nama Role" required="">
								</div>
							</div>
						</div>
		        	</div>

		        	<div class="col-12 col-lg-6">
		        		<div class="card">
							<div class="card-header">
								<h5>Akses Menu</h5>
							</div>
							<div class="card-body">
								<div id="accordion1">
									<?php foreach($accessListNew as $moduleName => $moduleClass) : ?>
						            <div class="card mb-2">
						            	<button type="button" class="btn btn-link text-white shadow-none" data-toggle="collapse" data-target="#collapse-<?= $moduleName ?>" aria-expanded="true" aria-controls="collapse-<?= $moduleName ?>" style="background-image: linear-gradient(to right, #28a745, #00a889, #00a4cf, #0096fe, #007bff) !important;">
							                <div class="card-header p-0">
							                      <span><?= str_replace("_", " ", $moduleName) ?></span>
							                </div>
							            </button>

						                <div id="collapse-<?= $moduleName ?>" class="collapse" data-parent="#accordion1">
						                  <div class="card-body">
												<?php foreach($moduleClass as $class => $valClass) :?>
													<?php if ($class != "0") { ?>
													<div class="row">
														<div class="col-6">
															<p style="font-size: 12px;"><?= str_replace("_", " ", strtoupper(str_replace("access", " ", $class))) ?>
														</div>
														<div class="col-1 text-center">
															<p> : </p>
														</div>
														<div class="col-5">
															<div class="icheck-material-danger icheck-inline">
																<input type="radio" id="radio-<?= $moduleName ?>-<?= $class ?>-off" name="access[<?= $moduleName ?>][<?= $class?>]" value="off" checked=""/>
																<label for="radio-<?= $moduleName ?>-<?= $class ?>-off">OFF</label>
															</div>
															<div class="icheck-material-success icheck-inline">
																<input type="radio" id="radio-<?= $moduleName ?>-<?= $class ?>-on" name="access[<?= $moduleName ?>][<?= $class?>]" value="on" <?= @$access->{$moduleName}->{$class} == 'on' ? 'checked=""' : ''; ?> />
																<label for="radio-<?= $moduleName ?>-<?= $class ?>-on">ON</label>
															</div>
														</div>
													</div>
													<?php } ?>
												<?php endforeach; ?>
						                  </div>
						                </div>
						            </div>
						        	<?php endforeach; ?>
					            </div>
							</div>
						</div>
		        	</div>
		    	</div>
		    </div>
	    	<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="col-12 col-md-12 col-lg-12 text-right">
							<div class="form-group">
								<button class="btn btn-info" type="submit"> Simpan <i class="fas fa-save"></i>
								</button>&nbsp;&nbsp;
								<a href="<?= site_url('user_role') ?>" class="btn btn-dark" ng-click="redirect()" type="button"> Kembali <i class="fas fa-undo-alt"></i>
								</a>&nbsp;&nbsp;
							</div>
						</div>

					</div>
				</div>
			</div>
	    </form>
    </section>
</div>