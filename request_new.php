<?php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ' . url('auth/login.php'));
    exit;
}

$msg = '';
// Detect optional new schema columns
$hasCategory = false;
if ($cc = $mysqli->query("SHOW COLUMNS FROM requests LIKE 'category'")) { $hasCategory = $cc->num_rows>0; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device   = trim($_POST['device_type']);
    $model    = trim($_POST['model']);
    $serial   = trim($_POST['serial_no']);
    $priority = $_POST['priority'];
    $category = $_POST['category'] ?? '';
    $desc     = trim($_POST['description']);
    $validCats = ['Hardware Failure','Software Issue','Physical Damage','Other'];
    if ($device !== '' && $desc !== '' && (!$hasCategory || in_array($category,$validCats,true))) {
        if ($hasCategory) {
            $stmt = $mysqli->prepare('INSERT INTO requests (user_id, device_type, model, serial_no, priority, category, description, created_at) VALUES (?,?,?,?,?,?,?,NOW())');
            $stmt->bind_param('issssss', $_SESSION['user_id'], $device, $model, $serial, $priority, $category, $desc);
        } else {
            // Fallback for pre-migration schema
            $stmt = $mysqli->prepare('INSERT INTO requests (user_id, device_type, model, serial_no, priority, description, created_at) VALUES (?,?,?,?,?,?,NOW())');
            $stmt->bind_param('isssss', $_SESSION['user_id'], $device, $model, $serial, $priority, $desc);
        }
        if ($stmt->execute()) {
            $msg = 'Request saved';
        } else {
            $msg = 'Error saving';
        }
    } else {
        $msg = 'Fill required fields';
    }
}
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<?php include BASE_PATH . '/partials/nav.php'; ?>

<div class="container">
    <h1>Submit New Repair Request</h1>
    <p>Fill out the form below to submit a new hardware repair request. Fields marked with * are required.</p>
    
    <?php if ($msg): ?>
        <div class="<?php echo ($msg === 'Request saved') ? 'success' : 'alert'; ?>">
            <?php echo htmlspecialchars($msg); ?>
            <?php if ($msg === 'Request saved'): ?>
                <br><a href="<?php echo url('my_requests.php'); ?>">View your requests</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="request-form">
        <form method="post">
            <div class="form-row">
                <label>Device Type *</label>
                <input name="device_type" required placeholder="e.g., Laptop, Desktop, Printer, Tablet" 
                       value="<?php echo isset($_POST['device_type']) ? htmlspecialchars($_POST['device_type']) : ''; ?>">
                <small class="form-help">Specify the type of device that needs repair</small>
            </div>

            <div class="form-row">
                <label>Model</label>
                <input name="model" placeholder="e.g., Dell Inspiron 15, HP LaserJet Pro" 
                       value="<?php echo isset($_POST['model']) ? htmlspecialchars($_POST['model']) : ''; ?>">
                <small class="form-help">Device model if known</small>
            </div>

            <div class="form-row">
                <label>Serial Number</label>
                <input name="serial_no" placeholder="Device serial number if available" 
                       value="<?php echo isset($_POST['serial_no']) ? htmlspecialchars($_POST['serial_no']) : ''; ?>">
                <small class="form-help">Usually found on a sticker on the device</small>
            </div>

            <div class="form-row">
                <label>Priority</label>
                <select name="priority">
                    <option value="Low" <?php echo (isset($_POST['priority']) && $_POST['priority'] === 'Low') ? 'selected' : ''; ?>>Low - Can wait for scheduling</option>
                    <option value="Medium" <?php echo (!isset($_POST['priority']) || $_POST['priority'] === 'Medium') ? 'selected' : ''; ?>>Medium - Standard priority</option>
                    <option value="High" <?php echo (isset($_POST['priority']) && $_POST['priority'] === 'High') ? 'selected' : ''; ?>>High - Urgent repair needed</option>
                </select>
                <small class="form-help">Select based on how urgently you need the repair</small>
            </div>

            <?php if ($hasCategory) { ?>
            <div class="form-row">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">-- Select Category --</option>
                    <?php $sel = $_POST['category'] ?? ''; foreach(['Hardware Failure','Software Issue','Physical Damage','Other'] as $c){ $s=$sel===$c?'selected':''; echo '<option '.$s.'>'.htmlspecialchars($c).'</option>'; } ?>
                </select>
            </div>
            <?php } ?>

            <div class="form-row">
                <label>Problem Description *</label>
                <textarea name="description" required placeholder="Describe the problem in detail. Include error messages, symptoms, and when the issue started."><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                <small class="form-help">The more details you provide, the better we can help you</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Submit Request</button>
                <a href="<?php echo url('my_requests.php'); ?>" class="btn-secondary">View My Requests</a>
            </div>
        </form>
    </div>

    <div class="request-tips mt">
        <div class="card">
            <h3>Tips for Better Service</h3>
            <ul>
                <li>Provide as much detail as possible about the problem</li>
                <li>Include any error messages you've seen</li>
                <li>Mention what you were doing when the problem occurred</li>
                <li>Note if the problem is intermittent or constant</li>
                <li>Include any troubleshooting steps you've already tried</li>
            </ul>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
