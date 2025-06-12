<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="{{ asset('css/temporary.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <div class="desktop-container">
        <div class="top-panel">

        </div>
        <div class="left-panel">
            <button class="add-task">
                <img src="{{ asset('css/images/task.png') }}" alt="Add Task">
                Task
            </button>
            <button class="add-column">
                <img src="{{ asset('css/images/column.png') }}" alt="Add Task">
                Column
            </button>
            <button class="add-image">
                <img src="{{ asset('css/images/image.png') }}" alt="Add Task">
                Image
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
                document.getElementById('spawn-container').appendChild(card);
                spawnCount++;
            });
    });
    document.querySelector('.add-column').addEventListener('click', function() {
        fetch(base + '/spawn/column')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html.trim();
                const card = temp.firstElementChild;
                const offset = 40 * spawnCount;
                card.style.left = (100 + offset) + 'px';
                card.style.top = (100 + offset) + 'px';
                makeDraggable(card);
                makeResizable(card);
                document.getElementById('spawn-container').appendChild(card);
                spawnCount++;
            });
    });
    document.querySelector('.add-image').addEventListener('click', function() {
        fetch(base + '/spawn/image')
            .then(res => res.text())
            .then(html => {
                const temp = document.createElement('div');
                temp.innerHTML = html.trim();
                const card = temp.firstElementChild;
                const offset = 40 * spawnCount;
                card.style.left = (100 + offset) + 'px';
                card.style.top = (100 + offset) + 'px';
                makeDraggable(card);
                makeResizable(card);
                document.getElementById('spawn-container').appendChild(card);
                spawnCount++;
                card.setAttribute('draggable', 'true');
                card.id = 'task-' + Date.now() + '-' + Math.floor(Math.random()*10000);
                card.ondragstart = function(ev) {
                    ev.dataTransfer.setData("text", card.id);
                };
            });
    });

    function makeDraggable(card) {
        const header = card.querySelector('.header');
        let offsetX = 0, offsetY = 0, isDragging = false;

        function onDrag(e) {
            if (!isDragging) return;
            const container = document.getElementById('spawn-container');
            const containerRect = container.getBoundingClientRect();
            let x = e.clientX - containerRect.left - offsetX;
            let y = e.clientY - containerRect.top - offsetY;
            x = Math.max(0, Math.min(x, container.offsetWidth - card.offsetWidth));
            y = Math.max(0, Math.min(y, container.offsetHeight - card.offsetHeight));
            card.style.left = x + 'px';
            card.style.top = y + 'px';
        }
        function stopDrag() {
            isDragging = false;
            document.body.style.userSelect = '';
            document.removeEventListener('mousemove', onDrag);
            document.removeEventListener('mouseup', stopDrag);
        }

        header.addEventListener('mousedown', function(e) {
            // Only drag if not clicking a button inside header
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) return;
            isDragging = true;
            const rect = card.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            document.body.style.userSelect = 'none';
            document.addEventListener('mousemove', onDrag);
            document.addEventListener('mouseup', stopDrag);
        });
    }

    function makeResizable(card) {
        const handle = card.querySelector('.resizable-handle');
        let isResizing = false, startX, startY, startW, startH;

        handle.addEventListener('mousedown', function(e) {
            e.stopPropagation();
            isResizing = true;
            startX = e.clientX;
            startY = e.clientY;
            startW = card.offsetWidth;
            startH = card.offsetHeight;
            document.body.style.userSelect = 'none';
        });

        document.addEventListener('mousemove', onResize);
        document.addEventListener('mouseup', stopResize);

        function onResize(e) {
            if (!isResizing) return;
            let newW = startW + (e.clientX - startX);
            let newH = startH + (e.clientY - startY);
            newW = Math.max(220, newW); // minimum width
            newH = Math.max(70, newH);  // minimum height
            card.style.width = newW + 'px';
            card.style.height = newH + 'px';
        }
        function stopResize() {
            isResizing = false;
            document.body.style.userSelect = '';
        }
    }
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function dropTask(ev) {
        ev.preventDefault();
        const taskId = ev.dataTransfer.getData("text");
        const taskCard = document.getElementById(taskId);

        if (taskCard && taskCard.classList.contains('task-card')) {
            const columnContent = ev.target.closest('.content');
            if (columnContent) {
                // Reset positioning for dropped task
                taskCard.style.position = 'relative';
                taskCard.style.left = 'auto';
                taskCard.style.top = 'auto';
                taskCard.style.width = 'auto';

                // Add to column
                columnContent.appendChild(taskCard);
            }
        }
    }

    function addTaskSlot(btn) {
        const content = btn.previousElementSibling; // Gets the content div
        const slot = document.createElement('div');
        slot.className = 'task-slot';
        slot.ondrop = dropTask;
        slot.ondragover = allowDrop;
        content.appendChild(slot);
    }
    </script>
</body>
</html>
