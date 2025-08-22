<?php 
require_once __DIR__ . '/config.php'; 
$page_title = "Help & Support";
?>
<?php include BASE_PATH . '/partials/header.php'; ?>
<div class="container">
    <h1>Help Center</h1>
    <p>Welcome to the Hardware Repair Request Tracker help center. Find answers to common questions below.</p>

    <div class="help-sections">
        <div class="card">
            <h3>Getting Started</h3>
            <ol>
                <li><strong>Login:</strong> Use your provided username and password to access the system</li>
                <li><strong>Dashboard:</strong> View your personalized dashboard with quick actions</li>
                <li><strong>Submit Request:</strong> Click "New Request" to submit a repair request</li>
                <li><strong>Track Progress:</strong> Monitor your requests in "My Requests"</li>
            </ol>
        </div>

        <div class="card">
            <h3>For Students & Staff</h3>
            <ul>
                <li><strong>Submit New Request:</strong> Fill out all required fields including device type, model, and detailed description</li>
                <li><strong>Set Priority:</strong> Choose Low, Medium, or High based on urgency</li>
                <li><strong>Check Status:</strong> Your request will show as Pending, In Progress, Completed, or Rejected</li>
                <li><strong>View Details:</strong> Click on any request to see full information and updates</li>
            </ul>
        </div>

        <div class="card">
            <h3>For Administrators</h3>
            <ul>
                <li><strong>Manage Users:</strong> Add, edit, or remove user accounts</li>
                <li><strong>Process Requests:</strong> Update status, assign due dates, and change priorities</li>
                <li><strong>View Reports:</strong> Generate reports by status, device type, user, and time period</li>
                <li><strong>Dashboard Overview:</strong> Monitor system statistics and recent activity</li>
            </ul>
        </div>

        <div class="card">
            <h3>Request Status Guide</h3>
            <ul>
                <li><span class="badge badge-pending">Pending</span> - Request received, awaiting review</li>
                <li><span class="badge badge-inprogress">In Progress</span> - Currently being worked on</li>
                <li><span class="badge badge-completed">Completed</span> - Repair finished successfully</li>
                <li><span class="badge badge-rejected">Rejected</span> - Request could not be processed</li>
            </ul>
        </div>

        <div class="card">
            <h3>Priority Levels</h3>
            <ul>
                <li><span class="badge badge-low">Low</span> - Non-urgent, can wait for scheduling</li>
                <li><span class="badge badge-medium">Medium</span> - Standard priority request</li>
                <li><span class="badge badge-high">High</span> - Urgent, needs immediate attention</li>
            </ul>
        </div>

        <div class="card">
            <h3>Tips for Better Service</h3>
            <ul>
                <li>Provide detailed descriptions of the problem</li>
                <li>Include error messages or symptoms</li>
                <li>Mention any recent changes or events before the issue</li>
                <li>Be available for follow-up questions</li>
                <li>Set realistic priorities based on actual urgency</li>
            </ul>
        </div>

        <div class="card">
            <h3>Demo Accounts</h3>
            <p>For testing purposes, you can use these accounts:</p>
            <ul>
                <li><strong>User Account:</strong> Username: uoc, Password: uoc</li>
                <li><strong>Admin Account:</strong> Username: admin, Password: admin</li>
            </ul>
            <div class="alert">
                <strong>Note:</strong> These are demo accounts for testing only. In a production environment, use secure, unique passwords.
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/partials/footer.php'; ?>
