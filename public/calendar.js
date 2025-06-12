document.addEventListener('DOMContentLoaded', () => {
    const currentMonthYear = document.getElementById('currentMonthYear');
    const calendarDates = document.getElementById('calendarDates');
    const prevMonthBtn = document.getElementById('previous-month');
    const nextMonthBtn = document.getElementById('next-month');

    let currentDate = new Date(); // Start with the current date
    let taskMap = {}; // Will hold dates as keys and task arrays as values

    // Fetch tasks from backend
    async function fetchTasks() {
        try {
            const response = await fetch('/tasks/fetch');
            const data = await response.json();

            taskMap = {}; // Reset task map

            (Array.isArray(data) ? data : []).forEach(task => {
                const dueDate = task.due_date ? new Date(task.due_date) : null;
                if (dueDate) {
                    const key = dueDate.toISOString().split('T')[0]; // YYYY-MM-DD
                    if (!taskMap[key]) taskMap[key] = [];
                    taskMap[key].push(task);
                }
            });

            renderCalendar();
        } catch (err) {
            console.error('Failed to fetch tasks for calendar:', err);
        }
    }

    function renderCalendar() {
        calendarDates.innerHTML = ''; // Clear previous dates

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth(); // 0-indexed (0 for January)

        currentMonthYear.textContent = new Date(year, month, 1).toLocaleString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const startDate = new Date(year, month, 1 - firstDayOfMonth);
        const totalCells = 35;

        const today = new Date();

        for (let i = 0; i < totalCells; i++) {
            const displayDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + i);
            const displayDateStr = displayDate.toISOString().split('T')[0];

            const dateDiv = document.createElement('div');
            const dateNumberSpan = document.createElement('span');
            dateNumberSpan.classList.add('date-number');
            dateNumberSpan.textContent = displayDate.getDate();
            dateDiv.appendChild(dateNumberSpan);

            if (displayDate.getMonth() !== month) {
                dateDiv.classList.add('empty');
            } else if (
                displayDate.getDate() === today.getDate() &&
                displayDate.getMonth() === today.getMonth() &&
                displayDate.getFullYear() === today.getFullYear()
            ) {
                dateDiv.classList.add('today');
            }

            // 🔔 If there are tasks on this date, display indicator
            if (taskMap[displayDateStr]) {
                const taskDot = document.createElement('div');
                taskDot.classList.add('task-dot'); // Add CSS for this
                taskDot.title = taskMap[displayDateStr].map(t => t.title).join(', ');
                dateDiv.appendChild(taskDot);
            }

            calendarDates.appendChild(dateDiv);
        }
    }

    // Button navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Initial load
    fetchTasks();
});
