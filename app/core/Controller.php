<?php
/* 
=======================================================================
 PHPHR — Open Source HR Management Software
 Version: 3.0
 License: MIT Open Source License
 Developed & Maintained By: PHPHR (https://www.phphr.com)
 
 Description:
 PHPHR is a Human Resource Management (HRM) platform available in
 two deployment models: PHPHR Open-Source, a free, self-hosted
 edition for essential HR operations, and PHPHR Cloud, a fully
 managed, enterprise-grade HRMS. PHPHR is designed to help
 organizations manage Employees, Attendance, Leaves, Payroll,
 and core HR workflows with simplicity, security, and speed.

 Core Modules Included (Open-Source Edition):
 - Employee Management
 - Attendance Tracking
 - Leave Management
 - Payroll Management
 - HR Dashboard
 - Secure Login

 This software is open for modification and extension.
 You are free to customize, improve, and commercially use the
 Open-Source edition without licensing fees, provided this
 copyright notice remains preserved.

 Website:
 https://www.phphr.com

 Live Demo:
 https://www.phphr.com/hr-software-demo/

 Download:
 https://www.phphr.com/download-hr-software/

 Community Contribution:
 Developers are welcome to contribute improvements, bug fixes,
 and enhancements to the Open-Source edition. Please visit our
 website for documentation, updates, and community support.

 Last Update: 14-12-2025
=======================================================================
*/

// app/core/Controller.php
class Controller
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    protected function view(string $path, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/' . $path . '.php';
    }

    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}
