<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <title>Task Manager</title>
</head>
<body>
    <div id="wrapper">
        <div id="side-panel" class="container">
            <div id="account">
                <img src="css/images/profile.png" alt="profile" id="profile">
                <h1>Lorem Ipsum Name</h1>
                <button><img src="css/images/notification-button.png" alt="notification" id="notification-button"></button>
            </div>
            <div id="categories">
                <h6>Your Categories</h6>
                    <button id="category">
                        <div class="bullet"></div>
                        <p>Work</p>
                        <p class="count">42</p>
                    </button>
                    <button id="category">
                        <div class="bullet"></div>
                        <p>School</p>
                        <p class="count">42</p>
                    </button>
                    <button id="category">
                        <div class="bullet"></div>
                        <p>Home</p>
                        <p class="count">42</p>
                    </button>
            </div>
            <div id="filters">
                <h6>Filter</h6>
                    <button id="filter">
                        <div class="bullet"></div>
                        <p>Today</p>
                        <p class="count">42</p>
                    </button>
                    <button id="filter">
                        <div class="bullet"></div>
                        <p>This Week</p>
                        <p class="count">42</p>
                    </button>
                    <button id="filter">
                        <div class="bullet"></div>
                        <p>This month</p>
                        <p class="count">42</p>
                    </button>
                    <button id="filter">
                        <div class="bullet"></div>
                        <p>Unscheduled</p>
                        <p class="count">42</p>
                    </button>
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
            <div class="RecentlyAdded-tasks">
                <h1>Recently added</h1>
                <button class="task">
                    <div class="indicator"></div>
                        <div class="task-details">
                            <span id="title">Dogs!</span>
                            <span id="description">buy dog food!</span>
                        </div>
                    <div class="task-setting"></div>
                </buttton>
            </div>
        </div>
    </div>
</body>
</html>
