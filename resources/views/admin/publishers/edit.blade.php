@extends('admin.components.main')

@section('title', 'Edit Penerbit')
@section('page-title', 'Edit Penerbit: ' . $publisher->name)

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Penerbit</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.publishers.update', $publisher->id) }}" method="POST">
                @method('PUT')
                @include('admin.publishers._form', ['publisher' => $publisher])
            </form>
        </div>
    </div>
@endsection
