<body ng-app="kab_bogor">
	<style>
		.logo-img {
			max-width: 200px;
			width: 120%;
			height: auto;
		}
	</style>
	<div class="colorlib-loader"></div>
	<div id="page">
		<div class="main-wrapper">
			<nav class="colorlib-nav" role="navigation">
				<div class="top-menu">
					<div class="container">
						<div class="row">
							<div class="col-md-2">
								<div id="colorlib-logo">
									<a href="<?= base_url('landing_page') ?>">
										<img src="<?= base_url('assets/img/logo_kab_bogor_hitam_regular.png') ?>"
											alt="Logo"
											class="logo-img mr-0">
									</a>
								</div>
							</div>
							<div class="col-md-10 text-right menu-1">
								<ul>
									<!-- <li><a href="<?= base_url("dashboard"); ?>">Dashboard</a></li> -->
									<li>
										<a href="<?= base_url("ssh"); ?>">SSH & SBU</a>
									</li>
									<li>
										<a href="<?= base_url("hspk"); ?>">HSPK</a>
									</li>
									<li>
										<a href="<?= base_url("asb"); ?>">ASB</a>
									</li>
									<li><a href="<?= base_url("rab"); ?>">RAB</a></li>
									<li><a href="<?= base_url("perkiraan_hps"); ?>">HPS</a></li>
									<li><a href="<?= base_url("dokumen"); ?>">Unduh</a></li>
									<li>
										<?php if (!empty($users['role_id'])) { ?>
									<li class="has-dropdown">
										<a href="#">Hi, <?= ucwords($users["full_name"]) ?></a>
										<ul class="dropdown">
											
											<?php if (in_array($users['role_name'], ['Administrator', 'admin'])): ?>
												<li><a href="<?= base_url('dashboard'); ?>">Admin Panel</a></li>
											<?php else: ?>
												<li><a href="<?= base_url('opd'); ?>">Admin Panel</a></li>
											<?php endif; ?>
											<li><a href="<?= base_url("index.php/logout"); ?>">Keluar</a></li>
										</ul>
									</li>
								<?php } else { ?>
									<a href="<?= base_url("login"); ?>">Masuk</a>
								<?php } ?>
								</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</nav>
			<section id="home" class="video-hero" style="height: 100px; background-image: url(assets/img/cover_img_1.jpg);  background-size:cover; background-position: center center;background-attachment:fixed;" data-section="home">
				<div class="overlay"></div>
				<div class="display-t display-t2 text-center">
					<div class="display-tc display-tc2">
						<div class="container">
							<div class="col-md-12 col-md-offset-0">
								<div class="animate-box">
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<?= $body ?>
			<footer id="colorlib-footer">
				<div class="copy">
					<div class="container">
						<div class="row">
							<div class="col-md-12 text-center">
								<span class="text-white">Copyright @2025</span>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>

		<div class="gototop js-top">
			<a href="#" class="js-gotop"><i class="icon-arrow-up2"></i></a>
		</div>
	</div>
	</div>
</body>