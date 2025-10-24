<?php
// pages/ajax/get_notif_tbo.php
require_once '../../koneksi.php';

// query untk ambil code yang baru
$newCodes = 0;
$revdCodes = 0;
$revCodes = 0;
$countRev = 0;
$newListed = [];
$revisi1 = [];
$revisi2 = [];
$head = [];
$line = [];
$gabDb2 = [];
$gabRevisi = [];
$gabRevisiD = [];

$q_code_baru = "SELECT
	                t.code as total_new
                from
                    tbl_header_bonorder t
                left join approval_bon_order a on
                    a.code = t.CODE
                where
                    buyer is not null
                    and APPROVED_RMP_DATETIME is not null
                    and a.code is null";

$stmt_baru = mysqli_query($con, $q_code_baru);
while ($data_baru =mysqli_fetch_array($stmt_baru)){
    $newListed[] = $data_baru['total_new'];
    $newCodes += 1;
}

// Query untuk revisi.
$query_revisi1 = "SELECT DISTINCT 
    s.CODE
from (
    SELECT
        t.CODE,
        t.ORDERLINE,
        case 
            when l.revisic != REV_DEPT1 then 1
            when l.revisic1 != REV_DEPT2 then 1
            when l.revisic2 != REV_DEPT3 then 1
            when l.revisic3 != REV_DEPT4 then 1
            when l.revisic4 != REV_DEPT5 then 1
        end as update_revisi_dept,
        case 
            when l.revisid != REV_COMN1 then 1
            when l.revisi2 != REV_COMN2 then 1
            when l.revisi3 != REV_COMN3 then 1
            when l.revisi4 != REV_COMN4 then 1
            when l.revisi5 != REV_COMN5 then 1
        end as update_revisi_comn,
        case 
            when nullif(l.revisi1date, '') != t.REV_DATE1 then 1
            when nullif(l.revisi2date, '') != t.REV_DATE2 then 1
            when nullif(l.revisi3date, '') != t.REV_DATE3 then 1
            when nullif(l.revisi4date, '') != t.REV_DATE4 then 1
            when nullif(l.revisi5date, '') != t.REV_DATE5 then 1
        end as update_revisi_date,
        case 
            when l.orderline is null then 1
        end as baru
    from
        tbl_line_bonorder t
    left join (
        SELECT
            distinct code,
            status,
            is_revision
        from
            approval_bon_order a
        where
            is_revision = 0) a on
        a.code = t.CODE
    left join (
        with RankedRevisions as (
            select
                l.*,
                row_number() over(
                    partition by code, orderline
                    order by id desc         
                ) as rn
            from
                line_revision l
        )
        select *
        from RankedRevisions
        where rn = 1
    ) l on
        l.code = t.CODE
        and l.orderline = t.ORDERLINE
    where
        coalesce(
            t.REV_DEPT1, t.REV_DEPT2, t.REV_DEPT3, t.REV_DEPT4, t.REV_DEPT5,
            t.REV_COMN1, t.REV_COMN2, t.REV_COMN3, t.REV_COMN4, t.REV_COMN5,
            t.REV_DATE1, t.REV_DATE2, t.REV_DATE3, t.REV_DATE4, t.REV_DATE5
        ) is not null
        and a.status = 'Approved'
        and t.IS_ACTIVE = 1
) s
where 
    (
        s.update_revisi_dept = 1
        or s.update_revisi_comn = 1
        or s.update_revisi_date = 1
        or s.baru = 1 
    );
";
$stmt_revisi1 = mysqli_query($con, $query_revisi1);
$revisiListed = [];
while ($data_revisi1 = mysqli_fetch_assoc($stmt_revisi1)) {
    $revisiListed[] = strtoupper(trim($data_revisi1['CODE']));
    $countRev += 1;
}

$response = [
    'new'    => ['count' => $newCodes,    'codes' => $newListed],
    'revisi' => ['count' => $countRev, 'codes' => $revisiListed],
    'total'  => $newCodes + $countRev,
];

echo json_encode($response);
