<body>

	<?php
	$hari = array(
		1 =>    'Senin',
		'Selasa',
		'Rabu',
		'Kamis',
		'Jumat',
		'Sabtu',
		'Minggu'
	);
	$bulan = array(
		1 =>   'Januari',
		'Februari',
		'Maret',
		'April',
		'Mei',
		'Juni',
		'Juli',
		'Agustus',
		'September',
		'Oktober',
		'November',
		'Desember'
	);
	?>

	<header>
		<?php if (!empty($data['logo'])): ?>
			<img src="<?= $data['logo'] ?>" style="width:80px;">
		<?php endif; ?>
		<h2>
			RENCANA ANGGARAN BIAYA (RAB) <br> Kabupaten Bogor <br> <?= date("d M Y") ?>
		</h2>
		<hr>
	</header>

	<article>

		<p>Rencana Anggaran Biaya</p>

		<table>
			<tr>
				<td>Kegiatan</td>
				<td>: </td>
				<td><?php echo $data['kegiatan_text']; ?></td>
			</tr>
		</table>

		<table border="1">
			<thead>
				<tr>
					<td style="text-align: center">No</td>
					<td style="text-align: center">Kode Item</td>
					<td style="text-align: center">Harga</td>
					<td style="text-align: center">Volume</td>
					<td style="text-align: center">Total</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['id_thn_harga'] as $key => $value) { ?>
					<tr>
						<td style="width: 2%;"><?php echo ($key + 1) ?></td>
						<td style="width: 55%;">
							<?= $value->kodeKelompok ?> -
							<?= $value->UraianKelompok ?> -
							<?= $value->NamaJenis ?> -
							<?= $value->UraianSpesifikasi ?> -
							<?= $value->satuan ?> -
							(<?= $value->tipe ?>) -
							<?= $value->TahunHarga ?>
						</td>
						<td style="width: 17%; text-align: right;"><?php echo 'Rp.' . number_format($data['harga_satuan'][$key], 0, '', '.') ?></td>
						<td style="text-align: center" style="width: 9%;"><?php echo $data['total_item'][$key] ?></td>
						<td style="width: 17%; text-align: right;"><?php echo 'Rp.' . number_format($data['total_harga'][$key], 0, '', '.') ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<table border="1">
			<tr>
				<td colspan="2" style="width: 470px;">Jumlah</td>
				<td style="width: 150px; text-align: right;"><?php echo 'Rp.' . number_format($data['jumlah_total'], 0, '', '.') ?></td>
			</tr>
			<tr>
				<td style="width: 370px;">Biaya Umum & Keuntungan</td>
				<td style="width: 100px; text-align: right;"><?php echo $data['percent'] . '%' ?></td>
				<td style="width: 150px; text-align: right;"><?php echo 'Rp.' . number_format($data['total_percent'], 0, '', '.') ?></td>
			</tr>
			<tr>
				<td colspan="2" style="width: 470px;">Total Harga Satuan Pekerjaan</td>
				<td style="width: 150px; text-align: right;"><?php echo 'Rp.' . number_format($data['total_all'], 0, '', '.') ?></td>
			</tr>
		</table>

		<p>Demikian Simulasi Perkiraan HPS ini dibuat dan ditandatangani, untuk dapat dipergunakan sebagaimana mestinya.</p>

	</article>

	<table class="signature">
		<tr>
			<td style="padding: auto; padding-bottom: 50px;">Dibuat oleh, </td>
			<td style="padding: auto; padding-bottom: 50px;">Menyetujui, </td>
		</tr>
		<tr>
			<td style="padding: auto;"><u><?php echo $data['updated_by']; ?></u></td>
			<td style="padding: auto;"><u>Bpk KADIS</u></td>
		</tr>
		<tr>
			<td style="padding: auto;">Kabid Jaskon</td>
			<td style="padding: auto;">Kadis</td>
		</tr>
	</table>


	<!-- <footer>
	  	<p>Internal</p>
	</footer> -->

</body>

<style type="text/css">
	/* Style the layout */
	body {
		padding: 0 30px 0 30px;
		font-size: 14px;
		font-family: Arial, Helvetica, sans-serif;
	}

	@media (max-width: 600px) {

		nav,
		article {
			width: 100%;
			height: auto;
		}
	}

	/* Style the element */
	hr {
		border-top: 5px double;
	}

	.red-marker {
		color: red;
	}

	/*article table {
	  padding-left: 30px;
	}*/

	.signature {
		text-align: center;
		width: 100%;
		padding-top: 100px;
	}

	/* Style the header */
	img {
		width: 80px;
		/*	  opacity: 0.5;*/
	}

	/*table {
	    border-collapse: collapse;
	}*/

	header h2 {
		text-align: center;
		margin: -95px 30px 0px 30px;
		font-family: Tahoma, sans-serif;
	}

	header small {
		font-size: 12px;
		color: red;
		font-weight: normal;
	}

	/* Style the footer */
	footer {
		position: absolute;
		bottom: 0;
		width: 100%;
		height: 50px;
		text-align: left;
		color: grey;
	}
</style>