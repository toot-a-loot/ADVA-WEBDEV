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
</body>

</html>