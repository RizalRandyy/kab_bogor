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
          <svg width="100%" height="100%">
            <rect width="100%" height="100%" fill="#777" />
          </svg>
          <div class="container">
            <div class="carousel-caption text-left">
              <h1>Selamat Datang di Kabupaten Bogor</h1>
              <p>Sistem informasi modern untuk kemudahan layanan publik.</p>
              <p><a class="btn btn-lg btn-primary" href="#">Pelajari Selengkapnya</a></p>
            </div>
          </div>
        </div>

        <div class="carousel-item">
          <svg width="100%" height="100%">
            <rect width="100%" height="100%" fill="#999" />
          </svg>
          <div class="container">
            <div class="carousel-caption">
              <h1>Data Terintegrasi</h1>
              <p>Akses cepat untuk semua data pembangunan.</p>
              <p><a class="btn btn-lg btn-primary" href="#">Lihat Data</a></p>
            </div>
          </div>
        </div>

        <div class="carousel-item">
          <svg width="100%" height="100%">
            <rect width="100%" height="100%" fill="#555" />
          </svg>
          <div class="container">
            <div class="carousel-caption text-right">
              <h1>Layanan Lebih Mudah</h1>
              <p>Kemudahan akses informasi di ujung jari Anda.</p>
              <p><a class="btn btn-lg btn-primary" href="#">Mulai Sekarang</a></p>
            </div>
          </div>
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


      <!-- ===================== BERITA GRID ===================== -->
      <h2 class="section-title">BERITA JASA KONSTRUKSI</h2>

      <div class="row">

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#bbb" />
            </svg>
            <div class="inner">
              <h5>Judul Berita 1</h5>
              <p>Contoh isi berita singkat sebagai placeholder.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#aaa" />
            </svg>
            <div class="inner">
              <h5>Judul Berita 2</h5>
              <p>Contoh berita yang tampil pada grid card.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#ccc" />
            </svg>
            <div class="inner">
              <h5>Judul Berita 3</h5>
              <p>Tampilan modern dan rapi seperti MASPETRUK.</p>
              <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
            </div>
          </div>
        </div>

      </div>



      <!-- ===================== PENGUMUMAN GRID ===================== -->
      <h2 class="section-title mt-5">PENGUMUMAN TERBARU</h2>

      <div class="row">

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#ddd" />
            </svg>
            <div class="inner">
              <h5>Pengumuman 1</h5>
              <p>Kumpulan pengumuman penting bagi masyarakat.</p>
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#aaa" />
            </svg>
            <div class="inner">
              <h5>Pengumuman 2</h5>
              <p>Pengumuman terbaru mengenai konstruksi.</p>
              <a href="#" class="btn btn-sm btn-primary">Baca</a>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="grid-card">
            <svg>
              <rect width="100%" height="100%" fill="#bbb" />
            </svg>
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