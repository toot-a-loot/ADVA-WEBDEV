<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/edit-task.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class="desktop-container">
        <div class="top-panel">
            <button class="back-button">
                <img src="{{ asset('css/images/back.png') }}" alt="Back">
            </button>
        </div>
        <div class="left-panel">
            <button class="add-task">
                <img src="{{ asset('css/images/task.png') }}" alt="Add Task">
                Task
            </button>
            <button class="save-button">
                <img src="{{ asset('css/images/save.png') }}" alt="Save">
                Save
            </button>
        </div>
        <div class="background">
            <div id="spawn-container"></div>
        </div>
    </div>
    <script>
    const base = window.location.pathname.startsWith('/ADVA-WEBDEV/public') ? '/ADVA-WEBDEV/public' : '';

    let spawnCount = 0;
    document.querySelector('.add-task').addEventListener('click', function() {
    fetch(base + '/spawn/task')
        .then(res => res.text())
        .then(html => {
            const temp = document.createElement('div');
            temp.innerHTML = html.trim();
            const card = temp.firstElementChild;
            const offset = 40 * spawnCount;
            card.style.left = (100 + offset) + 'px';
            card.style.top = (100 + offset) + 'px';

            card.id = 'task-' + Date.now() + '-' + Math.floor(Math.random()*10000);
            card.ondragstart = function(ev) {
                ev.dataTransfer.setData("text", card.id);
            };

            makeDraggable(card);
            makeResizable(card);
            initializeTaskCard(card); // Add this line
            document.getElementById('spawn-container').appendChild(card);
            spawnCount++;
        });
    });

    function initializeTaskCard(card) {
        const header = card.querySelector('.header');
        const statusOptions = card.querySelectorAll('.status-option');
        const menuDots = card.querySelector('.menu-dots');
        const statusMenu = card.querySelector('.status-menu');

        // Toggle menu on click instead of hover
        menuDots.addEventListener('click', (e) => {
            e.stopPropagation();
            statusMenu.style.display = statusMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close menu when clicking outside
        document.addEventListener('click', () => {
            statusMenu.style.display = 'none';
        });

        // Status change handling
        statusOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const status = option.dataset.status;
                header.dataset.status = status;
                statusDot.dataset.status = status;
                statusMenu.style.display = 'none';
            });
        });
        const addTaskBtn = card.querySelector('.add-task');
        const taskList = card.querySelector('.task-list');

        function createTaskItem() {
            const li = document.createElement('li');
            li.className = 'task-list-item';
            li.innerHTML = `
                <span contenteditable="true" spellcheck="false">New task</span>
                <span class="remove-task-item">—</span>
            `;

            // Add click handler for remove button
            li.querySelector('.remove-task-item').addEventListener('click', () => {
                li.remove();
            });

            return li;
        }

        taskList.appendChild(createTaskItem());

        addTaskBtn.addEventListener('click', () => {
            taskList.appendChild(createTaskItem());
        });
    }
    function makeDraggable(card) {
        const header = card.querySelector('.header');
        let offsetX = 0, offsetY = 0, isDragging = false;

        function onDrag(e) {
            if (!isDragging) return;
            e.preventDefault();
            const container = document.getElementById('spawn-container');
            const containerRect = container.getBoundingClientRect();
            let x = e.clientX - containerRect.left - offsetX;
            let y = e.clientY - containerRect.top - offsetY;

            // Smoother boundary checking
            x = Math.max(0, Math.min(x, container.offsetWidth - card.offsetWidth));
            y = Math.max(0, Math.min(y, container.offsetHeight - card.offsetHeight));

            requestAnimationFrame(() => {
                card.style.left = `${x}px`;
                card.style.top = `${y}px`;
            });
        }

        function stopDrag() {
            if (!isDragging) return;
            isDragging = false;
            document.body.style.userSelect = '';
            document.removeEventListener('mousemove', onDrag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('mouseleave', stopDrag);
        }

        header.addEventListener('mousedown', function(e) {
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
            e.preventDefault();
            isDragging = true;
            const rect = card.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            document.body.style.userSelect = 'none';
            document.addEventListener('mousemove', onDrag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('mouseleave', stopDrag);
        });
    }

    function makeResizable(card) {
    const handle = card.querySelector('.resizable-handle');
    let isResizing = false, startX, startY, startW, startH;

    function getMinimumSize() {
        // Create a clone to measure actual content size
        const clone = card.cloneNode(true);
        clone.style.position = 'absolute';
        clone.style.visibility = 'hidden';
        clone.style.width = 'auto';
        clone.style.height = 'auto';
        document.body.appendChild(clone);

        // Get the content size
        const rect = clone.getBoundingClientRect();
        document.body.removeChild(clone);

        return {
            width: Math.max(220, rect.width),  // minimum 220px or content width
            height: Math.max(130, rect.height) // minimum 130px or content height
        };
    }

    function onResize(e) {
        if (!isResizing) return;
        e.preventDefault();

        const minSize = getMinimumSize();
        let newW = startW + (e.clientX - startX);
        let newH = startH + (e.clientY - startY);

        // Prevent resizing smaller than content
        newW = Math.max(minSize.width, newW);
        newH = Math.max(minSize.height, newH);

        requestAnimationFrame(() => {
            card.style.width = `${newW}px`;
            card.style.height = `${newH}px`;
        });
    }

        function stopResize() {
            if (!isResizing) return;
            isResizing = false;
            document.body.style.userSelect = '';
            document.removeEventListener('mousemove', onResize);
            document.removeEventListener('mouseup', stopResize);
            document.removeEventListener('mouseleave', stopResize);
        }

        handle.addEventListener('mousedown', function(e) {
            e.stopPropagation();
            e.preventDefault();
            isResizing = true;
            startX = e.clientX;
            startY = e.clientY;
            startW = card.offsetWidth;
            startH = card.offsetHeight;
            document.body.style.userSelect = 'none';
            document.addEventListener('mousemove', onResize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('mouseleave', stopResize);
        });
    }
    document.querySelector('.back-button').addEventListener('click', function() {
        window.location.href = base + '/dashboard';
    });

    document.querySelector('.save-button').addEventListener('click', function() {
    // Collect all tasks
    const tasks = [];
    document.querySelectorAll('.task-card').forEach(card => {
        const taskItems = [];
        card.querySelectorAll('.task-list-item span[contenteditable="true"]').forEach(item => {
            if (item.textContent.trim()) {  // Only add non-empty items
                taskItems.push(item.textContent.trim());
            }
        });

        tasks.push({
            title: card.querySelector('.task-title').textContent.trim(),
            status: card.querySelector('.header').dataset.status || 'ongoing',
            items: taskItems,
            position: {
                left: card.style.left || '0px',
                top: card.style.top || '0px',
                width: card.style.width || '220px',
                height: card.style.height || '130px'
            }
        });
    });

    console.log('Saving tasks:', tasks); // Debug log

    fetch(base + '/tasks/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ tasks: tasks })
    })
    .then(async response => {
        const data = await response.json();
        console.log('Response:', data); // Debug log
        if (!response.ok) {
            throw new Error(data.error || 'Network response was not ok');
        }
        return data;
    })
    .then(data => {
        alert('Tasks saved successfully!');
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Error saving tasks: ' + error.message);
    });
    });
    </script>
</body>
</html>
