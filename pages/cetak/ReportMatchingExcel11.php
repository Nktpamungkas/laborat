<?php
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=LAB_ReportMatching11".date('Y-m-d').".xls"); //ganti nama sesuai keperluan
	header("Pragma: no-cache");
	header("Expires: 0");
	// disini script laporan anda
?>
<?php
ini_set("error_reporting", 1);
include "../../koneksi.php";

?>
<table>
              <tr>
                <th>no_resep</th>
                <th>no_order</th>
                <th>no_po</th>
                <th>langganan</th>
                <th>no_item</th>
                <th>jenis_kain</th>
                <th>benang</th>
                <th>cocok_warna</th>
                <th>warna</th>
                <th>no_warna</th>
                <th>lebar</th>
                <th>gramasi</th>
                <th>qty_order</th>
                <th>status_bagi</th>
                <th>tgl_in</th>
                <th>tgl_out</th>
                <th>proses</th>
                <th>buyer</th>
                <th>tgl_delivery</th>
                <th>note</th>
                <th>jenis_matching</th>
                <th>tgl_buat</th>
                <th>created_by</th>
                <th>tgl_update</th>
                <th>last_update_by</th>
                <th>grup</th>
                <th>matcher</th>
                <th>cek_warna</th>
                <th>cek_dye</th>
                <th>status</th>
                <th>kt_status</th>
                <th>koreksi_resep</th>
                <th>percobaan_ke</th>
                <th>percobaan_berapa_kali</th>
                <th>benang_aktual</th>
                <th>lebar_aktual</th>
                <th>gramasi_aktual</th>
                <th>ph</th>
                <th>soaping_sh</th>
                <th>soaping_tm</th>
                <th>rc_sh</th>
                <th>rc_tm</th>
                <th>lr</th>
                <th>cie_wi</th>
                <th>cie_tint</th>
                <th>spektro_r</th>
                <th>ket</th>
                <th>cside_c</th>
                <th>cside_min</th>
                <th>tside_c</th>
                <th>tside_min</th>
                <th>done_matching</th>
                <th>created_at</th>
                <th>created_by</th>
                <th>edited_at</th>
                <th>edited_by</th>
                <th>target_selesai</th>
                <th>mulai_by</th>
                <th>mulai_at</th>
                <th>selesai_by</th>
                <th>selesai_at</th>
                <th>approve_by</th>
                <th>approve_at</th>
                <th>approve</th>
                <th>hold_at</th>
                <th>hold_by</th>
                <th>timer</th>
                <th>why_batal</th>
                <th>revisi_at</th>
                <th>revisi_by</th>
                <th>kadar_air</th>
                <th>final_matcher</th>
                <th>colorist1</th>
                <th>colorist2</th>
                <th>penanggung_jawab</th>
                <th>bleaching_tm</th>
                <th>bleaching_sh</th>
                <th>second_lr</th>
                <th>remark_dye</th>
              </tr>
              <?php
              $date_s = date('Y-m-d', strtotime("-1 days"));
              $date_e = date('Y-m-d');
			  $time_s = "23:00";
              $time_e = "23:00";	

              $sql = mysqli_query($con,"SELECT *,  a.id as id_status, a.created_at as tgl_buat_status, a.created_by as status_created_by
                                          FROM tbl_status_matching a
                                          INNER JOIN tbl_matching b ON a.idm = b.no_resep
                                          where a.status = 'selesai' and a.approve = 'TRUE' and
                                          DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') >= '$date_s $time_s' AND DATE_FORMAT(a.approve_at,'%Y-%m-%d %H:%i') <= '$date_e $time_e'
                                          ORDER BY a.id desc");              
              while ($r = mysqli_fetch_array($sql)) {
                $no++;
              ?>
                <tr>
                  <td><?php echo $r['no_resep'] ?></td>
                  <td><?php echo $r['no_order'] ?></td>
                  <td><?php echo $r['no_po'] ?></td>
                  <td><?php echo $r['langganan'] ?></td>
                  <td><?php echo $r['no_item'] ?></td>
                  <td><?php echo $r['jenis_kain'] ?></td>
                  <td><?php echo $r['benang'] ?></td>
                  <td><?php echo $r['cocok_warna'] ?></td>
                  <td><?php echo $r['warna'] ?></td>
                  <td><?php echo $r['no_warna'] ?></td>
                  <td><?php echo $r['lebar'] ?></td>
                  <td><?php echo $r['gramasi'] ?></td>
                  <td><?php echo $r['qty_order'] ?></td>
                  <td><?php echo $r['status_bagi'] ?></td>
                  <td><?php echo $r['tgl_in'] ?></td>
                  <td><?php echo $r['tgl_out'] ?></td>
                  <td><?php echo $r['proses'] ?></td>
                  <td><?php echo $r['buyer'] ?></td>
                  <td><?php echo $r['tgl_delivery'] ?></td>
                  <td><?php echo $r['note'] ?></td>
                  <td><?php echo $r['jenis_matching'] ?></td>
                  <td><?php echo $r['tgl_buat'] ?></td>
                  <td><?php echo $r['created_by'] ?></td>
                  <td><?php echo $r['tgl_update'] ?></td>
                  <td><?php echo $r['last_update_by'] ?></td>
                  <td><?php echo $r['grp'] ?></td>
                  <td><?php echo $r['matcher'] ?></td>
                  <td><?php echo $r['cek_warna'] ?></td>
                  <td><?php echo $r['cek_dye'] ?></td>
                  <td><?php echo $r['status'] ?></td>
                  <td><?php echo $r['kt_status'] ?></td>
                  <td><?php echo $r['koreksi_resep'] ?></td>
                  <td><?php echo $r['percobaan_ke'] ?></td>
                  <td><?php echo $r['howmany_percobaan_ke'] ?></td>
                  <td><?php echo $r['benang_aktual'] ?></td>
                  <td><?php echo $r['lebar_aktual'] ?></td>
                  <td><?php echo $r['gramasi_aktual'] ?></td>
                  <td><?php echo $r['ph'] ?></td>
                  <td><?php echo $r['soaping_sh'] ?></td>
                  <td><?php echo $r['soaping_tm'] ?></td>
                  <td><?php echo $r['rc_sh'] ?></td>
                  <td><?php echo $r['rc_tm'] ?></td>
                  <td><?php echo $r['lr'] ?></td>
                  <td><?php echo $r['cie_wi'] ?></td>
                  <td><?php echo $r['cie_tint'] ?></td>
                  <td><?php echo $r['spektro_r'] ?></td>
                  <td><?php echo $r['ket'] ?></td>
                  <td><?php echo $r['cside_c'] ?></td>
                  <td><?php echo $r['cside_min'] ?></td>
                  <td><?php echo $r['tside_c'] ?></td>
                  <td><?php echo $r['tside_min'] ?></td>
                  <td><?php echo $r['done_matching'] ?></td>
                  <td><?php echo $r['created_at'] ?></td>
                  <td><?php echo $r['created_by'] ?></td>
                  <td><?php echo $r['edited_at'] ?></td>
                  <td><?php echo $r['edited_by'] ?></td>
                  <td><?php echo $r['target_selesai'] ?></td>
                  <td><?php echo $r['mulai_by'] ?></td>
                  <td><?php echo $r['mulai_at'] ?></td>
                  <td><?php echo $r['selesai_by'] ?></td>
                  <td><?php echo $r['selesai_at'] ?></td>
                  <td><?php echo $r['approve_by'] ?></td>
                  <td><?php echo $r['approve_at'] ?></td>
                  <td><?php echo $r['approve'] ?></td>
                  <td><?php echo $r['hold_at'] ?></td>
                  <td><?php echo $r['hold_by'] ?></td>
                  <td><?php echo $r['timer'] ?></td>
                  <td><?php echo $r['why_batal'] ?></td>
                  <td><?php echo $r['revisi_at'] ?></td>
                  <td><?php echo $r['revisi_by'] ?></td>
                  <td><?php echo $r['kadar_air'] ?></td>
                  <td><?php echo $r['final_matcher'] ?></td>
                  <td><?php echo $r['colorist1'] ?></td>
                  <td><?php echo $r['colorist2'] ?></td>
                  <td><?php echo $r['penanggung_jawab'] ?></td>
                  <td><?php echo $r['bleaching_tm'] ?></td>
                  <td><?php echo $r['bleaching_sh'] ?></td>
                  <td><?php echo $r['second_lr'] ?></td>
                  <td><?php echo $r['remark_dye'] ?></td>
                </tr>
              <?php } ?>
          </table>