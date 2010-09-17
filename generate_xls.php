<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2010 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2010 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.3c, 2010-06-01
 */

/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
require_once '../Classes/PHPExcel.php';

//Database
//includes
require_once "../secure/common.php";
require_once "../secure/set_defs.php";
require_once "../secure/db.php";

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set properties
$objPHPExcel->getProperties()->setCreator("Niklas Stenström")
							 ->setLastModifiedBy("Niklas Stenström")
							 ->setTitle("Tävlingsanmälan ISG")
							 ->setSubject("Tävlingsanmälan ISG");


//Open database use secured function
$connection = db_open();

$query = 'SELECT * FROM isg_anmalan ORDER by Race, Heat, Namn';
  
$result = mysql_query($query)
or die('Fel i query: $query. ' . mysql_error());

//Koll om element finns i databasen
$participants=mysql_num_rows($result);
if ($participants > 0)
{
//Here we should print_excel_header; 
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Namn')
                                  ->setCellValueByColumnAndRow(1, 1, 'Födelseår')  
                                  ->setCellValueByColumnAndRow(2, 1, 'Tävling')  
                                  ->setCellValueByColumnAndRow(3, 1, 'Klass') 
                                  ->setCellValueByColumnAndRow(4, 1, 'Reg tid');

// Go through all records in the database
$excel_row = 2;
  while($db_record = mysql_fetch_object($result))
  {

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $db_record->Namn)
                                  ->setCellValueByColumnAndRow(1, $excel_row, $db_record->Birth)  
                                  ->setCellValueByColumnAndRow(2, $excel_row, $db_record->Race)  
                                  ->setCellValueByColumnAndRow(3, $excel_row, $db_record->Heat) 
                                  ->setCellValueByColumnAndRow(4, $excel_row, $db_record->RegTid);

    $excel_row++;
  }  

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ISG_race.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
  
}
else
{
  //Error
  echo 'Inga rader funna Kan inte exportera!<br>';
}

//free result
mysql_free_result($result);

//close connection
mysql_close($connection);

exit;




