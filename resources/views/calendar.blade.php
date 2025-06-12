<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">
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
            <button class="settings-button">
                <img src="css/images/settings-button.png" alt="settings">
            </button>
        </div>
        <div id="main-pannel" class="container">
            <div id="top-barCalendar">
                <div id="searchbar">
                    <input type="text" id="search-input" placeholder="Look up your task here!">
                    <button class="search-button"><img src="css/images/search-button.png" alt="search" id="search-button"></button>
                </div>
                <a href="{{ url('/dashboard') }}"><img src="css/images/home-button.png" alt="calendar" class="calendar-button"></a>
            </div>
                <div class="calendar-container">
                    <div class="calendar-header">
                        <h2 id="currentMonthYear"></h2>
                        <button id="previous-month"><img src="css/images/previous-month.png" alt="back" class="previous-month"></button>
                        <button id="next-month"><img src="css/images/next-month.png" alt="next" class="next-month"></button>
                    </div>
                    <div class="calendar-weekdays">
                        <div>Sunday</div>
                        <div>Monday</div>
                        <div>Tuesday</div>
                        <div>Wednesday</div>
                        <div>Thursday</div>
                        <div>Friday</div>
                        <div>Saturday</div>
                    </div>
                    <div class="calendar-dates" id="calendarDates">
                        </div>
                </div>
        </div>
    </div>

    <script src="calendar.js"></script>
</body>
</html>
