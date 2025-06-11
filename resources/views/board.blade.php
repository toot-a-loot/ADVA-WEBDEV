<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Whiteboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="board-container">
        <div class="board-grid" id="boardGrid"></div>
        <div class="board-footer">
            <button class="board-btn" id="addBoxBtn">
                <span>+</span>
            </button>
            <button class="board-btn" id="settingsBtn">⚙️</button>
        </div>
        <button class="delete-all-btn" id="deleteAllBtn">Delete All</button>
    </div>

    <!-- Settings Modal -->
    <div id="settingsModal" class="modal-overlay">
        <div class="modal-bottom-panel">
            <div class="modal-actions">
                <button class="modal-action-btn">
                    <span class="modal-icon">💾</span>
                    <span class="modal-label">Save</span>
                </button>
                <button class="modal-action-btn">
                    <span class="modal-icon">❓</span>
                    <span class="modal-label">Help</span>
                </button>
            </div>
        </div>
    </div>

    <div id="addPanelModal" class="fixed inset-0 bg-black/10 z-[2000] flex items-end justify-center" style="display:none;">
        <div class="w-screen max-w-[430px] bg-[#c7b6e5] rounded-t-[32px] rounded-b-[32px] px-0 pt-8 pb-6 shadow-[0_-2px_16px_rgba(0,0,0,0.10)] flex flex-col items-center mb-0">
            <div class="flex justify-center gap-8 w-full">
                <button class="flex flex-col items-center bg-white rounded-lg px-6 pt-4 pb-2 shadow-md min-w-[72px] border-none">
                    <img src="{{ asset('images/task-icon.png') }}" alt="Task" class="w-9 h-9 mb-1" />
                    <span class="text-[15px] text-[#333] mt-1">Task</span>
                </button>
                <button class="flex flex-col items-center bg-white rounded-lg px-6 pt-4 pb-2 shadow-md min-w-[72px] border-none">
                    <img src="{{ asset('images/link-icon.png') }}" alt="Link" class="w-9 h-9 mb-1" />
                    <span class="text-[15px] text-[#333] mt-1">Link</span>
                </button>
                <button class="flex flex-col items-center bg-white rounded-lg px-6 pt-4 pb-2 shadow-md min-w-[72px] border-none">
                    <img src="{{ asset('images/column-icon.png') }}" alt="Column" class="w-9 h-9 mb-1" />
                    <span class="text-[15px] text-[#333] mt-1">Column</span>
                </button>
                <button class="flex flex-col items-center bg-white rounded-lg px-6 pt-4 pb-2 shadow-md min-w-[72px] border-none">
                    <img src="{{ asset('images/image-icon.png') }}" alt="Image" class="w-9 h-9 mb-1" />
                    <span class="text-[15px] text-[#333] mt-1">Image</span>
                </button>
            </div>
        </div>
    </div>

    <div id="dragDeleteZone" class="drag-delete-zone">Drop here to delete</div>

    <script src="app.js"></script>
</body>
</html>

<style>
:root {
    --bottom-panel-height: 110px;
    --box-min-width: 180px;
    --box-min-height: 100px;
    --box-initial-width: 300px;
    --box-initial-height: 200px;
    --box-mobile-width: 320px;
    --box-mobile-height: 240px;
    --primary-color: #c7b6e5;
    --danger-color: #ff5a5a;
    --danger-hover: #ff3a3a;
    --text-color: #222;
    --light-gray: #e0e0e0;
    --border-radius: 12px;
}

/* Base Styles */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    background: #f4f4f4;
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    touch-action: none;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Board Layout */
.board-container {
    width: 100vw;
    height: 100vh;
    background: #faf9fa;
    display: flex;
    flex-direction: column;
}

.board-grid {
    flex: 1;
    position: relative;
    background-image: radial-gradient(var(--light-gray) 1px, transparent 1px);
    background-size: 16px 16px;
    overflow: hidden;
    touch-action: none;
}

.board-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--primary-color);
    padding: 32px 24px;
    min-height: 80px;
}

/* Buttons */
.board-btn {
    background: none;
    border: none;
    color: white;
    font-size: 48px;
    cursor: pointer;
    width: 72px;
    height: 72px;
    display: flex;
    align-items: center;
    justify-content: center;
    touch-action: manipulation;
}

.delete-all-btn {
    position: fixed;
    bottom: 150px;
    right: 20px;
    background: var(--danger-color);
    color: white;
    border: none;
    border-radius: 24px;
    padding: 12px 24px;
    cursor: pointer;
    font-size: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.delete-all-btn.visible {
    opacity: 1;
    transform: translateY(0);
}

.delete-all-btn:hover {
    background: var(--danger-hover);
}

/* Draggable Box */
.draggable-box {
    position: absolute;
    width: var(--box-initial-width);
    height: var(--box-initial-height);
    background: #fff;
    border: 2.5px solid var(--text-color);
    border-radius: var(--border-radius);
    cursor: grab;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: flex-start;
    padding-top: 8px;
    padding-bottom: 36px;
    user-select: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    transition: box-shadow 0.2s;
    overflow: hidden;
    touch-action: none;
}

.draggable-box:active {
    cursor: grabbing;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

/* Box Content Elements */
.box-status-label {
    font-size: 13px;
    color: #888;
    font-weight: 500;
    margin: 6px 0 0 12px;
    position: absolute;
    top: 4px;
    left: 0;
    z-index: 3;
}

.box-menu-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: transparent;
    border: none;
    padding: 4px;
    cursor: pointer;
    z-index: 4;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.box-menu-btn .bar {
    display: block;
    width: 18px;
    height: 3px;
    background: #888;
    border-radius: 2px;
}

.box-status-menu {
    position: absolute;
    top: 36px;
    right: 8px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    z-index: 10;
    min-width: 120px;
    display: none;
    flex-direction: column;
}

.box-status-menu-item {
    padding: 10px 16px;
    font-size: 15px;
    color: #444;
    cursor: pointer;
    text-align: left;
    transition: background 0.15s;
}

.box-status-menu-item:hover {
    background: #f0eaff;
}

.box-subject {
    width: 90%;
    margin: 12px auto 4px;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-bottom: 1.5px solid #bbb;
    outline: none;
    background: transparent;
    padding: 4px 0 4px 4px;
}

.box-body-wrapper {
    position: relative;
    width: 92%;
    margin: 0 auto;
    height: 90px;
}

.box-body {
    width: 100%;
    height: 90px;
    resize: none;
    border: none;
    outline: none;
    background: transparent;
    font-size: 15px;
    line-height: 22px;
    padding: 0 0 0 4px;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
}

.body-guide-line {
    position: relative;
    width: 100%;
    height: 22px;
    border-bottom: 1px dashed #d0d0d0;
    z-index: 1;
    margin-top: -2px;
}

.delete-btn {
    position: absolute;
    bottom: 0;
    width: 100%;
    background: var(--danger-color);
    color: white;
    border: none;
    padding: 8px;
    cursor: pointer;
    font-size: 14px;
    transform: translateY(100%);
    transition: transform 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 28px;
}

.draggable-box:hover .delete-btn,
.draggable-box:active .delete-btn {
    transform: translateY(0);
}

.delete-btn:hover {
    background: var(--danger-hover);
}

/* Resize Handles */
.resize-handle {
    position: absolute;
    width: 18px;
    height: 18px;
    background: #fff;
    border: 2px solid #888;
    border-radius: 50%;
    z-index: 10;
    touch-action: none;
}

.resize-nw { top: -9px; left: -9px; cursor: nwse-resize; }
.resize-ne { top: -9px; right: -9px; cursor: nesw-resize; }
.resize-sw { bottom: -9px; left: -9px; cursor: nesw-resize; }
.resize-se { bottom: -9px; right: -9px; cursor: nwse-resize; }

/* Modal Styles */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.08);
    z-index: 2000;
    display: none;
    align-items: flex-end;
    justify-content: center;
}

.modal-bottom-panel {
    width: 100vw;
    max-width: 430px;
    background: var(--primary-color);
    border-radius: 32px 32px 0 0;
    padding: 32px 0 24px;
    box-shadow: 0 -2px 16px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.modal-actions {
    display: flex;
    justify-content: center;
    gap: 40px;
    width: 100%;
}

.modal-action-btn {
    background: #fff;
    border: none;
    border-radius: 8px;
    padding: 16px 24px 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    min-width: 80px;
    transition: background 0.2s;
}

.modal-action-btn:hover {
    background: #f0eaff;
}

.modal-icon {
    font-size: 32px;
    margin-bottom: 4px;
}

.modal-label {
    font-size: 16px;
    color: #333;
}

/* Drag Delete Zone */
.drag-delete-zone {
    position: fixed;
    left: 0;
    right: 0;
    bottom: var(--bottom-panel-height);
    height: 60px;
    background: rgba(255, 90, 90, 0.85);
    color: #fff;
    font-size: 22px;
    font-weight: bold;
    text-align: center;
    line-height: 60px;
    border-radius: 24px 24px 0 0;
    opacity: 0;
    pointer-events: none;
    z-index: 3000;
    transition: opacity 0.2s, background 0.2s;
}

.drag-delete-zone.active {
    opacity: 1;
    background: var(--danger-hover);
}

/* Mobile Styles */
@media (max-width: 768px) {
    .draggable-box {
        width: var(--box-mobile-width);
        height: var(--box-mobile-height);
    }
    
    .board-footer {
        padding: 16px 12px;
        min-height: 60px;
    }
    
    .board-btn {
        font-size: 28px;
        width: 56px;
        height: 56px;
    }
    
    .delete-all-btn {
        bottom: 100px;
        right: 15px;
        padding: 12px 24px;
    }
    
    .box-status-label {
        font-size: 22px;
        margin: 16px 0 0 20px;
    }
    
    .box-menu-btn {
        width: 44px;
        height: 44px;
        top: 16px;
        right: 16px;
    }
    
    .box-menu-btn .bar {
        width: 32px;
        height: 5px;
    }
    
    .box-subject {
        font-size: 28px;
        padding: 12px 0 12px 12px;
        margin: 28px auto 12px;
    }
    
    .box-body-wrapper {
        height: 170px;
    }
    
    .box-body {
        font-size: 22px;
        height: 170px;
        line-height: 36px;
    }
    
    .body-guide-line {
        height: 36px;
    }
    
    .delete-btn {
        font-size: 22px;
        height: 48px;
        padding: 16px;
    }
    
    .box-status-menu {
        min-width: 180px;
    }
    
    .box-status-menu-item {
        padding: 20px 28px;
        font-size: 22px;
    }
    
    .modal-bottom-panel {
        max-width: 100vw;
    }
}
</style>

<script>
class WhiteboardApp {
    constructor() {
        this.boxes = [];
        this.draggingBox = null;
        this.gridPadding = 40;
        this.initElements();
        this.initEventListeners();
    }

    initElements() {
        this.boardGrid = document.getElementById('boardGrid');
        this.addBoxBtn = document.getElementById('addBoxBtn');
        this.deleteAllBtn = document.getElementById('deleteAllBtn');
        this.settingsBtn = document.getElementById('settingsBtn');
        this.settingsModal = document.getElementById('settingsModal');
        this.dragDeleteZone = document.getElementById('dragDeleteZone');
    }

    initEventListeners() {
        // Commented out: this.addBoxBtn.addEventListener('click', () => this.createNewBox());
        this.deleteAllBtn.addEventListener('click', () => this.deleteAllBoxes());
        this.settingsBtn.addEventListener('click', () => this.settingsModal.style.display = 'flex');
        this.settingsModal.addEventListener('click', (e) => {
            if (e.target === this.settingsModal) {
                this.settingsModal.style.display = 'none';
            }
        });
    }

    updateDeleteAllButton() {
        this.deleteAllBtn.classList.toggle('visible', this.boxes.length > 0);
    }

    deleteAllBoxes() {
        this.boxes.forEach(box => box.element.remove());
        this.boxes = [];
        this.updateDeleteAllButton();
    }

    isPositionValid(x, y, currentBox = null) {
        return !this.boxes.some(box => {
            if (box.element === currentBox?.element) return false;
            
            const boxRect = box.element.getBoundingClientRect();
            const gridRect = this.boardGrid.getBoundingClientRect();
            const boxLeft = boxRect.left - gridRect.left;
            const boxTop = boxRect.top - gridRect.top;
            
            return x < boxLeft + boxRect.width + this.gridPadding && 
                   x + currentBox?.width > boxLeft - this.gridPadding &&
                   y < boxTop + boxRect.height + this.gridPadding && 
                   y + currentBox?.height > boxTop - this.gridPadding;
        });
    }

    findValidPosition(boxWidth, boxHeight) {
        const gridWidth = this.boardGrid.clientWidth;
        const gridHeight = this.boardGrid.clientHeight;
        
        // Try random positions first
        for (let i = 0; i < 50; i++) {
            const x = Math.max(0, Math.random() * (gridWidth - boxWidth));
            const y = Math.max(0, Math.random() * (gridHeight - boxHeight));
            if (this.isPositionValid(x, y)) {
                return { x, y };
            }
        }
        
        // Grid search fallback
        for (let y = this.gridPadding; y < gridHeight - boxHeight; y += boxHeight / 2) {
            for (let x = this.gridPadding; x < gridWidth - boxWidth; x += boxWidth / 2) {
                if (this.isPositionValid(x, y)) {
                    return { x, y };
                }
            }
        }
        
        return { x: this.gridPadding, y: this.gridPadding };
    }

    createNewBox(status = "in-progress") {
        const box = new DraggableBox(this, status);
        this.boxes.push(box);
        this.updateDeleteAllButton();
        return box;
    }

    showDeleteZone() {
        this.dragDeleteZone.classList.add('active');
    }

    hideDeleteZone() {
        this.dragDeleteZone.classList.remove('active');
    }

    isOverDeleteZone(element) {
        const zoneRect = this.dragDeleteZone.getBoundingClientRect();
        const boxRect = element.getBoundingClientRect();
        return (
            boxRect.bottom > zoneRect.top &&
            boxRect.top < zoneRect.bottom &&
            boxRect.right > zoneRect.left &&
            boxRect.left < zoneRect.right
        );
    }
}

class DraggableBox {
    constructor(app, status) {
        this.app = app;
        this.element = document.createElement('div');
        this.element.className = 'draggable-box';
        this.width = window.innerWidth <= 768 ? 320 : 300;
        this.height = window.innerWidth <= 768 ? 240 : 200;
        this.isDragging = false;
        this.isResizing = false;
        
        this.initElements(status);
        this.setupPosition();
        this.setupEventListeners();
    }

    initElements(status) {
        // Status label
        this.statusLabel = document.createElement('div');
        this.statusLabel.className = 'box-status-label';
        this.statusLabel.textContent = this.formatStatusText(status);
        this.element.appendChild(this.statusLabel);

        // Menu button
        this.menuBtn = document.createElement('button');
        this.menuBtn.className = 'box-menu-btn';
        this.menuBtn.innerHTML = '<span class="bar"></span><span class="bar"></span><span class="bar"></span>';
        this.element.appendChild(this.menuBtn);

        // Status menu
        this.statusMenu = document.createElement('div');
        this.statusMenu.className = 'box-status-menu';
        ['urgent', 'completed', 'in-progress'].forEach(type => {
            const item = document.createElement('div');
            item.className = 'box-status-menu-item';
            item.textContent = this.formatStatusText(type);
            item.addEventListener('click', () => this.changeStatus(type));
            this.statusMenu.appendChild(item);
        });
        this.element.appendChild(this.statusMenu);

        // Subject input
        this.subjectInput = document.createElement('input');
        this.subjectInput.className = 'box-subject';
        this.subjectInput.placeholder = 'Subject';
        this.element.appendChild(this.subjectInput);

        // Body textarea with guide lines
        this.bodyWrapper = document.createElement('div');
        this.bodyWrapper.className = 'box-body-wrapper';
        
        this.bodyTextarea = document.createElement('textarea');
        this.bodyTextarea.className = 'box-body';
        this.bodyTextarea.placeholder = 'Body...';
        this.bodyWrapper.appendChild(this.bodyTextarea);
        
        for (let i = 0; i < 4; i++) {
            const line = document.createElement('div');
            line.className = 'body-guide-line';
            this.bodyWrapper.appendChild(line);
        }
        this.element.appendChild(this.bodyWrapper);

        // Delete button
        this.deleteBtn = document.createElement('button');
        this.deleteBtn.className = 'delete-btn';
        this.deleteBtn.textContent = 'Delete';
        this.deleteBtn.addEventListener('click', (e) => this.removeBox(e));
        this.element.appendChild(this.deleteBtn);

        // Resize handles
        ['nw', 'ne', 'sw', 'se'].forEach(dir => {
            const handle = document.createElement('div');
            handle.className = `resize-handle resize-${dir}`;
            this.element.appendChild(handle);
            this.setupResizeHandle(handle, dir);
        });

        // Set initial status
        this.changeStatus(status, false);
    }

    formatStatusText(status) {
        return status.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    changeStatus(type, updateMenu = true) {
        this.statusLabel.textContent = this.formatStatusText(type);
        this.statusLabel.setAttribute('data-status', type);
        this.element.setAttribute('data-status', type);
        if (updateMenu) this.statusMenu.style.display = 'none';
    }

    setupPosition() {
        const position = this.app.findValidPosition(this.width, this.height);
        this.element.style.left = `${position.x}px`;
        this.element.style.top = `${position.y}px`;
        this.app.boardGrid.appendChild(this.element);
    }

    setupEventListeners() {
        // Menu toggle
        this.menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.statusMenu.style.display = this.statusMenu.style.display === 'none' ? 'block' : 'none';
        });

        // Prevent menu from closing when clicking inside
        this.statusMenu.addEventListener('click', (e) => e.stopPropagation());

        // Drag events
        this.element.addEventListener('mousedown', (e) => this.startDrag(e));
        this.element.addEventListener('touchstart', (e) => this.startDrag(e), { passive: false });

        // Global click to close menu
        document.addEventListener('click', (e) => {
            if (!this.element.contains(e.target)) {
                this.statusMenu.style.display = 'none';
            }
        });
    }

    setupResizeHandle(handle, dir) {
        let startX, startY, startW, startH, startL, startT;

        const startResize = (clientX, clientY) => {
            this.isResizing = true;
            startX = clientX;
            startY = clientY;
            startW = this.element.offsetWidth;
            startH = this.element.offsetHeight;
            startL = this.element.offsetLeft;
            startT = this.element.offsetTop;
            document.body.style.userSelect = 'none';
        };

        const doResize = (clientX, clientY) => {
            if (!this.isResizing) return;
            
            let dx = clientX - startX;
            let dy = clientY - startY;
            let newW = startW, newH = startH, newL = startL, newT = startT;

            if (dir.includes('e')) newW = Math.max(180, startW + dx);
            if (dir.includes('s')) newH = Math.max(100, startH + dy);
            if (dir.includes('w')) {
                newW = Math.max(180, startW - dx);
                newL = startL + dx;
            }
            if (dir.includes('n')) {
                newH = Math.max(100, startH - dy);
                newT = startT + dy;
            }

            this.element.style.width = newW + 'px';
            this.element.style.height = newH + 'px';
            this.element.style.left = newL + 'px';
            this.element.style.top = newT + 'px';
            this.width = newW;
            this.height = newH;
        };

        const stopResize = () => {
            this.isResizing = false;
            document.body.style.userSelect = '';
        };

        // Mouse events
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            startResize(e.clientX, e.clientY);
            
            const moveHandler = (e) => doResize(e.clientX, e.clientY);
            const upHandler = () => {
                document.removeEventListener('mousemove', moveHandler);
                document.removeEventListener('mouseup', upHandler);
                stopResize();
            };
            
            document.addEventListener('mousemove', moveHandler);
            document.addEventListener('mouseup', upHandler);
        });

        // Touch events
        handle.addEventListener('touchstart', (e) => {
            e.stopPropagation();
            const touch = e.touches[0];
            startResize(touch.clientX, touch.clientY);
            
            const moveHandler = (e) => doResize(e.touches[0].clientX, e.touches[0].clientY);
            const endHandler = () => {
                document.removeEventListener('touchmove', moveHandler);
                document.removeEventListener('touchend', endHandler);
                stopResize();
            };
            
            document.addEventListener('touchmove', moveHandler, {passive: false});
            document.addEventListener('touchend', endHandler);
        }, {passive: false});
    }

    startDrag(e) {
        if (this.shouldIgnoreDrag(e)) return;
        
        this.isDragging = true;
        this.app.draggingBox = this;
        this.app.showDeleteZone();
        
        const gridRect = this.app.boardGrid.getBoundingClientRect();
        const clientX = e.clientX || e.touches[0].clientX;
        const clientY = e.clientY || e.touches[0].clientY;
        
        this.offsetX = clientX - gridRect.left - this.element.offsetLeft;
        this.offsetY = clientY - gridRect.top - this.element.offsetTop;
        
        this.element.style.zIndex = 1000;
        this.element.style.cursor = 'grabbing';
        
        if (e.cancelable) e.preventDefault();
        
        const moveHandler = (e) => this.handleDrag(e);
        const endHandler = () => this.endDrag(moveHandler, endHandler);
        
        this.app.boardGrid.addEventListener('mousemove', moveHandler);
        document.addEventListener('mouseup', endHandler);
        
        this.app.boardGrid.addEventListener('touchmove', moveHandler, {passive: false});
        document.addEventListener('touchend', endHandler);
    }

    shouldIgnoreDrag(e) {
        const target = e.target;
        return (
            target === this.deleteBtn ||
            target === this.menuBtn ||
            this.statusMenu.contains(target) ||
            target === this.subjectInput ||
            target === this.bodyTextarea ||
            this.subjectInput === document.activeElement ||
            this.bodyTextarea === document.activeElement
        );
    }

    handleDrag(e) {
        if (!this.isDragging) return;
        
        const gridRect = this.app.boardGrid.getBoundingClientRect();
        const clientX = e.clientX || (e.touches && e.touches[0].clientX);
        const clientY = e.clientY || (e.touches && e.touches[0].clientY);
        
        if (clientX === undefined || clientY === undefined) return;
        
        let x = clientX - gridRect.left - this.offsetX;
        let y = clientY - gridRect.top - this.offsetY;
        
        x = Math.max(0, Math.min(x, this.app.boardGrid.clientWidth - this.width));
        y = Math.max(0, Math.min(y, this.app.boardGrid.clientHeight - this.height));
        
        this.element.style.left = x + 'px';
        this.element.style.top = y + 'px';

        // Highlight delete zone if over it
        this.app.dragDeleteZone.style.background = this.app.isOverDeleteZone(this.element) 
            ? '#ff0000' 
            : 'rgba(255, 90, 90, 0.85)';

        if (e.cancelable) e.preventDefault();
    }

    endDrag(moveHandler, endHandler) {
        if (this.isDragging) {
            if (this.app.isOverDeleteZone(this.element)) {
                this.removeBox();
            }
            this.app.hideDeleteZone();
            this.app.draggingBox = null;
        }
        
        this.isDragging = false;
        this.element.style.zIndex = '';
        this.element.style.cursor = 'grab';
        
        this.app.boardGrid.removeEventListener('mousemove', moveHandler);
        document.removeEventListener('mouseup', endHandler);
        this.app.boardGrid.removeEventListener('touchmove', moveHandler);
        document.removeEventListener('touchend', endHandler);
    }

    removeBox(e) {
        if (e) e.stopPropagation();
        
        const index = this.app.boxes.indexOf(this);
        if (index > -1) {
            this.app.boxes.splice(index, 1);
        }
        
        this.element.remove();
        this.app.updateDeleteAllButton();
        this.app.hideDeleteZone();
    }
}

// Initialize the app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new WhiteboardApp();

    const addBoxBtn = document.getElementById('addBoxBtn');
    const addPanelModal = document.getElementById('addPanelModal');

    if (addBoxBtn && addPanelModal) {
        addBoxBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            addPanelModal.style.display = 'flex';
        });

        addPanelModal.addEventListener('click', function (e) {
            if (e.target === addPanelModal) {
                addPanelModal.style.display = 'none';
            }
        });
    }
});
</script>