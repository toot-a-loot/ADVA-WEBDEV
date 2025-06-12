document.addEventListener('DOMContentLoaded', () => {
    const currentMonthYear = document.getElementById('currentMonthYear');
    const calendarDates = document.getElementById('calendarDates');
    const prevMonthBtn = document.getElementById('previous-month');
    const nextMonthBtn = document.getElementById('next-month');

    let currentDate = new Date(); // Start with the current date

    function renderCalendar() {
        calendarDates.innerHTML = ''; // Clear previous dates

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth(); // 0-indexed (0 for January)

        // Set the header text (e.g., "May 2023")
        currentMonthYear.textContent = new Date(year, month, 1).toLocaleString('en-US', {
            month: 'long',
            year: 'numeric'
        });

        // Get the first day of the month (0 = Sunday, 1 = Monday, etc.)
        const firstDayOfMonth = new Date(year, month, 1).getDay();

        // Calculate the starting date to display (can be from previous month)
        const startDate = new Date(year, month, 1 - firstDayOfMonth);

        // Display a fixed number of cells: 5 weeks (5 rows * 7 columns = 35 cells)
        const totalCells = 35; // Changed from 42 to 35

        const today = new Date(); // Get today's date once for comparison

        for (let i = 0; i < totalCells; i++) {
            const displayDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate() + i);

            const dateDiv = document.createElement('div');
            const dateNumberSpan = document.createElement('span');
            dateNumberSpan.classList.add('date-number');
            dateNumberSpan.textContent = displayDate.getDate();
            dateDiv.appendChild(dateNumberSpan);

            // Add 'empty' class for days not in the current month (for faded appearance)
            if (displayDate.getMonth() !== month) {
                dateDiv.classList.add('empty');
            } else {
                // Check if it's today's date
                if (displayDate.getDate() === today.getDate() &&
                    displayDate.getMonth() === today.getMonth() &&
                    displayDate.getFullYear() === today.getFullYear()) {
                    dateDiv.classList.add('today');
                }
            }
            calendarDates.appendChild(dateDiv);
        }
    }

    // Event Listeners for navigation buttons
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    // Initial render
    renderCalendar();
});
