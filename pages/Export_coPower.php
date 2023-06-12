<?php
ini_set("error_reporting", 1);
session_start();
include "koneksi.php";
?>
<style>
    /**
 *
 * Style.css
 *
 */
    .container {
        padding: 50px 200px;
    }

    .box {
        position: relative;
        background: #ffffff;
        width: 100%;
    }

    .box-header {
        color: #444;
        display: block;
        padding: 10px;
        position: relative;
        border-bottom: 1px solid #f4f4f4;
        margin-bottom: 10px;
    }

    .box-tools {
        position: absolute;
        right: 10px;
        top: 5px;
    }

    .dropzone-wrapper {
        border: 2px dashed #91b0b3;
        color: #92b0b3;
        position: relative;
        height: 300px;
    }

    .dropzone-desc {
        position: absolute;
        margin: 0 auto;
        left: 0;
        right: 0;
        text-align: center;
        width: 40%;
        top: 50px;
        font-size: 16px;
    }

    .dropzone,
    .dropzone:focus {
        position: absolute;
        outline: none !important;
        width: 100%;
        height: 300px;
        cursor: pointer;
        opacity: 0;
    }

    .dropzone-wrapper:hover,
    .dropzone-wrapper.dragover {
        background: #ecf0f5;
    }

    .preview-zone {
        text-align: center;
    }

    .preview-zone .box {
        box-shadow: none;
        border-radius: 0;
        margin-bottom: 0;
    }
</style>

<body>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 style="font-weight: bold; font-style: italic; border-bottom: solid #9e9e9e 1px;" class="text-center">Export Co-Power file</h4>
                    <div class="col-lg-12 overflow-auto table-responsive" style="overflow-x: auto;">
                        <form action="index1.php?p=upload_copower" method="POST" enctype="multipart/form-data" class="form-horizontal">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Upload File</label>
                                            <div class="dropzone-wrapper">
                                                <div class="dropzone-desc">
                                                    <i class="glyphicon glyphicon-download-alt"></i>
                                                    <p>Choose an .txt file or drag it here & Make sure the format in lowercase (.txt)</p>
                                                </div>
                                                <input type="file" type="file" id="file" name="file" class="dropzone" required="true">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <button type="submit" name="submit" value="submit" class="btn btn-primary btn-lg col-lg-4"><strong>Upload</strong></button>
                                </div>
                            </div>
                            <!-- <div class="container" style="margin-top:10px;">
                                <div class="form-group">
                                    <span class="control-fileupload">
                                        <label for="file">Choose a file :</label>
                                        <input type="file" id="file" name="file" class="form-control" required="true">
                                    </span>
                                    <span class="text-danger"><php echo $_SESSION['msg']; ?></span>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-danger col-lg-4" type="submit" name="submit" value="submit">UPLOAD</button>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script>
    $(function() {
        $('input[type=file]').change(function() {
            var t = $(this).val();
            var labelText = 'Choosed file : ' + t.substr(12, t.length);
            $('.dropzone-desc p').text(labelText);
        })
    });
</script>