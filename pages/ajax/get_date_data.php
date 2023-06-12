<!-- Editable table -->
<?php
ini_set("error_reporting", 1);
include '../../koneksi.php';
$id_status = $_GET['id'];

$sql_Time = mysqli_query($con,"SELECT kode, time_1, time_2, time_3, time_4, time_5, time_6,
                        time_7, time_8, time_9, time_10, doby1, doby2, doby3, doby4, doby5, doby6, doby7, doby8, doby9, doby10, inserted_by, last_edit_by FROM tbl_matching_detail where id_status = '$id_status' order by flag");
// var_dump($id_status);
// die;
?>
<div class="card">
    <div class="card-body">
        <div id="table" class="table-editable">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr class="bg-success">
                        <th class="text-center" style="border: 1px solid gray;">#</th>
                        <th class="text-center" style="border: 1px solid gray;">Code</th>
                        <th class="text-center" style="border: 1px solid gray;">Lab</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-1</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-2</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-3</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-4</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-5</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-6</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-7</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-8</th>
                        <th class="text-center" style="border: 1px solid gray;">Adjust-9</th>
                        <th class="text-center" style="border: 1px solid gray;">Last-edited</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    while ($data = mysqli_fetch_array($sql_Time)) { ?>
                        <tr style="border: 1px solid gray;">
                            <td class="text-center" style="border: 1px solid gray;"><?php echo $i++; ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php echo $data['kode'] ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_1'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_1'], 0, 16) . '<br>' . $data["doby1"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_2'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_2'], 0, 16) . '<br>' . $data["doby2"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_3'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_3'], 0, 16) . '<br>' . $data["doby3"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_4'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_4'], 0, 16) . '<br>' . $data["doby4"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_5'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_5'], 0, 16) . '<br>' . $data["doby5"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_6'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_6'], 0, 16) . '<br>' . $data["doby6"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_7'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_7'], 0, 16) . '<br>' . $data["doby7"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_8'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_8'], 0, 16) . '<br>' . $data["doby8"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_9'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_9'], 0, 16) . '<br>' . $data["doby9"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if (substr($data['time_10'], 0, 16) == '0000-00-00 00:00') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        echo substr($data['time_10'], 0, 16) . '<br>' . $data["doby10"];
                                                                                    } ?></td>
                            <td class="text-center" style="border: 1px solid gray;"><?php if ($data['last_edit_by'] == "") {
                                                                                        echo $data['inserted_by'];
                                                                                    } else {
                                                                                        echo $data['last_edit_by'];
                                                                                    }  ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Editable table -->