<style>
  /* Carousel */
  .carousel-item img {
    height: 500px;
    width: 100%;
    object-fit: cover;
    object-position: center;
  }

  /* Section Title */
  .section-title {
    font-size: 1.7rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
    position: relative;
  }

  .section-title::after {
    content: "";
    width: 60px;
    height: 3px;
    background: #0d6efd;
    display: block;
    margin-top: 8px;
    border-radius: 2px;
  }

  /* Overview Section */
  .overview-box {
    background: #f8fafc;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }

  /* Grid Cards */
  .grid-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
    transition: 0.25s;
  }

  .grid-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  }

  .grid-card img,
  .grid-card svg {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .grid-card .inner {
    padding: 15px 18px;
  }

  .grid-card h5 {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 8px;
  }

  .grid-card p {
    font-size: 0.9rem;
    color: #555;
  }
</style>


<div ng-controller="<?= $page ?>" id="<?= $page ?>">
  <div id="page">

    <!-- ===================== CAROUSEL ===================== -->
    <div id="myCarousel" class="carousel slide mb-5" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>

      <div class="carousel-inner">

        <div class="carousel-item active">
          <img src="<?= base_url('assets/img/carousel/bgnew.jpg') ?>" alt="Slide 1">
        </div>

        <div class="carousel-item">
          <img src="<?= base_url('assets/img/carousel/1.jpeg') ?>" alt="Slide 2">
        </div>

        <div class="carousel-item">
          <img src="<?= base_url('assets/img/carousel/2.jpeg') ?>" alt="Slide 3">
        </div>

      </div>

      <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </a>

      <a class="carousel-control-next" href="#myCarousel" data-slide="next">
        <span class="carousel-control-next-icon"></span>
      </a>
    </div>


    <!-- ===================== CONTENT WRAPPER ===================== -->
    <div class="section-wrapper container">

      <!-- ===================== OVERVIEW ===================== -->
      <div class="overview-box mb-5">
        <h2 class="section-title">Sistem Informasi SSH, HPSK, dan ASB Kabupaten Bogor</h2>
        <p class="lead">
          Sistem Informasi SSH, HPSK, dan ASB Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)
          Kabupaten Bogor merupakan platform digital yang menyediakan informasi terintegrasi
          terkait Standar Satuan Harga, Harga Perkiraan Sendiri Konstruksi, serta Analisis
          Standar Belanja.
        </p>
        <p>
          Aplikasi ini dibangun untuk mempermudah perangkat daerah, pelaku usaha, dan masyarakat
          dalam mengakses data standar harga, analisis belanja, serta perhitungan biaya konstruksi
          secara akurat, transparan, dan terkini. Dengan penyajian data yang terstruktur dan mudah
          dipahami, sistem ini diharapkan dapat mendukung efektivitas perencanaan, penganggaran,
          serta pelaksanaan kegiatan pembangunan di Kabupaten Bogor.
        </p>
      </div>


      <!-- ===================== BERITA ===================== -->
      <h2 class="section-title">Berita Jasa Konstruksi</h2>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/sld1.jpg') ?>">
            <div class="inner">
              <h5>Judul Berita 1</h5>
              <p>Contoh isi berita singkat sebagai placeholder.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/sld2.jpeg') ?>">
            <div class="inner">
              <h5>Judul Berita 2</h5>
              <p>Contoh berita yang tampil pada grid card.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/sld3.jpeg') ?>">
            <div class="inner">
              <h5>Judul Berita 3</h5>
              <p>Tampilan modern dan rapi seperti MASPETRUK.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>
      </div>


      <!-- ===================== PENGUMUMAN ===================== -->
      <h2 class="section-title mt-5">Pengumuman Terbaru</h2>

      <div class="row mb-5">
        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/sld4.jpeg') ?>">
            <div class="inner">
              <h5>Pengumuman 1</h5>
              <p>Kumpulan pengumuman penting bagi masyarakat.</p>
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/sld5.jpg') ?>">
            <div class="inner">
              <h5>Pengumuman 2</h5>
              <p>Pengumuman terbaru mengenai konstruksi.</p>
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-4">
          <div class="grid-card">
            <img src="<?= base_url('assets/img/berita/StaticMapService.jpg') ?>">
            <div class="inner">
              <h5>Pengumuman 3</h5>
              <p>Informasi penting yang wajib diperhatikan.</p>
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </div>
        </div>
      </div>

    </div><!-- end wrapper -->

  </div>
</div>