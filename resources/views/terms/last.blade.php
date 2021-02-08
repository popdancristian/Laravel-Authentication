@extends('terms.layout')

@section('content')

    @if ($term->date)
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Term:</strong>
                {{ $term->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Details:</strong>
                {{ $term->detail }}
            </div>
        </div>
    </div>
    @else
    <div class="form-group alert-danger">
       There is no active term!
    </div>
    @endif
@endsection
