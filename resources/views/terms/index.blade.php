@extends('terms.layout')

@section('content')

    <div class="pull-left">
        <a href="{{ route('users.index') }}">Go to User Dashboard</a>
        <h4>Terms</h4>
    </div>
    <div class="pull-right">
        <a class="btn btn-success" href="{{ route('terms.create') }}"> Create New Term</a>
    </div>


    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Content</th>
            <th>Publish Date</th>
            <th width="100px">Action</th>
        </tr>
        @foreach ($terms as $term)
            <tr>
                <td>{{ $term->id }}</td>
                <td>{{ $term->name }}</td>
                <td>{{ $term->detail }}</td>
                <td>{{ $term->date }}</td>
                <td>
                    <form action="{{ route('terms.destroy', $term->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('terms.show', $term->id) }}">Show</a>
                        {{-- @if ($term->date) --}}
                        <a class="btn btn-primary" href="{{ route('terms.edit', $term->id) }}">Edit</a>
                        {{-- @endif --}}
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                        @if ($term->date)
                            <a class="btn btn-warning" href="{{ route('terms.unpublish', $term->id) }}">Un-publish</a>
                        @else
                            <a class="btn btn-warning" href="{{ route('terms.publish', $term->id) }}">Publish</a>
                        @endif

                    </form>
                </td>
            </tr>
        @endforeach

    </table>
    {{ $terms->links() }}

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


@endsection
