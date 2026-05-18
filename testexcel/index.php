<?php
// Include Composer's autoloader
require 'vendor/autoload.php'; // Ensure this is the correct path to autoloader

use PhpOffice\PhpSpreadsheet\IOFactory; // For loading the file
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {
    // Load the existing Excel file
    $spreadsheet = IOFactory::load('template.xlsx'); // Replace 'template.xlsx' with your actual file path

    // Get the active sheet (or a specific sheet)
    $sheet = $spreadsheet->getActiveSheet();

    // Modify the data in specific cells
    $sheet->setCellValue('K22', '1');  // Updating cell A1
    $sheet->setCellValue('L22', '2'); // Updating cell B2
    $sheet->setCellValue('M22', '3'); // Updating cell C3

    // Optionally, style the modified cells (same as before)
    //$sheet->getStyle('A1:C3')->getFont()->setBold(true);
    //$sheet->getStyle('A1:C3')->getFont()->setColor(new PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
    //$sheet->getStyle('A1:C3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    //$sheet->getStyle('A1:C3')->getFill()->getStartColor()->setRGB('4F81BD'); // Blue background

    // Save the changes to the same file or to a new file
    $writer = new Xlsx($spreadsheet);
    $writer->save('updated_template.xlsx'); // You can save it as the same name or a new name

    echo 'Excel file has been updated successfully.';

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
