@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>My Credit Cards</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Credit Cards Table</h3>

                    {{$creditCards->links()}}
{{--                    <div class="card-tools">--}}
{{--                        <ul class="pagination pagination-sm float-right">--}}
{{--                            <li class="page-item"><a class="page-link" href="#">«</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">»</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Card Number</th>
                                <th>Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($creditCards as $card)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$card->number}}</td>
                                    <td>{{$card->date}}</td>
                                    <td>{{$card->created_at}}</td>
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
