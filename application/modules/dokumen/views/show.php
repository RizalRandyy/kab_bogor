<div ng-controller="<?= $page ?>" id="<?= $page ?>">
  <section class="section" style="margin-bottom:125px">
    <div class="row animate-box">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-stats p-5">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:25px; margin-bottom:25px;">
                  <h3 class="text-center">DETAIL DOKUMEN</h3>
                  <hr>
                </div>

                <?php if (!empty($data)) { ?>
                <div class="row">
                  <!-- Kolom Kiri: Detail -->
                  <div class="col-md-6">
                    <table class="table table-bordered" style="font-size:14px;">
                      <tr>
                        <th width="200">Nama Dokumen</th>
                        <td><?= $data->nama_dokumen ?></td>
                      </tr>
                      <tr>
                        <th>Jenis</th>
                        <td><?= $data->nama_jenis_dokumen ?></td>
                      </tr>
                      <tr>
                        <th>Tahun</th>
                        <td><?= $data->tahun ?></td>
                      </tr>
                      <tr>
                        <th>Deskripsi</th>
                        <td><?= $data->deskripsi ?></td>
                      </tr>
                      <tr>
                        <th>File</th>
                        <td>
                          <?php if ($data->dokumen) { ?>
                            <a href="<?= base_url('resources/uploads/dokumen/' . $data->dokumen) ?>" 
                               class="btn btn-primary btn-sm" target="_blank">
                              <i class="fas fa-download"></i> Unduh
                            </a>
                          <?php } else { ?>
                            <span>-</span>
                          <?php } ?>
                        </td>
                      </tr>
                    </table>

                    <a href="<?= base_url('dokumen') ?>" class="btn btn-secondary mt-3">
                      <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                  </div>

                  <!-- Kolom Kanan: Preview PDF -->
                  <div class="col-md-6">
                    <?php 
                      if ($data->dokumen) {
                        $ext = pathinfo($data->dokumen, PATHINFO_EXTENSION);
                        if (strtolower($ext) == 'pdf') {
                          $fileUrl = base_url('resources/uploads/dokumen/' . $data->dokumen);
                          echo '<embed src="'.$fileUrl.'" type="application/pdf" width="100%" height="600px" />';
                        }
                      }
                    ?>
                  </div>
                </div>
                <?php } else { ?>
                  <div class="alert alert-danger">Data tidak ditemukan!</div>
                <?php } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
