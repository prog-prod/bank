@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @include('includes.messages')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">You are logged in!</div>

                <div class="card-body">


                    <form action="{{route('home.update_avatar')}}" method="post" enctype="multipart/form-data">
                        @csrf
{{--                        @method('PUT')--}}
                        <div class="form-group">
                            <input type="file" class="form-control-file" name="avatar" id="avatarFile">
                            <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
