<?php

include "config.php";

//define('_MPDF_PATH',$mpdf_path);
include('../mpdf/mpdf.php');

$mpdf = new mPDF();
$mpdf->SetImportUse();
$pagecount = $mpdf->SetSourceFile('allegato2012B.pdf');
$tplIdx = $mpdf->ImportPage(1);
$mpdf->UseTemplate($tplIdx);
$mpdf->AddPage();
$tplIdx = $mpdf->ImportPage(2);
$mpdf->UseTemplate($tplIdx);

$mpdf->output()

?>