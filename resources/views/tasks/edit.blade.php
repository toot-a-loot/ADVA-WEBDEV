@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Task</h1>
    <form method="POST" action="{{ route('tasks.update', $task->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Title</label>
            <input type="text" name="title" value="{{ $task->title }}">
        </div>
        <div>
            <label>Description</label>
            <textarea name="description">{{ $task->description }}</textarea>
        </div>
        <button type="submit">Update</button>
    </form>
</div>
@endsection

