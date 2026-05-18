<?php

require_once __DIR__ . '/../core/Controller.php';

class SyncController extends Controller
{
    protected $mysql;

    public function __construct($mysql_conn)
    {
        parent::__construct($mysql_conn);

        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }

        $this->mysql = $mysql_conn;

        if (empty($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }
    }

    // =========================
    // VIEW
    // =========================
    public function index()
    {
        $this->view('SyncAttendance/index');
    }

    // =========================
    // SMART SYNC (FINAL + LOCATION)
    // =========================
    public function sync()
    {
        // =========================
        // GET LAST SYNC
        // =========================
        $res = $this->mysql->query("
            SELECT MAX(id) as last_id, MAX(updated_at) as last_updated 
            FROM field_attendance
        ");

        $row = $res->fetch_assoc();

        $last_id      = $row['last_id'] ?? 0;
        $last_updated = $row['last_updated'] ?? '1970-01-01 00:00:00';

        // 🔥 BUFFER
        $last_updated = date('Y-m-d H:i:s', strtotime($last_updated . ' -5 seconds'));

        // =========================
        // FETCH SERVER DATA
        // =========================
        $url = "https://tassi.tracealarm.com.ph/test_dashboard/test_dashboard/cam_location/get_table_data.php?last_id=$last_id&updated_at=" . urlencode($last_updated);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 20
        ]);

        $json = curl_exec($ch);

        if ($json === false) {
            $_SESSION['sync_error'] = "cURL Error: " . curl_error($ch);
            curl_close($ch);
            return $this->redirect('syncAttendance.php');
        }

        curl_close($ch);

        if (!$json || $json == "[]") {
            $_SESSION['sync_success'] = "No new updates";
            return $this->redirect('syncAttendance.php');
        }

        $data = json_decode($json, true);

        if (!is_array($data)) {
            $_SESSION['sync_error'] = "Invalid JSON";
            return $this->redirect('syncAttendance.php');
        }

        $synced = [];
        $errors = [];

        // =========================
        // IMAGE DOWNLOADER
        // =========================
        $downloadImage = function($imagePath) {

            if (!$imagePath) return null;

            $folder = __DIR__ . '/../../images/';

            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $filename = basename($imagePath);
            $local = "images/" . $filename;
            $full  = $folder . $filename;

            if (!file_exists($full)) {

                $img = file_get_contents("https://tassi.tracealarm.com.ph/test_dashboard/test_dashboard/cam_location/" . $imagePath);

                if ($img !== false) {
                    file_put_contents($full, $img);
                } else {
                    return $imagePath; // fallback
                }
            }

            return $local;
        };

        foreach ($data as $r) {

            $id        = $r['id'];
            $card_no   = $r['card_no'];
            $time_in   = $r['time_in'];
            $time_out  = $r['time_out'];
            $latitude  = $r['latitude'];
            $longitude = $r['longitude'];

            // 🔥 LOCATION
            $in_location  = $r['in_location'] ?? null;
            $out_location = $r['out_location'] ?? null;

            // 🔥 IMAGE FIX
         $image_in_path  = $r['image_in'] ?? null;

            $image_out_path = $r['image_out'] ?? null;

            if (!$image_out_path && $r['time_out'] && $r['image_path']) {
                $image_out_path = $r['image_path'];
            }

            $updated_at = $r['updated_at'] ?? date('Y-m-d H:i:s');
            $is_deleted = $r['is_deleted'] ?? 0;

            // DOWNLOAD
            $image_in  = $downloadImage($image_in_path);
            $image_out = $downloadImage($image_out_path);

            // ESCAPE
            $card_no   = $this->mysql->real_escape_string($card_no);
            $time_in   = $this->mysql->real_escape_string($time_in);
            $latitude  = $this->mysql->real_escape_string($latitude);
            $longitude = $this->mysql->real_escape_string($longitude);
            $updated_at= $this->mysql->real_escape_string($updated_at);

            $in_location  = $in_location ? $this->mysql->real_escape_string($in_location) : null;
            $out_location = $out_location ? $this->mysql->real_escape_string($out_location) : null;

            $time_out_sql = $time_out 
                ? "'" . $this->mysql->real_escape_string($time_out) . "'" 
                : "NULL";

            $image_in_sql = $image_in 
                ? "'" . $this->mysql->real_escape_string($image_in) . "'" 
                : "NULL";

            $image_out_sql = $image_out 
                ? "'" . $this->mysql->real_escape_string($image_out) . "'" 
                : "NULL";

            // =========================
            // FINAL UPSERT
            // =========================
            $sql = "
            INSERT INTO field_attendance 
            (
                id, card_no, time_in, time_out,
                latitude, longitude,
                in_location, out_location,
                image_in, image_out,
                updated_at, is_deleted
            )
            VALUES (
                '$id',
                '$card_no',
                '$time_in',
                $time_out_sql,
                '$latitude',
                '$longitude',
                " . ($in_location ? "'$in_location'" : "NULL") . ",
                " . ($out_location ? "'$out_location'" : "NULL") . ",
                $image_in_sql,
                $image_out_sql,
                '$updated_at',
                '$is_deleted'
            )
            ON DUPLICATE KEY UPDATE
                card_no = VALUES(card_no),
                time_in = IFNULL(VALUES(time_in), time_in),
                time_out = IFNULL(VALUES(time_out), time_out),
                latitude = VALUES(latitude),
                longitude = VALUES(longitude),
                in_location = IFNULL(VALUES(in_location), in_location),
                out_location = IFNULL(VALUES(out_location), out_location),
                image_in = IFNULL(VALUES(image_in), image_in),
                image_out = IFNULL(VALUES(image_out), image_out),
                updated_at = VALUES(updated_at),
                is_deleted = VALUES(is_deleted)
            ";

            if (!$this->mysql->query($sql)) {
                $errors[] = "Error ID $id: " . $this->mysql->error;
            } else {
                $synced[] = $id;
            }
        }

        $_SESSION['sync_success'] = count($synced) . " records synced";
        $_SESSION['sync_errors']  = $errors;

        return $this->redirect('syncAttendance.php');
    }

    // =========================
    // LOCAL API
    // =========================
    public function getLocalData()
    {
        header("Content-Type: application/json");

        $result = $this->mysql->query("
            SELECT * FROM field_attendance 
            WHERE is_deleted = 0
            AND time_in != '0000-00-00 00:00:00'
            ORDER BY id DESC
        ");

        $data = [];

        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        exit;
    }
}