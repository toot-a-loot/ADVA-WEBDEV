<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Whiteboard</title>
    <link rel="stylesheet" href="{{ asset('css/board.css') }}">
</head>
<body>
    <div class="board-container">
        <div class="board-grid" id="boardGrid"></div>
        <div class="board-footer" id="boardFooter">
            <button class="board-btn" id="addBoxBtn">
                <span>+</span>
            </button>
            <button class="board-btn" id="settingsBtn">⚙️</button>
        </div>
        <button class="delete-all-btn" id="deleteAllBtn">Delete All</button>
    </div>

   <div id="settingsModal" class="modal-overlay">
    <div class="modal-bottom-panel">
        <div class="modal-actions">
            <button class="modal-action-btn" id="saveBoardBtn">
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

    <div id="addPanelModal" class="modal-overlay">
        <div class="modal-bottom-panel">
            <div class="modal-actions">
                <button class="modal-action-btn" data-type="task">
                    <span class="modal-icon">📝</span>
                    <span class="modal-label">Task</span>
                </button>

                <button class="modal-action-btn" data-type="column">
                    <span class="modal-icon">📋</span>
                    <span class="modal-label">Column</span>
                </button>
                <button class="modal-action-btn" data-type="image">
                    <span class="modal-icon">🖼️</span>
                    <span class="modal-label">Image</span>
                </button>
            </div>
        </div>
    </div>

    <div id="dragDeleteZone" class="drag-delete-zone">Drop here to delete</div>
    <script src="{{ asset('board.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
</body>
</html>