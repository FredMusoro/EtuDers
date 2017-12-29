<?php
include "Plan.php";
include "Ders.php";
include "Sube.php";
include "Saat.php";
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}
function programOlustur($dersler){
    $tmpDers=array();
    $olasilik=1;
    for($a=0;$a<15;$a++){
        if($a<count($dersler)){
            $tmpDers[$a]=count($dersler[$a]->Subeler);
            $olasilik=$olasilik*$tmpDers[$a];
        }
        else
            $tmpDers[$a]=1;
    }
    $tmpProgram=array();
    for($i=0;$i<$olasilik;$i++)

        array_push($tmpProgram,new Plan());
    $tmpsayac=0;
    $ders_sayisi=count($dersler);
    $loop="";
    for ($i=0;$i<$ders_sayisi;$i++){
        $loop.="for(\$b$i=0;\$b$i<\$tmpDers[$i];\$b$i++){";
    }
    $loop.="\$tmpProgram[\$tmpsayac] = new Plan(\$tmpsayac);";
    for ($i=0;$i<$ders_sayisi;$i++){
        $loop.="if($i<count(\$dersler))
                                                                                \$tmpProgram[\$tmpsayac]->subeEkle(\$dersler[$i]->Subeler[\$b$i]);";
    }
    $loop.="\$tmpsayac++;";
    for ($i=0;$i<$ders_sayisi;$i++){
        $loop.="}";
    }
    eval($loop);
    return $tmpProgram;
}
function cakismali($program, $limit){
    $uygunolanlar=array();
    for($j=0;$j<count($program);$j++){
        if($program[$j]->cakismasayisi<=$limit){
            $uygunolanlar[]=$program[$j];
        }
    }
    return $uygunolanlar;
}

if(isset($_POST['derslistesi'])  ){

    if(is_array($_POST['derslistesi'])){

        $derslist=$_POST['derslistesi'];
        if(!isset($_POST['cakisma']))
            $cakismalimit=2;
        else
            $cakismalimit=$_POST['cakisma'];

        $dersler=array();

        foreach ($derslist as $dersno){
            array_push($dersler,new Ders($dersno));
        }

        $ders_sayisi=count($dersler);

        $programlar=programOlustur($dersler);

        $cakismaResult=cakismali($programlar,$cakismalimit);
        echo(json_encode($cakismaResult));

    }
    else
        echo json_encode(null);
}
else
    echo json_encode(null);

?>
