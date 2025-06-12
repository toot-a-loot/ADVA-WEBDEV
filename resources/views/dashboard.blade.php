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

                <button id="notification-btn" style="background:none;border:none;cursor:pointer;">
                    <img src="css/images/notification-button.png" alt="notification" id="notification-button">
                </button>
                <!-- Notification Dropdown -->
                <div id="notification-dropdown" class="notification-dropdown"
                    style="display:none; position:absolute; top:55px; right:10px; z-index:1000;">
                    <div class="notification-row">
                        <div class="notification-indicator"></div>
                        <div class="notification-main">
                            <span class="notification-title">Dogs!</span>
                            <span class="notification-desc">buy dog food!</span>
                        </div>
                        <span class="notification-due">Due: June 13</span>
                        <span class="notification-remove">—</span>
                    </div>
                    <div class="notification-row">
                        <div class="notification-indicator"></div>
                        <div class="notification-main">
                            <span class="notification-title">Imong SRS!</span>
                        </div>
                        <span class="notification-due">Due: June 12</span>
                        <span class="notification-remove">—</span>
                    </div>
                    <div class="notification-row">
                        <div class="notification-indicator"></div>
                        <div class="notification-main">
                            <span class="notification-title">Dogs!</span>
                            <span class="notification-desc">buy dog food!</span>
                        </div>
                        <span class="notification-due">Due: June 13</span>
                        <span class="notification-remove">—</span>
                    </div>
                </div>
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
            <button class="logout-button">
                <img src="css/images/logout-button.png" alt="logout">
            </button>
        </div>
        <div id="main-panel" class="container">
            <div id="top-bar">
                <a href="{{ url('/task') }}"><img src="css/images/add-task.png" alt="add"></a>
                <div id="searchbar">
                    <input type="text" id="search-input" placeholder="Look up your task here!">
                    <button class="search-button"><img src="css/images/search-button.png" alt="search"
                            id="search-button"></button>
                </div>
                <a href="{{ url('/calendar') }}"><img src="css/images/calendar-button.png" alt="calendar"
                        class="calendar-button"></a>
            </div>
            <div class="Urgent-tasks">
    <h1>Urgent</h1>
    <div id="urgent-tasks-list"></div>
</div>

<div class="RecentlyAdded-tasks">
    <h1>Recently added</h1>
    <div id="recent-tasks-list"></div>
</div>
        </div>
    </div>
    <script>
document.getElementById('notification-btn').onclick = function(e) {
    e.stopPropagation();
    var dropdown = document.getElementById('notification-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
};
document.addEventListener('click', function() {
    var dropdown = document.getElementById('notification-dropdown');
    if(dropdown) dropdown.style.display = 'none';
});

    document.addEventListener('DOMContentLoaded', () => {
    fetch('/tasks/fetch')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            const urgentList = document.getElementById('urgent-tasks-list');
            const recentList = document.getElementById('recent-tasks-list');

            // Clear current tasks
            urgentList.innerHTML = '';
            recentList.innerHTML = '';

            const tasks = Array.isArray(data) ? data : data.tasks || [];

            tasks.forEach(task => {
                const dueDate = task.due_date ? new Date(task.due_date + 'T00:00:00') : null; // Ensure date parsing is consistent
                const now = new Date();
                // Normalize 'now' to match the start of the day for comparison
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                let isUrgent = false;
                if (dueDate) {
                    const taskDueDateOnly = new Date(dueDate.getFullYear(), dueDate.getMonth(), dueDate.getDate());
                    isUrgent = (taskDueDateOnly.getTime() === today.getTime());
                }

                // If a task is urgent, it should only appear in urgent list.
                // Otherwise, it can appear in the "Recently added" list.
                // You might need more sophisticated logic here if "Recently added"
                // has a specific time-based criteria (e.g., added in the last 7 days).
                // For now, we'll put all non-urgent tasks in 'Recently added'.
                const isRecent = !isUrgent; // All tasks that are not urgent

                const taskEl = document.createElement('div');
                taskEl.className = 'task';
                taskEl.innerHTML = `
                    <div class="indicator" style="background-color: ${isUrgent ? '#FF6F62' : '#B2A0DC'};"></div>
                    <div class="task-details">
                        <span id="title">${task.title}</span>
                        <span id="description">${(JSON.parse(task.content || '[]')[0] || 'No description')}</span>
                    </div>
                    <div class="task-setting"></div>
                `;

                if (isUrgent) {
                    urgentList.appendChild(taskEl);
                } else { // All other tasks go to "Recently added"
                    recentList.appendChild(taskEl);
                }
            });
        })
        .catch(err => console.error('Failed to load tasks:', err));
        });
</script>
</body>
</html>
