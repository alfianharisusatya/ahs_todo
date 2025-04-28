<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';    // Default username for XAMPP
$dbPass = '';        // Default password for XAMPP (empty)
$dbName = 'task_manager';

// Create database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
<?php
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
} else {
    // Debug message
    error_log('Database connected successfully');
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? $input['action'] ?? '';

// Process actions
switch ($action) {
    case 'add_task':
        // Validate input
        if (empty($input['name']) || empty($input['start']) || empty($input['end']) || empty($input['assign'])) {
            echo json_encode([
                'success' => false,
                'message' => 'All fields are required'
            ]);
            exit;
        }

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO tasks (name, description, start_date, end_date, priority, assign_to) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssss",
            $input['name'],
            $input['desc'] ?? '',
            $input['start'],
            $input['end'],
            $input['priority'],
            $input['assign']
        );

        // Execute query
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'id' => $stmt->insert_id,
                'message' => 'Task added successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $stmt->error
            ]);
        }

        $stmt->close();
        break;

    case 'get_tasks':
        $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
        $result = $conn->query($sql);

        $tasks = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
        }

        echo json_encode($tasks);
        break;

    default:
        echo json_encode([
            'success' => false,
            'message' => 'Unknown action'
        ]);
}

$conn->close();
?>