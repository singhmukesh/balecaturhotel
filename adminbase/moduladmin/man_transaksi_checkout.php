<?php include "../fungsi/function_transaksi.php";
	//view status konfirmasi pembayaran
	$getkonfrimasi = "SELECT * FROM knfrimasi_pmbyaran WHERE kd_booking='$_GET[id]'";
	$savekonfrim = mysqli_query($konek,$getkonfrimasi);
	$hasilnya    = mysqli_fetch_array($savekonfrim);
	$location_file_bukti = $site."uploads/bukti/".$hasilnya['bukti_pembayaran'];

	//tampilkan transaksi layanan
	$get_trans_other = mysqli_fetch_array(mysqli_query($konek,"SELECT * FROM transaksi_layanan WHERE kd_booking='$_GET[id]'"));
	$databooking = mysqli_fetch_array(mysqli_query($konek,
	"SELECT b.kd_booking, m.id_member, m.nama_lengkap, b.tgl_booking, b.checkin, b.checkout, b.nama_atasnama, b.company_or_other, b.status_userbook ,b.layanan_extra, b.total_biayasewa
	FROM member m JOIN booking b ON b.id_member=m.id_member
	WHERE b.kd_booking='$_GET[id]'"));
	$total_transaksi=$databooking['total_biayasewa'];
	//mendifinisikan jarak waktu checkin + checkout = jumlah menginap berapa hari
	$jumlah_hari = round((strtotime($databooking['checkout'])-strtotime($databooking['checkin']))/86400);
	//status konfrimasi button
	if ($databooking['status_userbook']=='BK') {
		$showaction ='action-disable';
	}else{
		$showaction ='';
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#confrimation_form').validate({
			rules:{
				id_kamar:{
					required:true,
				}
			},
			messages:{
				id_kamar:{
					required:"Nomor kamar tidak boleh kosong !!",
				}
			}
		});
		//disable button konfrimasi pembayaran
		$( "#action-disable" ).prop( "disabled", true );
	});
</script>
<style type="text/css">
	.detil-transaction .man-kdbook{margin-left: 200px; }
	.detil-transaction .man-idmember{margin-left: 220px; }
	.detil-transaction .man-namapemesan{margin-left: 187px; }
	.detil-transaction .man-tipekamar{margin-left: 217px; }
	.detil-transaction .man-hargakamar{margin-left: 205px; }
	.detil-transaction .man-tanggal_pemesan{margin-left: 196px; }
	.detil-transaction .man-checkin{margin-left: 239px; }
	.detil-transaction .man-checkout{margin-left: 231px; }
	.detil-transaction .man-checkout{margin-left: 231px; }
	.detil-transaction .man-totalmenginap{margin-left: 194px; }
	.detil-transaction .man-nokamar{margin-left: 228px; }
	.detil-transaction .man-umlahorang{margin-left: 206px; }
	.detil-transaction .man-statususerbook{margin-left: 225px;}
	.detil-transaction .man-nama_atasnama{margin-left: 183px;}
  .detil-transaction .man-company_or_other{margin-left: 122px;}
	.pembayaran-transaksi .man-carabayar{margin-left: 225px; }
	.pembayaran-transaksi .man-jenispelunasan{margin-left: 193px;}
	.pembayaran-transaksi .man-jenisbank{margin-left: 225px;}
	.pembayaran-transaksi .man-totalbayar{margin-left: 223px;}
	.pembayaran-transaksi .man-tglbayar{margin-left: 203px;}
	.pembayaran-transaksi .man-buktipembayaran{margin-left: 183px;}
  .pembayaran-transaksi .man-hutangbayar{margin-left: 207px;}
  .other-transac-kategori-rental{margin-left: 100px;}
  .other-transac-nama_kendaraan{margin-left: 81px;}
  .other-transac-tgl_awal_sewa{margin-left: 105px;}
  .other-transac-harga_kendaraan{margin-left: 81px;}
  .other-transac-nama-paket{margin-left: 100px;}
  .other-transac-harga-paket{margin-left: 100px;}
  .other-transac-keterangan-menunya{margin-left: 42px;}
</style>
	<div class="col-lg-12">
		<div class="font-sizerheading">
			<h1 class="page-header">Manajemen Transaksi Checkout</h1>
		</div>
		<form action="<?php echo $site;?>adminbase/backend/proses_checkout_kamar.php?act=update_konfirmasi_pesan&id=<?php echo $_GET['id']?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="kd_booking" value="<?php echo $_GET['id'];?>">
			<!-- DETAIL TRANSACTION -->
			<section data-tittle='Detail-Transaction' class="detil-transaction">
				<div class="form-group">
					<h4><strong>ORDER INFORMATION</strong></h4>
				</div>
				<div class="form-group">
					<p>Kode Booking <span class="man-kdbook">: <?php echo $_GET['id']?></span></p>
				</div>
				<div class="form-group">
					<p>Id Member <span class="man-idmember">: <?php echo $databooking['id_member'];?></span></p>
				</div>
				<div class="form-group">
					<p>Nama Pemesan <span class="man-namapemesan">: <?php echo $databooking['nama_lengkap'];?></span></p>
				</div>
			<?php if($databooking['nama_atasnama']=='-' || $databooking['company_or_other']=='-'){ ?>
 			<?php }else{ ?>
            	<div class="form-group">
              	 	<p>Nama Atasnama <span class="man-nama_atasnama">: <?php echo $databooking['nama_atasnama'];?></span></p>
            	</div>
              	<div class="form-group">
               		<p>Nama Perusahaan / Other <span class="man-company_or_other">: <?php echo $databooking['company_or_other'];?></span></p>
           	 	</div>
         	<?php } ?>
				<div class="form-group">
					<p>Tanggal pesan <span class="man-tanggal_pemesan">: <?php $cut_time = substr($databooking['tgl_booking'],10);
																 $cut_tgl  = substr($databooking['tgl_booking'],0,10);
															     echo tgl_indo($cut_tgl).' / '.$cut_time; ?></span>
					</p>
				</div>
				<div class="form-group">
					<p>Checkin <span class="man-checkin">: <?php echo tgl_indo($databooking['checkin']); ?></span></p>
				</div>
				<div class="form-group">
					<p>Checkout <span class="man-checkout">: <?php echo tgl_indo($databooking['checkout']); ?></span></p>
				</div>
				<div class="form-group">
					<p>Total menginap <span class="man-totalmenginap">: <?php echo $jumlah_hari." Hari";?> </span>
				</div>
			</section>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Tipe kamar</th>
							<th>No kamar</th>
							<th>Harga Asli</th>
							<th>Pajak</th>
							<th>Diskon</th>
							<th>Total Harga (pajak / jika ada diskon)</th>
							<th>Subtotal Harga / kamar x hari</th>
						</tr>
					</thead>
					<?php
						$get_trans_room = mysqli_query($konek,"SELECT * FROM booking b
						JOIN detail_booking_kamar dbk ON dbk.kd_booking=b.kd_booking
						JOIN kategori_kamar km ON dbk.id_kategori_kamar=km.id_kategori_kamar
						WHERE b.kd_booking='$_GET[id]'");
						while ($result_room = mysqli_fetch_array($get_trans_room)) {
						$price_room=$result_room['tarif'];
						$kategori=$result_room['id_kategori_kamar'];
						//cek kamar tersebut ada diskon atau tidak
						$get_price = mysqli_fetch_array(mysqli_query($konek,"SELECT tarif, fasilitas, deskripsi, id_kategori_kamar FROM kategori_kamar WHERE id_kategori_kamar='$kategori'"));
						//deklarasi variable tarif
						$tarifnya = $get_price['tarif'];
						$x 		  = $get_price['id_kategori_kamar'];
						//buat diskon
						$getdiskon = mysqli_fetch_array(mysqli_query($konek,"SELECT * FROM diskon WHERE id_kategori_kamar='$kategori'"));
						//mendefinisikan get diskonya berdasarkan kamar yang ada diskon
						$y  		    = $getdiskon['id_kategori_kamar'];
						$available_disc = $getdiskon['besar_diskon'];
						//variable percent 10%
						$percent = (($price_room*10)/100);
						//variable discount
						$discount =(($price_room*$available_disc)/100);
					?>
					<tbody>
						<tr>
							<td><?php echo $result_room['type_kamar'];?></td>
							<td><?php echo $result_room['id_kamar'];?></td>
							<td>Rp.<?php echo formatuang($result_room['tarif']);?></td>
							<td><?php echo "10% / ".formatuang($percent);?></td>
							<td><?php if ($x == $y) {echo $getdiskon['besar_diskon']."%";}elseif($y==''){echo "-";}?></td>
							<td>Rp.<?php echo formatuang($price_room+$percent-$discount);?></td>
							<td>Rp.<?php echo formatuang(($price_room+$percent-$discount)*$jumlah_hari);?></td>
						</tr>
					</tbody>
					<input type="hidden" name="category_room[]" value="<?php echo $result_room['id_kategori_kamar'];?>">
					<input type="hidden" name="no_room_category[]" value="<?php echo $result_room['id_kamar'];?>">
					<?php } ?>
				</table>
			</div>
			<!-- KONFRIMASI PEMBAYARAN BANK TRANSFER & KREDIT CARD -->
			<section data-title="konfrimasi-pembayaran" class="pembayaran-transaksi">
				<div class="form-group">
			   		<h4><strong>KONFIRMASI PEMBAYARAN</strong></h4>
				</div>
				<!-- jika belum melakukan pembayaran -->
				<?php if ($databooking['status_userbook']=='BK') { ?>
				<div class="panel panel-default">
					<div class="panel-body">
						<h5 style="text-align:center;color:#ff0000;">Belum melakukan pembayaran</h5>
					</div>
				</div>
				<!-- jika sudah bayar -->
				<?php }else{?>
			   		<div class="form-group">
						<p>Cara bayar<span class='man-carabayar'>: <?php echo $hasilnya['cara_bayar'];?></span></p>
					</div>
					<div class="form-group">
						<p>Jenis pelunasan<span class='man-jenispelunasan'>: <?php echo $hasilnya['pelunasan'];?></span></p>
					</div>
					<div class="form-group">
						<p>Jenis bank <span class='man-jenisbank'> : <?php echo $hasilnya['jenis_bank'];?></span></p>
					</div>
				<?php if ($hasilnya['pelunasan']=='DP') { ?>
					<div class="form-group">
						<p>Total bayar <span class='man-totalbayar'> : Rp.<?php echo formatuang($hasilnya['jumlah_bayar']);?></span></p>
					</div>
					<div class="form-group">
						<p>Hutang bayar <span class='man-hutangbayar'> : Rp. <?php $hitung_trans=$total_transaksi-$hasilnya['jumlah_bayar'];echo formatuang($hitung_trans);?></span></p>
					</div>
				<?php }else{ ?>
					<div class="form-group">
						<p>Total bayar <span class='man-totalbayar'> : Rp.<?php echo formatuang($hasilnya['jumlah_bayar']);?></span></p>
					</div>
				<?php } ?>
					<div class="form-group">
						<p>Tanggal bayar <span class='man-tglbayar'> : <?php echo $hasilnya['tgl_bayar']; ?></span></p>
					</div>
					<div class="form-group">
						<p>Bukti pembayaran<span class="man-buktipembayaran">: <a href="<?php echo $location_file_bukti?>"  data-lightbox="<?php echo $location_file_bukti?>"><img width="200" height="auto" src="<?php echo $location_file_bukti?>"></a></span></p>
					</div>
				<?php } ?>
			</section>
			<!-- LAYANAN TAMBAHAN EXTRABED, LAUNDRY, RENTAL, RESTORASI -->
			<section data-title="Layanan-tambahan">
				<div class="form-group">
					<h4><strong>OTHER TRANSACTION</strong></h4>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">Transaksi Lainya ( Extrabed, Laundry, Sewa rental, Restaurant )</div>
					<div class="panel-body">
					<?php if ($databooking['layanan_extra']=='Tidak') { ?>
						<p style="text-align:center;">Tidak ada transaksi tambahan</p>
					<?php }elseif ($databooking['layanan_extra']=='Ya') { ?>
					 <?php $gettransaksi_extra = mysqli_fetch_array(mysqli_query($konek,"SELECT tl.kd_booking, tl.id_rental, r.kate_kendaraan, r.nama_kendaraan, r.harga_kendaraan, dbr.tgl_awal_sewa, dbr.tgl_akhir_sewa
                  			FROM transaksi_layanan tl JOIN rental r ON tl.id_rental=r.id_rental
                  			JOIN detail_booking_rental dbr ON tl.id_rental=dbr.id_rental WHERE tl.kd_booking='$_GET[id]'")); ?>
					<?php if ($get_trans_other['id_rental']!=0) { ?>
						<div class="form-group">
							<label>Sewa Rental</label>
						</div>
						<div class="form-group">
							<p>Kategori rental<span class='other-transac-kategori-rental'>: <?php echo $gettransaksi_extra['kate_kendaraan'];?></span></p>
						<div class="form-group">
							<p>Nama Kendaraan<span class='other-transac-nama_kendaraan'>: <?php echo $gettransaksi_extra['nama_kendaraan'];?></span></p>
						</div>
						<div class="form-group">
							<p>Tanggal Sewa<span class='other-transac-tgl_awal_sewa'>: <?php echo tgl_indo($gettransaksi_extra['tgl_awal_sewa'])." s/d ".tgl_indo($gettransaksi_extra['tgl_akhir_sewa']);?></span></p>
						</div>
						<div class="form-group">
							<p>Harga Kendaraan<span class='other-transac-harga_kendaraan'>: Rp.<?php echo formatuang($gettransaksi_extra['harga_kendaraan']);?></span></p>
						</div>
					<?php }elseif ($get_trans_other['id_rental']==0) {?>
					<?php } ?>
					<?php if ($get_trans_other['id_extrabed']!=0){?>
						<?php $gettransaksi_extra = mysqli_fetch_array(mysqli_query($konek,"SELECT tl.kd_booking, tl.id_extrabed, e.harga_extrabed
											    FROM transaksi_layanan tl JOIN extrabed e ON tl.id_extrabed=e.id_extrabed WHERE tl.kd_booking='$_GET[id]'")); ?>
						<div class="form-group">
							<label>Extrabed</label>
						</div>
						<div class="form-group">
							<p>Harga Extrabed<span class='Harga-extrabed'>: Rp.<?php echo formatuang($gettransaksi_extra['harga_extrabed']);?></span></p>
						</div>
						<?php }elseif ($get_trans_other['id_extrabed']==0) { ?>
						<?php } ?>
					<?php if($get_trans_other['id_paket']!=0) { ?>
					<?php $gettransaksi_extra = mysqli_fetch_array(mysqli_query($konek,"SELECT tl.kd_booking, tl.id_paket, p.nama_paket, p.harga_paket, dp.keterangan_menunya
					                     FROM transaksi_layanan tl JOIN paket p ON tl.id_paket=p.id_paket
					                     JOIN detail_paket dp ON p.id_paket=dp.id_paket WHERE tl.kd_booking='$_GET[id]'"));?>
					  <div class="form-group">
					     <label>Restaurant</label>
					  </div>
					  <div class="form-group">
					     <p>Nama paket <span class="other-transac-nama-paket">: <?php echo $gettransaksi_extra['nama_paket'];?></span></p>
					  </div>
					  <div class="form-group">
					     <p>Harga Paket<span class='other-transac-harga-paket'>: Rp.<?php echo formatuang($gettransaksi_extra['harga_paket']);?></span></p>
					  </div>
					  <div class="form-group">
					     <p>Keterangan menunya<span class="other-transac-keterangan-menunya">: <?php echo strip_tags($gettransaksi_extra['keterangan_menunya']);?></span></p>
					  </div>
					<?php }elseif ($get_trans_other['id_extrabed']==0) { ?>
					<?php } ?>
					<?php if ($get_trans_other['id_laundry']!=0) { ?>
					<?php $gettransaksi_extra = mysqli_fetch_array(mysqli_query($konek,"SELECT tl.kd_booking, tl.id_laundry, l.jenis_laundry, l.harga_laundry, l.ket_laundry
					         FROM transaksi_layanan tl JOIN laundry l ON tl.id_laundry=l.id_laundry WHERE tl.kd_booking='$_GET[id]'"));?>
					  <div class="form-group">
					     <label>Laundry</label>
					  </div>
					  <div class="form-group">
					     <p>Jenis layanan <span class="other-transac-nama-paket">: <?php echo $gettransaksi_extra['jenis_laundry'];?></span></p>
					  </div>
					  <div class="form-group">
					     <p>Harga laundry<span class='other-transac-harga-paket'>: Rp.<?php echo formatuang($gettransaksi_extra['harga_laundry']);?></span></p>
					  </div>
					  <div class="form-group">
					     <p>Keterangan <span class="other-transac-keterangan-laundry">: <?php echo strip_tags($gettransaksi_extra['ket_laundry']);?></span></p>
					  </div>
					<?php }elseif ($get_trans_other['id_laundry']==0) { ?>
               		<?php } ?>
               		<?php } ?>
					</div>
				</div>
			</section>
			<div class="row">
				<div class="col-md-3 pull-right">
					<p>Total Transaksi <span style="font-weight:bold;">Rp. <?php echo formatuang($databooking['total_biayasewa']);?></span></p>
				</div>
			</div>
			<article class='ketentuan-termsandcondition form-group'>
				<strong>Keterangan :</strong>
				<p>Jika dalam waktu 1 hari x 24 jam pelanggan tidak mengkonfrimasi pembayaran maka transaksi akan kami batalkan
					sesuai dengan peraturan kebijakan manajemen balecatur hotel.</p>
			</article>
			<div class="form-group">
				<button class="btn btn-warning" id="<?php echo $showaction; ?>">Proses Checkout</button>
				<button class="btn btn-danger" onclick="javascript:history.go(-1);">Cancel</button>
			</div>
		</form>
		<div class="clearfix-bottom-100"></div>
	</div>
</div>
