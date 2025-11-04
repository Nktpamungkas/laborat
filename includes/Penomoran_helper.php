<?php
class Penomoran_helper{
    public static function nilaiKeRibuan($angka,$ribuan=".",$koma=","){
        try{
            if($angka==""||$angka=="-"){
                $angka=0;
            }
            $str=explode(".",strval($angka)+ 0);
            if(count($str)==2){
                return number_format(intval($str[0]),0,$koma,$ribuan).$koma.$str[1];
            }else{
                return number_format(intval($str[0]),0,$koma,$ribuan);
            }
        }
        catch (ExceptionType $e) {
            return $angka;
        }
    }
    public static function ribuanKeNilai($angka,$ribuan=".",$koma=","){
        try{
            $tanpaRibuan=str_replace($ribuan,"",$angka);
            $str=explode($koma,strval($tanpaRibuan));
            if(count($str)==2){
                return $str[0].".".$str[1];
            }else{
                return $str[0];
            }
        }
        catch (ExceptionType $e) {
            return $angka ;
        }
    }
    public static function nilaiKeRibuanStandarPackage($angka,$ribuan=".",$koma=","){
        $str=explode("||",strval($angka));
        $result=array();
        foreach($str as $exp){
            $result[]= Penomoran_helper::nilaiKeRibuan($exp,$ribuan,$koma);
        }
        return join("||",$result);;
    }
}