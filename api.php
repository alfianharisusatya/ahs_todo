<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$dbHost = 'localhost';
$dbUser = 'username'; // Ganti dengan username MySQL Anda
$dbPass = 'password'; // Ganti dengan password MySQL Anda
$dbName = 'task_manager';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_tasks':
        try {
            $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'add_task':
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (name, description, start_date, end_date, priority, assign_to, status) 
                                  VALUES (?, ?, ?, ?, ?, ?, 'todo')");
            $stmt->execute([
                $data['name'],
                $data['desc'],
                $data['start'],
                $data['end'],
                $data['priority'],
                $data['assign']
            ]);
            
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    case 'update_status':
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
            $stmt->execute([$data['status'], $data['id']]);
            
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>