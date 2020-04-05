@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @include('includes.messages')
    <h1>All users transactions</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users Table</h3>

                    <div class="card-tools">
                        {{$transactions->links()}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body card-body table-responsive p-0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Card number</th>
                            <th>Receiver Card Number</th>
                            <th>Amount</th>
                            <th>When</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr class="@if(Auth::user()->isMyCard($transaction->card->id)) bg-danger @elseif(Auth::user()->isMyCard($transaction->receiverCard->id)) bg-success @endif">
                                <td>{{$loop->iteration}}</td>
                                <td><b> {{$transaction->card->number}}</b> <br> {{$transaction->card->user->email}}</td>
                                <td><b> {{$transaction->receiverCard->number}}</b> <br> {{$transaction->receiverCard->user->email}}</td>
                                <td>{{$transaction->amount}} UAH</td>
                                <td>{{$transaction->created_at->format('d.m.Y H:i:s')}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
