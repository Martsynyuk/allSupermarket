<?php
include 'Classes/PHPExcel/IOFactory.php';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="supermarkets.xls"');
header('Cache-Control: max-age=0');

$objReader = PHPExcel_IOFactory::createReader('CSV');


$objReader->setDelimiter(",");
$objReader->setInputEncoding('UTF-8');

$style = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    )
);

$objPHPExcel = $objReader->load('file.csv');
$objPHPExcel->getDefaultStyle()->applyFromArray($style);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

/**
 * If you want to save the excel file locally uncomment the following line
 */
//$objWriter->save('foot.xlsx');