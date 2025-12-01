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
              <th class="text-center">no_resep</th>
                <th class="text-center">no_order</th>
                <th class="text-center">no_po</th>
                <th class="text-center">langganan</th>
                <th class="text-center">no_item</th>
                <th class="text-center">jenis_kain</th>
                <th class="text-center">benang</th>
                <th class="text-center">cocok_warna</th>
                <th class="text-center">warna</th>
                <th class="text-center">no_warna</th>
                <th class="text-center">lebar</th>
                <th class="text-center">gramasi</th>
                <th class="text-center">qty_order</th>
                <th class="text-center">status_bagi</th>
                <th class="text-center">tgl_in</th>
                <th class="text-center">tgl_out</th>
                <th class="text-center">proses</th>
                <th class="text-center">buyer</th>
                <th class="text-center">tgl_delivery</th>
                <th class="text-center">note</th>
                <th class="text-center">jenis_matching</th>
                <th class="text-center">tgl_buat</th>
                <th class="text-center">created_by</th>
                <th class="text-center">tgl_update</th>
                <th class="text-center">last_update_by</th>
                <th class="text-center">grup</th>
                <th class="text-center">matcher</th>
                <th class="text-center">cek_warna</th>
                <th class="text-center">cek_dye</th>
                <th class="text-center">status</th>
                <th class="text-center">kt_status</th>
                <th class="text-center ">koreksi_resep 1</th>
                <th class="text-center ">koreksi_resep 1</th>
                <th class="text-center ">koreksi_resep 2</th>
                <th class="text-center ">koreksi_resep 2</th>
                <th class="text-center ">koreksi_resep 3</th>
                <th class="text-center ">koreksi_resep 3</th>
                <th class="text-center ">koreksi_resep 4</th>
                <th class="text-center ">koreksi_resep 4</th>
                <th class="text-center">percobaan_ke</th>
                <th class="text-center">percobaan_berapa_kali</th>
                <th class="text-center">benang_aktual</th>
                <th class="text-center">lebar_aktual</th>
                <th class="text-center">gramasi_aktual</th>
                <th class="text-center">ph</th>
                <th class="text-center">soaping_sh</th>
                <th class="text-center">soaping_tm</th>
                <th class="text-center">rc_sh</th>
                <th class="text-center">rc_tm</th>
                <th class="text-center">lr</th>
                <th class="text-center">cie_wi</th>
                <th class="text-center">cie_tint</th>
                <th class="text-center">spektro_r</th>
                <th class="text-center">ket</th>
                <th class="text-center">cside_c</th>
                <th class="text-center">cside_min</th>
                <th class="text-center">tside_c</th>
                <th class="text-center">tside_min</th>
                <th class="text-center">done_matching</th>
                <th class="text-center">created_at</th>
                <th class="text-center">created_by</th>
                <th class="text-center">edited_at</th>
                <th class="text-center">edited_by</th>
                <th class="text-center">target_selesai</th>
                <th class="text-center">mulai_by</th>
                <th class="text-center">mulai_at</th>
                <th class="text-center">selesai_by</th>
                <th class="text-center">selesai_at</th>
                <th class="text-center">approve_by</th>
                <th class="text-center">approve_at</th>
                <th class="text-center">approve</th>
                <th class="text-center">hold_at</th>
                <th class="text-center">hold_by</th>
                <th class="text-center">timer</th>
                <th class="text-center">why_batal</th>
                <th class="text-center">revisi_at</th>
                <th class="text-center">revisi_by</th>
                <th class="text-center">kadar_air</th>
                <th class="text-center">final_matcher</th>
                <th class="text-center ">colorist 1</th>
                <th class="text-center ">colorist 1</th>
                <th class="text-center ">colorist 2</th>
                <th class="text-center ">colorist 2</th>
                <th class="text-center ">colorist 3</th>
                <th class="text-center ">colorist 3</th>
                <th class="text-center ">colorist 4</th>
                <th class="text-center ">colorist 4</th>
                <th class="text-center">penanggung_jawab</th>
                <th class="text-center">bleaching_tm</th>
                <th class="text-center">bleaching_sh</th>
                <th class="text-center">second_lr</th>
                <th class="text-center">remark_dye</th>
              </tr>
              <?php
//              $date_s = date('Y-m-d', strtotime("-1 days"));
//              $date_e = date('Y-m-d');
			  $date_s = "2025-05-14";
              $date_e = "2025-05-15";
			  $time_s = "23:00";
              $time_e = "23:00";	

              $sql = mysqli_query($con,"SELECT
                                          *,
                                          a.id AS id_status,
                                          a.created_at AS tgl_buat_status,
                                          a.created_by AS status_created_by 
                                        FROM
                                          tbl_status_matching a
                                          INNER JOIN tbl_matching b ON a.idm = b.no_resep 
                                        WHERE
                                          a.STATUS = 'selesai' 
                                          AND a.approve = 'TRUE' 
                                          AND DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) >= '$date_s $time_s' 
                                          AND DATE_FORMAT( a.approve_at, '%Y-%m-%d %H:%i' ) <= '$date_e $time_e' 
                                        ORDER BY
                                          a.id DESC");              
              while ($r = mysqli_fetch_array($sql)) {
                $no++;
              ?>
                <tr>
                  <td> <?php echo $r['no_resep'] ?></td>
                  <td> <?php echo $r['no_order'] ?></td>
                  <td> <?php echo $r['no_po'] ?></td>
                  <td> <?php echo $r['langganan'] ?></td>
                  <td> <?php echo $r['no_item'] ?></td>
                  <td> <?php echo $r['jenis_kain'] ?></td>
                  <td> <?php echo $r['benang'] ?></td>
                  <td> <?php echo $r['cocok_warna'] ?></td>
                  <td> <?php echo $r['warna'] ?></td>
                  <td> <?php echo $r['no_warna'] ?></td>
                  <td> <?php echo $r['lebar'] ?></td>
                  <td> <?php echo $r['gramasi'] ?></td>
                  <td> <?php echo $r['qty_order'] ?></td>
                  <td> <?php echo $r['status_bagi'] ?></td>
                  <td> <?php echo $r['tgl_in'] ?></td>
                  <td> <?php echo $r['tgl_out'] ?></td>
                  <td> <?php echo $r['proses'] ?></td>
                  <td> <?php echo $r['buyer'] ?></td>
                  <td> <?php echo $r['tgl_delivery'] ?></td>
                  <td> <?php echo $r['note'] ?></td>
                  <td> <?php echo $r['jenis_matching'] ?></td>
                  <td> <?php echo $r['tgl_buat'] ?></td>
                  <td> <?php echo $r['created_by'] ?></td>
                  <td> <?php echo $r['tgl_update'] ?></td>
                  <td> <?php echo $r['last_update_by'] ?></td>
                  <td><?php echo $r['grp'] ?></td>
                  <td><?php echo $r['matcher'] ?></td>
                  <td><?php echo $r['cek_warna'] ?></td>
                  <td><?php echo $r['cek_dye'] ?></td>
                  <td><?php echo $r['status'] ?></td>
                  <td><?php echo $r['kt_status'] ?></td>
                  <td><?php echo $r['koreksi_resep'] ?></td>
                  <td><?php echo $r['koreksi_resep2'] ?></td>
                  <td><?php echo $r['koreksi_resep3'] ?></td>
                  <td><?php echo $r['koreksi_resep4'] ?></td>
                  <td><?php echo $r['koreksi_resep5'] ?></td>
                  <td><?php echo $r['koreksi_resep6'] ?></td>
                  <td><?php echo $r['koreksi_resep7'] ?></td>
                  <td><?php echo $r['koreksi_resep8'] ?></td>
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
                  <td><?php echo $r['colorist3'] ?></td>
                  <td><?php echo $r['colorist4'] ?></td>
                  <td><?php echo $r['colorist5'] ?></td>
                  <td><?php echo $r['colorist6'] ?></td>
                  <td><?php echo $r['colorist7'] ?></td>
                  <td><?php echo $r['colorist8'] ?></td>
                  <td><?php echo $r['penanggung_jawab'] ?></td>
                  <td><?php echo $r['bleaching_tm'] ?></td>
                  <td><?php echo $r['bleaching_sh'] ?></td>
                  <td><?php echo $r['second_lr'] ?></td>
                  <td><?php echo $r['remark_dye'] ?></td>
                </tr>
              <?php } ?>
          </table>