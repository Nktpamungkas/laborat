<?php
session_start();
include "../../koneksi.php";

header("Content-Type: application/json");

try {

    $decosub01 = trim($_POST['decosub01'] ?? "");
    $decosub02 = trim($_POST['decosub02'] ?? "");
    $decosub03 = trim($_POST['decosub03'] ?? "");
    $decosub04 = trim($_POST['decosub04'] ?? "");

    $warehouse_zone_code     = trim($_POST['warehouse_zone_code'] ?? "");
    $warehouse_location_code = trim($_POST['warehouse_location_code'] ?? "");

    $lot_code           = trim($_POST['lot_code'] ?? "");
    $project_code       = trim($_POST['project_code'] ?? "");
    $g_b                = trim($_POST['g_b'] ?? "");

    $primary_qty   = floatval($_POST['primary_quantity'] ?? 0);
    $secondary_qty = floatval($_POST['secondary_quantity'] ?? 0);

    // DEFAULT VALUES
    $QUALITY_LEVEL_CODE = 1;
    $COMPANYCODE = "100";
    $ITEMTYPECOMPANYCODE = "100";
    $ITEMTYPECODE = "KGF";

    $LOGICALWAREHOUSECOMPANYCODE = "100";
    $LOGICALWAREHOUSECODE = "M023";

    $PHYSICALWAREHOUSECOMPANYCODE = "100";
    $PHYSICALWAREHOUSECODE = "M02";

    $CUSTOMERTYPE = "1";
    $SUPPLIERTYPE = "2";
    $STOCKTYPECODE = "001";
    $DETAILTYPE = "01";

    $BASEPRIMARYUNITCODE = "kg";
    $BASESECONDARYUNITCODE = "yd";

    $PACKAGINGQUANTITYUNIT = 0;
    
    $CREATIONUSER = $_SESSION['userLAB'] ?? 'anonymous';
    
    $ABSUNIQUEID = 0;

    $getMax = $con->query(" SELECT 
            MAX(ELEMENTSCODE) AS last_elementcode,
            MAX(NUMBERID) AS last_numberid
        FROM balance
    ");

    $row = $getMax->fetch_assoc();

    // ELEMENTSCODE
    $lastElement = intval(str_replace(",", "", $row['last_elementcode'] ?? 0));
    $newElementCode = ($lastElement == 0)
        ? 8000000000001
        : $lastElement + 1;

    // NUMBERID
    $lastNumber = intval(str_replace(",", "", $row['last_numberid'] ?? 0));
    $newNumberId = ($lastNumber == 0)
        ? 1
        : $lastNumber + 1;
        
    // Query insert
     $sql = " INSERT INTO balance (
            G_B,
            COMPANYCODE,
            ITEMTYPECOMPANYCODE,
            ITEMTYPECODE,

            LOGICALWAREHOUSECOMPANYCODE,
            LOGICALWAREHOUSECODE,

            PHYSICALWAREHOUSECOMPANYCODE,
            PHYSICALWAREHOUSECODE,

            CUSTOMERTYPE,
            SUPPLIERTYPE,
            STOCKTYPECODE,
            DETAILTYPE,

            DECOSUBCODE01,
            DECOSUBCODE02,
            DECOSUBCODE03,
            DECOSUBCODE04,

            WHSLOCATIONWAREHOUSEZONECODE,
            WAREHOUSELOCATIONCODE,

            QUALITYLEVELCODE,
            LOTCODE,
            PROJECTCODE,

            NUMBERID,

            ELEMENTSCODE,

            BASEPRIMARYQUANTITYUNIT,
            BASEPRIMARYUNITCODE,

            BASESECONDARYQUANTITYUNIT,
            BASESECONDARYUNITCODE,

            PACKAGINGQUANTITYUNIT,
            ABSUNIQUEID,

            CREATIONUSER,
            CREATIONDATETIME,
            LASTUPDATEDATETIME,
            CREATIONDATETIMEUTC,
            LASTUPDATEDATETIMEUTC
        )

        VALUES (
            ?, ?, ?, ?,
            ?, ?,
            ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?,
            ?,
            ?,
            ?, ?,
            ?, ?,
            ?, ?,
            ?,
            NOW(), NOW(), NOW(), NOW()
        )
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssssiddsdsiis",
        
        $g_b,
        $COMPANYCODE,
        $ITEMTYPECOMPANYCODE,
        $ITEMTYPECODE,

        $LOGICALWAREHOUSECOMPANYCODE,
        $LOGICALWAREHOUSECODE,

        $PHYSICALWAREHOUSECOMPANYCODE,
        $PHYSICALWAREHOUSECODE,

        $CUSTOMERTYPE,
        $SUPPLIERTYPE,
        $STOCKTYPECODE,
        $DETAILTYPE,

        $decosub01,
        $decosub02,
        $decosub03,
        $decosub04,

        $warehouse_zone_code,
        $warehouse_location_code,

        $QUALITY_LEVEL_CODE,
        $lot_code,
        $project_code,

        $newNumberId,
        
        $newElementCode,

        $primary_qty,
        $BASEPRIMARYUNITCODE,

        $secondary_qty,
        $BASESECONDARYUNITCODE,

        $PACKAGINGQUANTITYUNIT,
        $ABSUNIQUEID,

        $CREATIONUSER
    );

 

    $stmt->execute();
    $stmt->close();

    echo json_encode(["status" => "success"]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
