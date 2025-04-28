<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Konfigurasi database (sesuaikan dengan setting Anda)
$dbHost = 'localhost';
$dbUser = 'root';      // Username database
$dbPass = '';          // Password database
$dbName = 'task_manager'; // Nama database

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . $e->getMessage()]));
}

// Ambil aksi dari request
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

switch ($action) {
    case 'get_tasks':
        try {
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data: ' . $e->getMessage()]);
        }
        break;
        
    case 'add_task':
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (name, description, start_date, end_date, priority, assign_to) 
                                  VALUES (:name, :desc, :start, :end, :priority, :assign)");
            
            $stmt->execute([
                ':name' => $input['name'],
                ':desc' => $input['desc'],
                ':start' => $input['start'],
                ':end' => $input['end'],
                ':priority' => $input['priority'],
                ':assign' => $input['assign']
            ]);
            
            echo json_encode([
                'success' => true,
                'id' => $pdo->lastInsertId(),
                'message' => 'Tugas berhasil ditambahkan'
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal menambah tugas: ' . $e->getMessage()]);
        }
        break;
        
    case 'update_status':
        try {
            $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :id");
            $stmt->execute([
                ':status' => $input['status'],
                ':id' => $input['id']
            ]);
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()]);
        }
        break;
        
    case 'delete_task':
        try {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
            $stmt->execute([':id' => $input['id']]);
            
            echo json_encode(['success' => true, 'message' => 'Tugas berhasil dihapus']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus tugas: ' . $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
        break;
}
?>