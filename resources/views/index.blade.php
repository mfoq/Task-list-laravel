@extends('layouts.app')

@section('title', "All Tasks")

@section('content')

        <nav class="mb-4">
            <a href="{{ route('Khara') }}" class="link">Add Task</a>
        </nav>

        @forelse ( $tasks as $item)
            <div>
                <a href="{{ route("tasks.show", ["task" => $item->id] ) }}" @class(['line-through' => $item->completed])>{{ $item->title }}</a>
            </div>
        @empty
            <p>There are no tasks!</p>
        @endforelse

        @if($tasks->count())
            <nav class="mt-4">
                {{ $tasks->links() }}
            </nav>
        @endif
@endsection
