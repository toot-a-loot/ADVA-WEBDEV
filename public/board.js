class WhiteboardApp {
        constructor() {
            this.boxes = [];
            this.draggingBox = null;
            this.gridPadding = 40;
            this.activeEventListeners = new Map();
            

            this.supabaseUrl = 'https://euddfnpylvmebcqfqadh.supabase.co';
            this.supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImV1ZGRmbnB5bHZtZWJjcWZxYWRoIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDgxNzcwMjMsImV4cCI6MjA2Mzc1MzAyM30.fe7GCpJOqisBu0V-8kTsy162UWv_jxDn709Pns9kWQo';
            this.supabase = supabase.createClient(this.supabaseUrl, this.supabaseKey);

            this.cacheElements();
            this.initEventListeners();
            
        }

        async saveBoardToSupabase(boardName) {
    try {
        // Serialize the board state
        const boardData = this.serializeBoard();
        
        // Save to Supabase
        const { data, error } = await this.supabase
            .from('boards') // your table name
            .upsert([
                { 
                    name: boardName,
                    data: boardData,
                    updated_at: new Date().toISOString()
                }
            ]);
            
        if (error) throw error;
        
        console.log('Board saved successfully!', data);
        return data;
    } catch (error) {
        console.error('Error saving board:', error);
        return null;
    }
}

async loadBoardFromSupabase(boardName) {
    try {
        const { data, error } = await this.supabase
            .from('boards')
            .select('data')
            .eq('name', boardName)
            .single();
            
        if (error) throw error;
        
        if (data) {
            this.deserializeBoard(data.data);
            console.log('Board loaded successfully!');
        }
        return data;
    } catch (error) {
        console.error('Error loading board:', error);
        return null;
    }
}

serializeBoard() {
    return {
        boxes: this.boxes.map(box => ({
            type: box.type,
            x: box.element.offsetLeft,
            y: box.element.offsetTop,
            width: box.width,
            height: box.height,
            status: box.statusLabel?.getAttribute('data-status') || 'in-progress',
            content: this.getBoxContent(box)
        }))
    };
}

getBoxContent(box) {
    switch(box.type) {
        case 'task':
            return {
                subject: box.subjectInput.value,
                body: box.bodyTextarea.value
            };
        case 'column':
            return {
                title: box.columnTitle.value,
                items: Array.from(box.columnItems.children).map(item => 
                    item.querySelector('input').value)
            };
        case 'image':
            return {
                imageSrc: box.imagePreview.src || ''
            };
        default:
            return {};
    }
}

deserializeBoard(data) {
    // Clear existing boxes
    this.deleteAllBoxes();
    
    // Recreate boxes from saved data
    data.boxes.forEach(boxData => {
        const box = this.createNewBox(boxData.status || 'in-progress', boxData.type);
        
        // Set position and size
        box.element.style.left = `${boxData.x}px`;
        box.element.style.top = `${boxData.y}px`;
        box.element.style.width = `${boxData.width}px`;
        box.element.style.height = `${boxData.height}px`;
        box.width = boxData.width;
        box.height = boxData.height;
        
        // Set content based on type
        switch(box.type) {
            case 'task':
                box.subjectInput.value = boxData.content.subject || '';
                box.bodyTextarea.value = boxData.content.body || '';
                break;
            case 'column':
                box.columnTitle.value = boxData.content.title || 'Column Title';
                box.columnItems.innerHTML = '';
                (boxData.content.items || []).forEach(itemText => {
                    const addItem = box.element.querySelector('.box-add-item-btn');
                    addItem.click(); // Add new item
                    const items = box.columnItems.children;
                    if (items.length > 0) {
                        items[items.length - 1].querySelector('input').value = itemText;
                    }
                });
                break;
            case 'image':
                if (boxData.content.imageSrc) {
                    box.imagePreview.src = boxData.content.imageSrc;
                    box.imagePreview.style.display = '';
                    box.element.querySelector('.box-image-remove-btn').style.display = '';
                    box.imageDropzone.style.display = 'none';
                }
                break;
        }
    });
}

        cacheElements() {
            this.elements = {
                boardGrid: document.getElementById('boardGrid'),
                boardFooter: document.getElementById('boardFooter'),
                deleteAllBtn: document.getElementById('deleteAllBtn'),
                addBoxBtn: document.getElementById('addBoxBtn'),
                settingsBtn: document.getElementById('settingsBtn'),
                settingsModal: document.getElementById('settingsModal'),
                addPanelModal: document.getElementById('addPanelModal'),
                dragDeleteZone: document.getElementById('dragDeleteZone')
            };
        }

        initEventListeners() {

            this.addManagedListener(document.getElementById('saveBoardBtn'), 'click', () => {
        const boardName = prompt('Enter a name for this board:');
        if (boardName) {
            this.saveBoardToSupabase(boardName).then(() => {
                alert('Board saved successfully!');
             }).catch(error => {
                console.error('Error saving board:', error);
                alert('Failed to save board. Please try again.');
             });
                    }
            });
            // Delete all button
            this.addManagedListener(this.elements.deleteAllBtn, 'click', () => this.deleteAllBoxes());
            
            // Settings button and modal
            this.addManagedListener(this.elements.settingsBtn, 'click', () => {
                this.elements.settingsModal.style.display = 'flex';
            });
            
            this.addManagedListener(this.elements.settingsModal, 'click', (e) => {
                if (e.target === this.elements.settingsModal) {
                    this.elements.settingsModal.style.display = 'none';
                }
            });

            // Add box button and modal
            this.addManagedListener(this.elements.addBoxBtn, 'click', () => {
                this.showAddPanelModal();
            });

            this.addManagedListener(this.elements.addPanelModal, 'click', (e) => {
                if (e.target === this.elements.addPanelModal) {
                    this.hideAddPanelModal();
                }
            });

            // Add panel modal buttons
            const modalButtons = this.elements.addPanelModal.querySelectorAll('.modal-action-btn');
            modalButtons.forEach(btn => {
                this.addManagedListener(btn, 'click', () => {
                    const type = btn.getAttribute('data-type');
                    this.handleAddPanelSelection(type);
                    this.hideAddPanelModal();
                });
            });
        }

        showAddPanelModal() {
            this.elements.addPanelModal.style.display = 'flex';
            this.elements.boardFooter.style.opacity = '0';
            this.elements.boardFooter.style.visibility = 'hidden';
        }

        hideAddPanelModal() {
            this.elements.addPanelModal.style.display = 'none';
            this.elements.boardFooter.style.opacity = '1';
            this.elements.boardFooter.style.visibility = 'visible';
        }

        handleAddPanelSelection(type) {

            if (type === 'column') {
                this.createNewBox('in-progress', 'column');
            } else if (type === 'image') {
                this.createNewBox('in-progress', 'image');
            } else {
                this.createNewBox('in-progress', 'task');
            }
            this.hideAddPanelModal();
        }

        addManagedListener(element, event, handler) {
            element.addEventListener(event, handler);
            const key = `${event}-${Math.random()}`;
            this.activeEventListeners.set(key, { element, event, handler });
        }

        removeEventListeners() {
            this.activeEventListeners.forEach(({ element, event, handler }) => {
                element.removeEventListener(event, handler);
            });
            this.activeEventListeners.clear();
        }

       updateDeleteAllButton() {
    const hasBoxes = this.boxes.length > 0;
    if (hasBoxes) {
        this.elements.deleteAllBtn.classList.add('visible');
        this.elements.deleteAllBtn.style.opacity = '1';
        this.elements.deleteAllBtn.style.visibility = 'visible';
    } else {
        this.elements.deleteAllBtn.classList.remove('visible');
        this.elements.deleteAllBtn.style.opacity = '0';
        this.elements.deleteAllBtn.style.visibility = 'hidden';
    }
}

        deleteAllBoxes() {
            this.boxes.forEach(box => box.destroy());
            this.boxes = [];
            this.updateDeleteAllButton();
        }

        isPositionValid(x, y, currentBox = null) {
            return !this.boxes.some(box => {
                if (box === currentBox) return false;
                
                const boxRect = box.element.getBoundingClientRect();
                const gridRect = this.elements.boardGrid.getBoundingClientRect();
                const boxLeft = boxRect.left - gridRect.left;
                const boxTop = boxRect.top - gridRect.top;
                
                return x < boxLeft + boxRect.width + this.gridPadding && 
                       x + (currentBox?.width || 0) > boxLeft - this.gridPadding &&
                       y < boxTop + boxRect.height + this.gridPadding && 
                       y + (currentBox?.height || 0) > boxTop - this.gridPadding;
            });
        }

        findValidPosition(boxWidth, boxHeight) {
            const gridWidth = this.elements.boardGrid.clientWidth;
            const gridHeight = this.elements.boardGrid.clientHeight;
            
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

        createNewBox(status = "in-progress", type = "task") {
            const box = new DraggableBox(this, status, type);
            this.boxes.push(box);
            this.updateDeleteAllButton();
            return box;
        }

        showDeleteZone() {
            this.elements.dragDeleteZone.classList.add('active');
        }

        hideDeleteZone() {
            this.elements.dragDeleteZone.classList.remove('active');
        }

        isOverDeleteZone(element) {
            const zoneRect = this.elements.dragDeleteZone.getBoundingClientRect();
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
        constructor(app, status, type = "task") {
            this.app = app;
            this.type = type;
            this.width = window.innerWidth <= 768 ? 320 : 300;
            this.height = window.innerWidth <= 768 ? 240 : 200;
            this.isDragging = false;
            this.isResizing = false;
            this.eventListeners = new Map();

            if (type === 'column') {
                this.initColumnBox();
            } else if (type === 'image') {
                this.initImageBox();
            } else {
                this.initTaskBox(status);
            }
            this.setupPosition();
            this.setupEventListeners();
        }

        // --- COLUMN BOX DESIGN ---
        initColumnBox() {
            this.element = document.createElement('div');
            this.element.className = 'draggable-box column-box';
            this.element.style.width = `${this.width}px`;
            this.element.style.height = `${this.height}px`;

            // Title
            this.columnTitle = document.createElement('input');
            this.columnTitle.className = 'box-column-title';
            this.columnTitle.type = 'text';
            this.columnTitle.value = 'Column Title';
            this.element.appendChild(this.columnTitle);

            // Items container
            this.columnItems = document.createElement('div');
            this.columnItems.className = 'box-column-items';
            this.element.appendChild(this.columnItems);

            // Add item button
            const addItemBtn = document.createElement('button');
            addItemBtn.className = 'box-add-item-btn';
            addItemBtn.textContent = '+ Add Item';
            this.element.appendChild(addItemBtn);

            // Add item logic
            const addItem = (text = 'New Item') => {
                const item = document.createElement('div');
                item.className = 'box-column-item';

                const input = document.createElement('input');
                input.type = 'text';
                input.value = text;
                input.className = 'box-column-item-input';
                item.appendChild(input);

                const removeBtn = document.createElement('button');
                removeBtn.className = 'box-column-item-remove';
                removeBtn.textContent = '❌';
                removeBtn.onclick = () => item.remove();
                item.appendChild(removeBtn);

                this.columnItems.appendChild(item);
            };

            addItemBtn.onclick = () => addItem();
            addItem(); // Add a default item

            // Resize handles
            ['nw', 'ne', 'sw', 'se'].forEach(dir => {
                const handle = document.createElement('div');
                handle.className = `resize-handle resize-${dir}`;
                this.element.appendChild(handle);
                this.setupResizeHandle(handle, dir);
            });

            // Make the column box draggable
            this.element.addEventListener('mousedown', (e) => this.startDrag(e));
            this.element.addEventListener('touchstart', (e) => this.startDrag(e), { passive: false });
        }

        // --- IMAGE BOX DESIGN ---
        initImageBox() {
            this.element = document.createElement('div');
            this.element.className = 'draggable-box image-box';
            this.element.style.width = `${this.width}px`;
            this.element.style.height = `${this.height}px`;

            // Dropzone
            this.imageDropzone = document.createElement('div');
            this.imageDropzone.className = 'box-image-dropzone';
            this.imageDropzone.textContent = 'Drop, paste, or upload an image';
            this.element.appendChild(this.imageDropzone);

            // File input
            this.fileInput = document.createElement('input');
            this.fileInput.type = 'file';
            this.fileInput.accept = 'image/*';
            this.fileInput.style.display = 'none';
            this.element.appendChild(this.fileInput);

            // Upload button
            const uploadBtn = document.createElement('button');
            uploadBtn.className = 'box-upload-btn';
            uploadBtn.textContent = 'Upload Image';
            this.element.appendChild(uploadBtn);

            // Image preview
            this.imagePreview = document.createElement('img');
            this.imagePreview.className = 'box-image-preview';
            this.imagePreview.style.display = 'none';
            this.element.appendChild(this.imagePreview);

            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.className = 'box-image-remove-btn';
            removeBtn.textContent = '🗑️';
            removeBtn.style.display = 'none';
            this.element.appendChild(removeBtn);

            // Upload logic
            uploadBtn.onclick = () => this.fileInput.click();
            this.fileInput.onchange = (e) => {
                const file = e.target.files[0];
                if (file) showImage(file);
            };

            // Drag & drop logic
            this.imageDropzone.ondragover = (e) => { e.preventDefault(); this.imageDropzone.classList.add('dragover'); };
            this.imageDropzone.ondragleave = () => this.imageDropzone.classList.remove('dragover');
            this.imageDropzone.ondrop = (e) => {
                e.preventDefault();
                this.imageDropzone.classList.remove('dragover');
                const file = e.dataTransfer.files[0];
                if (file) showImage(file);
            };

            // Paste logic
            this.imageDropzone.onpaste = (e) => {
                const items = e.clipboardData.items;
                for (let i = 0; i < items.length; i++) {
                    if (items[i].type.startsWith('image/')) {
                        const file = items[i].getAsFile();
                        showImage(file);
                        e.preventDefault();
                        break;
                    }
                }
            };

            // Remove logic
            removeBtn.onclick = () => {
                this.imagePreview.src = '';
                this.imagePreview.style.display = 'none';
                removeBtn.style.display = 'none';
                this.imageDropzone.style.display = '';
            };

            // Helper to show image
            const showImage = (file) => {
                const reader = new FileReader();
                reader.onload = (evt) => {
                    this.imagePreview.src = evt.target.result;
                    this.imagePreview.style.display = '';
                    removeBtn.style.display = '';
                    this.imageDropzone.style.display = 'none';
                };
                reader.readAsDataURL(file);
            };

            // Resize handles
            ['nw', 'ne', 'sw', 'se'].forEach(dir => {
                const handle = document.createElement('div');
                handle.className = `resize-handle resize-${dir}`;
                this.element.appendChild(handle);
                this.setupResizeHandle(handle, dir);
            });

            // Make the image box draggable
            this.element.addEventListener('mousedown', (e) => this.startDrag(e));
            this.element.addEventListener('touchstart', (e) => this.startDrag(e), { passive: false });
        }

        // --- TASK BOX DESIGN ---
        initTaskBox(status) {
            this.element = document.createElement('div');
            this.element.className = 'draggable-box task-box';
            this.element.style.width = `${this.width}px`;
            this.element.style.height = `${this.height}px`;

            // Status label
            this.statusLabel = document.createElement('div');
            this.statusLabel.className = 'box-status-label';
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
                this.addListener(item, 'click', () => this.changeStatus(type));
                this.statusMenu.appendChild(item);
            });
            this.element.appendChild(this.statusMenu);

            // Subject input
            this.subjectInput = document.createElement('input');
            this.subjectInput.className = 'box-subject';
            this.subjectInput.placeholder = 'Task title';
            this.element.appendChild(this.subjectInput);

            // Body wrapper and textarea
            this.bodyWrapper = document.createElement('div');
            this.bodyWrapper.className = 'box-body-wrapper';

            this.bodyTextarea = document.createElement('textarea');
            this.bodyTextarea.className = 'box-body';
            this.bodyTextarea.placeholder = 'Task description...';
            this.bodyWrapper.appendChild(this.bodyTextarea);

            // Guide lines
            for (let i = 0; i < 4; i++) {
                const line = document.createElement('div');
                line.className = 'body-guide-line';
                this.bodyWrapper.appendChild(line);
            }
            this.element.appendChild(this.bodyWrapper);

            // Set initial status
            this.changeStatus(status, false);

            // Resize handles
            ['nw', 'ne', 'sw', 'se'].forEach(dir => {
                const handle = document.createElement('div');
                handle.className = `resize-handle resize-${dir}`;
                this.element.appendChild(handle);
                this.setupResizeHandle(handle, dir);
            });
        }

        addListener(element, event, handler) {
            element.addEventListener(event, handler);
            const key = `${event}-${Math.random()}`;
            this.eventListeners.set(key, { element, event, handler });
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
            this.app.elements.boardGrid.appendChild(this.element);
        }

        setupEventListeners() {
            // Menu toggle
            this.addListener(this.menuBtn, 'click', (e) => {
                e.stopPropagation();
                this.statusMenu.style.display = this.statusMenu.style.display === 'none' ? 'flex' : 'none';
            });

            // Prevent menu from closing when clicking inside
            this.addListener(this.statusMenu, 'click', (e) => e.stopPropagation());

            // Drag events
            this.addListener(this.element, 'mousedown', (e) => this.startDrag(e));
            this.addListener(this.element, 'touchstart', (e) => this.startDrag(e), { passive: false });

            // Global click to close menu
            const clickHandler = (e) => {
                if (!this.element.contains(e.target)) {
                    this.statusMenu.style.display = 'none';
                }
            };
            document.addEventListener('click', clickHandler);
            this.eventListeners.set('document-click', { element: document, event: 'click', handler: clickHandler });
        }

        setupResizeHandle(handle, dir) {
            const startResize = (clientX, clientY) => {
                this.isResizing = true;
                this.startX = clientX;
                this.startY = clientY;
                this.startW = this.element.offsetWidth;
                this.startH = this.element.offsetHeight;
                this.startL = this.element.offsetLeft;
                this.startT = this.element.offsetTop;
                document.body.style.userSelect = 'none';
            };

            const doResize = (clientX, clientY) => {
                if (!this.isResizing) return;
                
                const dx = clientX - this.startX;
                const dy = clientY - this.startY;
                let newW = this.startW, newH = this.startH, newL = this.startL, newT = this.startT;

                if (dir.includes('e')) newW = Math.max(180, this.startW + dx);
                if (dir.includes('s')) newH = Math.max(100, this.startH + dy);
                if (dir.includes('w')) {
                    newW = Math.max(180, this.startW - dx);
                    newL = this.startL + dx;
                }
                if (dir.includes('n')) {
                    newH = Math.max(100, this.startH - dy);
                    newT = this.startT + dy;
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
            this.addListener(handle, 'mousedown', (e) => {
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
            this.addListener(handle, 'touchstart', (e) => {
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
            
            const gridRect = this.app.elements.boardGrid.getBoundingClientRect();
            const clientX = e.clientX || e.touches[0].clientX;
            const clientY = e.clientY || e.touches[0].clientY;
            
            this.offsetX = clientX - gridRect.left - this.element.offsetLeft;
            this.offsetY = clientY - gridRect.top - this.element.offsetTop;
            
            this.element.style.zIndex = '1000';
            this.element.style.cursor = 'grabbing';
            
            if (e.cancelable) e.preventDefault();
            
            const moveHandler = (e) => this.handleDrag(e);
            const endHandler = () => this.endDrag(moveHandler, endHandler);
            
            this.app.elements.boardGrid.addEventListener('mousemove', moveHandler);
            document.addEventListener('mouseup', endHandler);
            this.app.elements.boardGrid.addEventListener('touchmove', moveHandler, {passive: false});
            document.addEventListener('touchend', endHandler);
        }

        shouldIgnoreDrag(e) {
            const target = e.target;
            // Only block drag if the target is an input, textarea, or button inside the box
            return (
                target.tagName === 'INPUT' ||
                target.tagName === 'TEXTAREA' ||
                (target.tagName === 'BUTTON' && this.element.contains(target))
            );
        }

        handleDrag(e) {
            if (!this.isDragging) return;
            
            const gridRect = this.app.elements.boardGrid.getBoundingClientRect();
            const clientX = e.clientX || (e.touches && e.touches[0].clientX);
            const clientY = e.clientY || (e.touches && e.touches[0].clientY);
            
            if (clientX === undefined || clientY === undefined) return;
            
            let x = clientX - gridRect.left - this.offsetX;
            let y = clientY - gridRect.top - this.offsetY;
            
            x = Math.max(0, Math.min(x, this.app.elements.boardGrid.clientWidth - this.width));
            y = Math.max(0, Math.min(y, this.app.elements.boardGrid.clientHeight - this.height));
            
            this.element.style.left = x + 'px';
            this.element.style.top = y + 'px';

            // Highlight delete zone if over it
            this.app.elements.dragDeleteZone.style.background = this.app.isOverDeleteZone(this.element) 
                ? '#ff0000' 
                : 'rgba(255, 90, 90, 0.85)';

            if (e.cancelable) e.preventDefault();
        }

        endDrag() {
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
        }

        removeBox(e) {
            if (e) e.stopPropagation();
            
            const index = this.app.boxes.indexOf(this);
            if (index > -1) {
                this.app.boxes.splice(index, 1);
            }
            
            this.destroy();
            this.app.updateDeleteAllButton();
            this.app.hideDeleteZone();
        }

        destroy() {
            // Remove all event listeners
            this.eventListeners.forEach(({ element, event, handler }) => {
                element.removeEventListener(event, handler);
            });
            this.eventListeners.clear();
            
            // Remove the element from DOM
            if (this.element.parentNode) {
                this.element.parentNode.removeChild(this.element);
            }
        }
    }

    // Initialize the app when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        const app = new WhiteboardApp();

        // Clean up when the page is unloaded
        window.addEventListener('beforeunload', () => {
            app.removeEventListeners();
        });

    //help information alert on popup
    const helpBtn = document.querySelector('#settingsModal .modal-action-btn:nth-child(2)');
    if (helpBtn) {
        helpBtn.addEventListener('click', () => {
            alert(
    `Interactive Whiteboard Help

    • Click the + button to add a new box: Task, Column, or Image.
    • Drag boxes to move them around the board.
    • Drag a box to the red "Drop here to delete" zone to remove it.
    • Click "Delete All" to remove all boxes at once.
    • Task boxes let you enter a title and description.
    • Column boxes let you create a list of items with a custom title.
    • Image boxes let you upload, paste, or drag-and-drop images.
    • Use the ⚙️ button for settings and more options.

    This board is responsive and works on all devices.`
                );
            });
        }
});