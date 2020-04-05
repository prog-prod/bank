@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    @include('includes.messages')
    <h1>My Credit Cards</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Credit Cards Table</h3>


                    <div class="card-tools">
                        {{$creditCards->links()}}
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body card-body table-responsive p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Card Number</th>
                                <th>Expired Date</th>
                                <th>CVV</th>
                                <th>Amount</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($creditCards as $card)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$card->number}}</td>
                                    <td>{{$card->exp_date->format('m/y')}}</td>
                                    <td>***</td>
                                    <td>{{ $card->account->amount }} UAH</td>
                                    <td>{{$card->created_at->format('d.m.Y H:i:s')}}</td>
                                    <td>
                                        <form action="{{route('credit_cards.destroy',['credit_card' => $card->id])}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-block btn-outline-danger btn-xs"><i class="fas fa-times"></i></button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4">You haven't credit cards.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
