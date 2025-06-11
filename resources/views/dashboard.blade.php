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
            <form method="POST" action="{{ route('logout') }}">
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


        <!-- Task Cards (Dynamic) -->
@if(isset($tasks) && count($tasks) > 0)
    @foreach($tasks as $task)
        <div class="task-container">
            @if(isset($task->is_urgent) && $task->is_urgent)
                <button class="urgent-btn">URGENT</button>
            @endif
            <div class="task-title">{{ $task->title }}</div>
            <div class="task-desc">{{ $task->description }}</div>
            <div class="task-action">
                <!-- View Button (link to a show page if you have one) -->
                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info">View</a>
                <!-- Edit Button -->
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit</a>
                <!-- Delete Button -->
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
@else
    <div>No tasks found.</div>
@endif

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