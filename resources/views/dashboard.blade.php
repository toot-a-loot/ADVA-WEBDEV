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
    @if(Auth::check())
        <script>console.log('User authenticated. ID:', '{{ Auth::id() }}');</script>
    @else
        <script>console.log('User not authenticated!');</script>
    @endif

    <!-- Debug information -->
    <div id="debug-info" style="display: none;">
        <p>Authentication Status: {{ Auth::check() ? 'Authenticated' : 'Not Authenticated' }}</p>
        <p>User ID: {{ Auth::id() ?? 'No ID' }}</p>
    </div>

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
                <h1></h1>
                <div id="urgent-tasks-list"></div>
            </div>

            <div class="RecentlyAdded-tasks">
                <h1>Recently added</h1>
                <div id="recent-tasks-list"></div>
            </div>

            <div id="tasks-container">
                <!-- Tasks will be rendered here -->
            </div>
        </div>
    </div>
    <!-- Logout Modal -->
    <div class="modal-overlay" id="logoutModalOverlay"></div>
    <div class="logout-modal" id="logoutModal">
        <h2>Are you sure you want to logout?</h2>
        <div class="modal-buttons">
            <button class="cancel-btn" onclick="closeLogoutModal()">Cancel</button>
            <button class="logout-confirm-btn" onclick="confirmLogout()">Logout</button>
        </div>
    </div>

    <!-- Delete Task Modal -->
    <div class="modal-overlay" id="deleteModalOverlay"></div>
    <div class="modal" id="deleteTaskModal">
        <h2>Delete Task</h2>
        <p>Are you sure you want to delete this task?</p>
        <div class="modal-buttons">
            <button class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
            <button class="delete-btn" onclick="confirmDeleteTask()">Delete</button>
        </div>
    </div>

    <script>
        // Show debug panel with keyboard shortcut (Ctrl+Shift+D)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                const debugInfo = document.getElementById('debug-info');
                debugInfo.style.display = debugInfo.style.display === 'none' ? 'block' : 'none';
            }
        });

        document.getElementById('notification-btn').onclick = function (e) {
            e.stopPropagation();
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.addEventListener('click', function () {
            var dropdown = document.getElementById('notification-dropdown');
            if (dropdown) dropdown.style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', () => {
            fetch("{{ route('tasks.user') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Response not OK:', {
                            status: response.status,
                            statusText: response.statusText,
                            text: text
                        });
                        throw new Error('Server error: ' + text);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);

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
            .catch(err => {
                console.error('Fetch error:', err);
                document.getElementById('tasks-container').innerHTML = '<p>Error: ' + err.message + '</p>';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ url('/tasks/user') }}')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('tasks-container');
            if (data.error) {
                container.innerHTML = '<p>Error: ' + data.error + '</p>';
                return;
            }
            if (data.length === 0) {
                container.innerHTML = '<p>No tasks found.</p>';
                return;
            }
            // let html = '<ul>';
            // data.forEach(task => {
            //     html += `<li>
            //         <strong>${task.title}</strong> - ${task.status}
            //         <br>Due: ${task.due_date ? task.due_date : 'N/A'}
            //         <br>Content: ${task.content}
            //     </li>`;
            // });
            // html += '</ul>';
            // container.innerHTML = html;
        })
        .catch(err => {
            document.getElementById('tasks-container').innerHTML = '<p>Error loading tasks.</p>';
        });
});
    </script>

    <script>
        // Logout Modal Functionality
        const logoutModal = document.getElementById('logoutModal');
        const logoutModalOverlay = document.getElementById('logoutModalOverlay');
        const logoutButton = document.querySelector('.logout-button');

        // Show modal when clicking logout button
        logoutButton.addEventListener('click', function() {
            logoutModal.style.display = 'block';
            logoutModalOverlay.style.display = 'block';
        });

        // Close modal function
        function closeLogoutModal() {
            logoutModal.style.display = 'none';
            logoutModalOverlay.style.display = 'none';
        }

        // Handle modal close when clicking outside
        logoutModalOverlay.addEventListener('click', closeLogoutModal);

        // Confirm logout function
        function confirmLogout() {
            // Create a form for the logout POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && logoutModal.style.display === 'block') {
                closeLogoutModal();
            }
        });
    </script>

    <script>
        let taskToDelete = null;

        function showDeleteModal(taskId, event) {
            // Prevent the event from bubbling up
            event.stopPropagation();

            taskToDelete = taskId;
            document.getElementById('deleteTaskModal').style.display = 'block';
            document.getElementById('deleteModalOverlay').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteTaskModal').style.display = 'none';
            document.getElementById('deleteModalOverlay').style.display = 'none';
            taskToDelete = null;
        }

        function confirmDeleteTask() {
            if (!taskToDelete) return;

            // Create form for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/tasks/${taskToDelete}`;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add method override for DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('deleteModalOverlay').addEventListener('click', closeDeleteModal);

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('deleteTaskModal').style.display === 'block') {
                closeDeleteModal();
            }
        });
    </script>

    <style>
        /* Logout Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .logout-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            text-align: center;
        }

        .logout-modal h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: #e0e0e0;
            cursor: pointer;
            transition: background 0.3s;
        }

        .cancel-btn:hover {
            background: #d0d0d0;
        }

        .logout-confirm-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: #ff6b6b;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-confirm-btn:hover {
            background: #ff5252;
        }

        /* Delete Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            text-align: center;
        }

        .delete-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: #dc3545;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        /* Make tasks clickable */
        .task {
            position: relative;
        }

        .task-delete-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 5px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .task:hover .task-delete-btn {
            opacity: 1;
        }
    </style>
</body>

</html>
