<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mobile Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="profile">
                <img src="{{ asset('assets/profile.jpg') }}" alt="Profile">
                <div class="profile-text">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="welcome">Welcome!</div>
                </div>
            </div>
            <form method="POST" action="{{ route('dashboard') }}">
                @csrf
                <button type="submit" class="notification-btn">🔔 Logout</button>
            </form>
        </div>

        <!-- Status Buttons -->
        <div class="status-buttons">
            <button>Urgent</button>
            <button>In Progress</button>
            <button>Completed</button>
        </div>

        <!-- Task Card -->
        <div class="task-container">
            <button class="urgent-btn">URGENT</button>
            <div class="task-title">Due Today: Submit Report</div>
            <div class="task-desc">You need to finalize and submit the quarterly report by 3 PM today.</div>
            <div class="task-action">
                <button>View</button>
            </div>
        </div>

        <!-- Task Overview -->
        <div class="overview-container">
            <div class="overview-header">
                <h3>Task Overview</h3>
                <button>View All</button>
            </div>
            <div class="overview-grid">
                <div class="overview-box">Today: 3 Tasks</div>
                <div class="overview-box">Urgent: 1 Task</div>
                <div class="overview-box">In Progress: 2 Tasks</div>
                <div class="overview-box">Completed: 5 Tasks</div>
            </div>
        </div>
    </div>
</body>

</html>