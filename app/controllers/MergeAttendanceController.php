<?php
require_once __DIR__ . '/../models/mergeAttendanceModel.php';

class MergeAttendanceController {
    private $model;

    public function __construct($mysql_conn, $mssql_conn) {
        $this->model = new mergeAttendanceModel($mysql_conn, $mssql_conn);
    }
public function view_attendance() {
    $card_id = $_GET['card_id'] ?? '';
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $source_filter = $_GET['source_filter'] ?? 'MERGED'; // Default ay lahat

    $employeeList = $this->model->getAllEmployees();
    $employee = $this->model->findByCardNo($card_id);
    
    // Kunin lahat ng logs (Merged)
    $all_logs = $this->model->get_individual_merge($card_id, $start_date, $end_date);

    // --- FILTER LOGIC ---
    if ($source_filter === 'FIELD') {
        $logs = array_filter($all_logs, function($log) {
            return $log['source'] === 'FIELD/MANUAL';
        });
    } elseif ($source_filter === 'OFFICE') {
        $logs = array_filter($all_logs, function($log) {
            return $log['source'] === 'OFFICE/BIOMETRIC';
        });
    } else {
        $logs = $all_logs; // MERGED
    }

    $data = [
        'logs'          => $logs,
        'employee'      => $employee,
        'employeeList'  => $employeeList,
        'card_id'       => $card_id,
        'start_date'    => $start_date,
        'end_date'      => $end_date,
        'source_filter' => $source_filter // Ipasa sa view
    ];

    extract($data);
    include __DIR__ . '/../views/mergeAttendance/employee_attendance_view.php';
}
    public function login() {
        // --- 1. GET FILTER DATA FROM URL ---
        // Halimbawa: ?employee_name=John&start_date=2023-10-01
        $filterName = isset($_GET['employee_name']) ? trim($_GET['employee_name']) : null;
        $filterDate = isset($_GET['date']) ? $_GET['date'] : null;

        // Kunin ang raw data mula sa Model
        $mysqlRaw = $this->model->getMySQLData();
        $mssqlRaw = $this->model->getMSSQLData();
        $employeeList = $this->model->getAllEmployees();

        $records = [];
        $attendanceCount = []; // Dito natin ilalagay ang computation ng days

        // --- 2. PROCESS MYSQL DATA (FIELD) ---
        foreach ($mysqlRaw as $m) {
            $fName = isset($m['first_name']) ? $m['first_name'] : (isset($m['FirstName']) ? $m['FirstName'] : '');
            $lName = isset($m['last_name']) ? $m['last_name'] : (isset($m['LastName']) ? $m['LastName'] : '');
            $fullName = trim($fName . ' ' . $lName);
            $date = !empty($m['time_in']) ? date('Y-m-d', strtotime($m['time_in'])) : '-';

            // Apply Filters (Simple version)
            if ($filterName && stripos($fullName, $filterName) === false) continue;
            if ($filterDate && $date !== $filterDate) continue;

            $records[] = [
                'card_id'   => $m['card_no'],
                'full_name' => (!empty($fullName)) ? $fullName : 'Unknown Employee',
                'date'      => $date,
                'time_in'   => !empty($m['time_in']) ? date('H:i:s', strtotime($m['time_in'])) : '-',
                'time_out'  => (!empty($m['time_out']) && strpos($m['time_out'], '0000-00-00') === false) 
                                ? date('H:i:s', strtotime($m['time_out'])) : '-',
                'source'    => 'FIELD',
                'status'    => 'PRESENT'
            ];
            
            // --- COMPUTATION LOGIC ---
            // Bibilangin lang natin kung valid ang date at hindi '-'
            if ($date !== '-') {
                $attendanceCount[$fullName][$date] = true; 
            }
        }

        // --- 3. PROCESS MSSQL DATA (OFFICE) ---
        foreach ($mssqlRaw as $ms) {
            $cardId = trim($ms['CardNo']);
            if (!isset($employeeList[$cardId])) continue; 

            $fullName = $employeeList[$cardId];
            $date = ($ms['LogDate'] instanceof DateTime) ? $ms['LogDate']->format('Y-m-d') : '-';

            // Apply Filters
            if ($filterName && stripos($fullName, $filterName) === false) continue;
            if ($filterDate && $date !== $filterDate) continue;

            $records[] = [
                'card_id'   => $cardId,
                'full_name' => $fullName,
                'date'      => $date,
                'time_in'   => ($ms['FirstIn'] instanceof DateTime) ? $ms['FirstIn']->format('H:i:s') : '-',
                'time_out'  => ($ms['LastOut'] instanceof DateTime) ? $ms['LastOut']->format('H:i:s') : '-',
                'source'    => 'OFFICE',
                'status'    => 'PRESENT'
            ];

            // --- COMPUTATION LOGIC ---
            if ($date !== '-') {
                $attendanceCount[$fullName][$date] = true;
            }
        }

        // 4. SORT RECORDS (Latest first)
        usort($records, function($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        // --- 5. FORMAT SUMMARY PARA SA VIEW ---
        // Ginagawa nating simple count: 'Juan Dela Cruz' => 5 (days)
        $summaryReport = [];
        foreach ($attendanceCount as $name => $dates) {
            $summaryReport[$name] = count($dates);
        }

        // I-pass ang $records at $summaryReport sa View
        include __DIR__ . '/../views/mergeAttendance/index.php';
    }

    public function showSummary() {
    // 1. Kunin ang Dates mula sa URL (GET)
    $start = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $end = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

    $mysqlRaw = $this->model->getMySQLData(); // Siguraduhing may date filter sa SQL query mo kung kaya
    $mssqlRaw = $this->model->getMSSQLData();
    $employeeList = $this->model->getAllEmployees();

    $summaryReport = [];

    // --- PROCESS MYSQL (FIELD) ---
    foreach ($mysqlRaw as $m) {
        $date = date('Y-m-d', strtotime($m['time_in']));
        if ($date >= $start && $date <= $end) {
            $fName = $m['first_name'] ?? '';
            $lName = $m['last_name'] ?? '';
            $fullName = trim("$fName $lName");

            if (!isset($summaryReport[$fullName])) {
                $summaryReport[$fullName] = ['field_count' => 0, 'office_count' => 0, 'dates' => []];
            }
            // Para hindi madoble ang bilang kung maraming logs sa isang araw
            if (!isset($summaryReport[$fullName]['dates'][$date])) {
                $summaryReport[$fullName]['field_count']++;
                $summaryReport[$fullName]['dates'][$date] = true;
            }
        }
    }

    // --- PROCESS MSSQL (OFFICE) ---
    foreach ($mssqlRaw as $ms) {
        $date = ($ms['LogDate'] instanceof DateTime) ? $ms['LogDate']->format('Y-m-d') : null;
        if ($date && $date >= $start && $date <= $end) {
            $cardId = trim($ms['CardNo']);
            if (isset($employeeList[$cardId])) {
                $fullName = $employeeList[$cardId];

                if (!isset($summaryReport[$fullName])) {
                    $summaryReport[$fullName] = ['field_count' => 0, 'office_count' => 0, 'dates' => []];
                }
                
                if (!isset($summaryReport[$fullName]['dates'][$date])) {
                    $summaryReport[$fullName]['office_count']++;
                    $summaryReport[$fullName]['dates'][$date] = true;
                }
            }
        }
    }

    // Bilangin ang Total Unique Days
    foreach ($summaryReport as $name => &$data) {
        $data['total_unique_days'] = count($data['dates']);
    }

    include __DIR__ . '/../views/mergeAttendance/summary.php';
}

}