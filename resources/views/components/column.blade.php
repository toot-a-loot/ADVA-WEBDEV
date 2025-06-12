<div class="column-card">
    <div class="header">
        <h3>Column</h3>
        <button class="delete-column" onclick="this.closest('.column-card').remove()">
            <img src="{{ asset('css/images/delete.png') }}" alt="Delete Column">
        </button>
    </div>
    <div class="content" ondrop="dropTask(event)" ondragover="allowDrop(event)">
        <!-- Tasks will be dropped here -->
    </div>
    <div class="resizable-handle"></div>
</div>
