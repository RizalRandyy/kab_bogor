<body ng-app="pdam">
	<div id="app">
		<div class="main-wrapper">
			<div class="navbar-bg"></div>

			<nav class="navbar navbar-expand-lg main-navbar">
				<div class="container-fluid py-1 px-3">
					<nav aria-label="breadcrumb">
						<ul class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
							<li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg" style="color: black;"><i class="fas fa-bars"></i></a></li>
							<h6 class="font-weight-bolder mb-0" style="margin-top:7px;"><?= $title; ?></h6>
						</ul>
					</nav>
					<ul class="navbar-nav navbar-right">
						<li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
								<img alt="image" style="color: black;" src="<?= $users['photo_profile'] ?>" class="rounded-circle mr-1">
								<div class="d-sm-none d-lg-inline-block" style="color: black;">Hi, <?= $users["full_name"] ?></div>
							</a>
							<div class="dropdown-menu dropdown-menu-right">
								<div class="dropdown-title text-uppercase" style="padding-bottom: 0px !important;">
									<h6><b><?= $users["role_name"] ?></b></h6>
								</div>
								<div class="dropdown-divider"></div>
								<a href="<?= base_url("index.php/logout"); ?>" class="dropdown-item has-icon text-danger">
									<i class="fas fa-sign-out-alt"></i> Logout
								</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
			<div class="main-sidebar">
				<aside id="sidebar-wrapper">
					<div class="sidebar-brand mt-2">
						<a href="<?= base_url() ?>"><img style="width: 30%;" src="<?= base_url() ?>assets/img/Kabupaten-bogor.png" class="mr-0"> </a>
					</div>
					<div class="sidebar-brand sidebar-brand-sm">
						<a href="index.html">IT</a>
					</div>
					<ul class="sidebar-menu mt-5">
						<?php if ($users['role_access']['dashboard']['dashboard'] == 'on') { ?>
							<li class="nav-item dropdown <?= $this->uri->segment(1) == 'dashboard' ? 'active' : '' ?>">
								<a href="<?= base_url('dashboard'); ?>" class="nav-link"><i class="fas fa-chart-line"></i><span>Dashboard</span></a>
							</li>
						<?php } ?>

						<?php if ($users['role_access']['kelompok_item']['kelompok_item'] == 'on') { ?>
							<li class="nav-item dropdown <?= $this->uri->segment(1) == 'kelompok_item' || $this->uri->segment(1) == 'jenis_item' ? 'active' : '' ?>">
								<a href="#" class="nav-link has-dropdown"><i class="fas fa-comment"></i><span>Data Master</span></a>
								<ul class="dropdown-menu">
									<li class="nav-item  <?= $this->uri->segment(1) == 'kelompok_item' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('kelompok_item'); ?>">
											<span>Kelompok Item</span>
										</a>
									</li>
									<li class="nav-item  <?= $this->uri->segment(1) == 'jenis_item' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('jenis_item'); ?>">
											<span>Jenis Item</span>
										</a>
									</li>
								</ul>
							</li>
						<?php } ?>

						<?php if ($users['role_access']['dashboard']['dashboard'] == 'on') { ?>
							<li class="nav-item dropdown <?= $this->uri->segment(1) == 'card_request' ? 'active' : '' ?>">
								<a href="#" class="nav-link has-dropdown"><i class="fas fa-money-check"></i><span>Isian SSH</span></a>
								<ul class="dropdown-menu">
									<li class="nav-item  <?= $version == 'form_card_request' ? 'active' : 'noactive' ?>">
										<a class="nav-link" href="<?= base_url('card_request/form_card_request'); ?>">
											<span>ID & Biz Card Request</span>
										</a>
									</li>
									<li class="nav-item  <?= $version == 'card_management' || $version == 'assign_card_request' ? 'active' : 'noactive' ?>">
										<a class="nav-link" href="<?= base_url('card_request'); ?>">
											<span>Approval Status</span>
										</a>
									</li>

								</ul>
							</li>
						<?php } ?>

						<?php if ($users['role_access']['dashboard']['dashboard'] == 'on') { ?>
							<li class="nav-item dropdown <?= $this->uri->segment(1) == 'ticket_gs' ? 'active' : '' ?>">
								<a href="#" class="nav-link has-dropdown"><i class="fas fa-ticket-alt"></i><span>Isian HSPK</span></a>
								<ul class="dropdown-menu">
									<li class="nav-item  <?= $version == 'form_ticket_gs' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('ticket_gs/form_ticket_gs'); ?>">
											<span>Lapor GS Form</span>
										</a>
									</li>
									<li class="nav-item  <?= $version == 'ticket_gs' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('ticket_gs'); ?>">
											<span>Ticket GS</span>
										</a>
									</li>
								</ul>
							</li>
						<?php } ?>

						<li class="nav-item dropdown <?= $version == 'guest_book' || $version == 'guest_list' ? 'active' : '' ?>">
							<a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Isian ASB</span></a>
							<ul class="dropdown-menu">
								<li class="nav-item  <?= $version == 'guest_book' ? 'active' : '' ?>">
									<a class="nav-link" href="<?= base_url('guest_book') ?>">
										<span>Create Guest Book</span>
									</a>
								</li>
								<!-- <li class="nav-item  <?= $version == 'assign' ? 'active' : '' ?>">
								<a class="nav-link" href="<?= base_url('guest_book/assign') ?>">
									<span>Assign Guest Book</span>
								</a>
							</li> -->
								<li class="nav-item  <?= $version == 'guest_list' ? 'active' : '' ?>">
									<a class="nav-link" href="<?= base_url('guest_book/guest_list') ?>">
										<span>Manage Guest Book</span>
									</a>
								</li>

							</ul>
						</li>

						<li class="nav-item dropdown <?= $version == 'user_role' || $version == 'user_manage' ? 'active' : '' ?>">
							<a href="managements" class="nav-link has-dropdown"><i class="fas fa-users-cog"></i><span>Manajemen Pengguna</span></a>
							<ul class="dropdown-menu">
								<?php if (!empty($users['role_access']['user_manage']) && $users['role_access']['user_manage']['user_manage'] == 'on') { ?>
									<li class="nav-item  <?= $version == 'user_manage' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('user_manage') ?>"><span>User</span>
										</a>
									</li>
								<?php } ?>
								<?php if (!empty($users['role_access']['user_role']) && $users['role_access']['user_role']['user_role'] == 'on') { ?>
									<li class="nav-item  <?= $version == 'user_role' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('user_role') ?>"><span>User Role</span>
										</a>
									</li>
								<?php } ?>
								<?php if (!empty($users['role_access']['user_log']) && $users['role_access']['user_log']['user_log'] == 'on') { ?>
									<li class="nav-item  <?= $version == 'user_log' ? 'active' : '' ?>">
										<a class="nav-link" href="<?= base_url('user_log') ?>"><span>User Log</span>
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>
					</ul>
				</aside>
			</div>

			<?php if (!empty($this->session->flashdata())) { ?>
				<!-- <div class="main-flashdata navbar" style="position: absolute; top: 0%; left: 27%;"> -->
				<div style="position: absolute; top: 0%; left: 27%;">
					<?php if (!empty($this->session->flashdata('alert-success'))) : ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<div class="alert-icon contrast-alert"><i class="fa fa-check"></i></div>
							<div class="alert-message">
								<span><strong>Berhasil!</strong> - <?= $this->session->flashdata('alert-success') ?></span>
							</div>
						</div>
					<?php endif; ?>
					<?php if (!empty($this->session->flashdata('alert-warning'))) : ?>
						<div class="alert alert-warning alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<div class="alert-icon contrast-alert"><i class="fa fa-exclamation-triangle"></i></div>
							<div class="alert-message">
								<span><strong>Perhatian!</strong> - <?= $this->session->flashdata('alert-warning') ?></span>
							</div>
						</div>
					<?php endif; ?>

					<?php if (!empty($this->session->flashdata('alert-danger'))) : ?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<div class="alert-icon contrast-alert"><i class="fa fa-times"></i></div>
							<div class="alert-message">
								<span><strong>Gagal!</strong> - <?= $this->session->flashdata('alert-danger') ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php } ?>
			<?= $body ?>

		</div>
		<!-- POPUP MESSAGE -->
		<div class="modal fade" id="popup-msg" tabindex="-1" role="dialog" aria-labelledby="cashierModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header"></div>
					<div class="modal-body text-center"></div>
					<div class="modal-footer p-0">
						<button class="btn btn-danger btn-block btn-round-bottom" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>