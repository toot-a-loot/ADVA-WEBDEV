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
                <button><img src="css/images/notification-button.png" alt="notification"
                        id="notification-button"></button>
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
                <button class="task">
                    <div class="indicator"></div>
                    <div class="task-details">
                        <span id="title">Due Today!</span>
                        <span id="description">Imong SRS!</span>
                    </div>
                    <div class="task-setting"></div>
                </button>
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

    <!-- Logout Modal -->
    <div class="modal-overlay" id="logoutModalOverlay"></div>
    <div class="logout-modal" id="logoutModal">
        <h2>Are you sure you want to logout?</h2>
        <div class="modal-buttons">
            <button class="cancel-btn" onclick="closeLogoutModal()">Cancel</button>
            <button class="logout-confirm-btn" onclick="confirmLogout()">Log out</button>
        </div>
    </div>

    <script>
        // Get the logout button and modal elements
        const logoutButton = document.querySelector('.logout-button');
        const modalOverlay = document.getElementById('logoutModalOverlay');
        const logoutModal = document.getElementById('logoutModal');

        // Function to show the modal
        function showLogoutModal() {
            modalOverlay.style.display = 'block';
            logoutModal.style.display = 'block';
        }

        // Function to close the modal
        function closeLogoutModal() {
            modalOverlay.style.display = 'none';
            logoutModal.style.display = 'none';
        }

        // Function to handle logout
        function confirmLogout() {
            window.location.href = "{{ url('/login') }}";
        }

        // Add click event listener to the logout button
        logoutButton.addEventListener('click', showLogoutModal);

        // Close modal when clicking outside
        modalOverlay.addEventListener('click', closeLogoutModal);
    </script>
</body>

</html>