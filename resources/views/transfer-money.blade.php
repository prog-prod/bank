@extends('adminlte::page')

@section('title', 'Transfer money')

@section('content_header')
    <h1>Transfer Money</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <form role="form" action="{{route('transfer_money_store')}}" method="post">
                    @csrf
                    <div class="card-header">
                        <h3 class="card-title">Transfer Form</h3>
                        <div class="card-tools"></div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>User From </label>
                                        <select class="form-control">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"> {{$user->email}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>User To</label>
                                        <select class="form-control">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"> {{$user->email}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group mb-5">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control">
                                        <div class="input-group-append">
                                            <span class="input-group-text">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

