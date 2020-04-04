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
                <div class="card-header">Upload Your Avatar</div>
                <div class="card-body">
                    <form action="{{route('home.update_avatar')}}" method="post" enctype="multipart/form-data">
                        @csrf
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">History transaction</div>
                <div class="card-body">
                    <div class="card-body table-responsive p-0">
{{--                        <table class="table table-hover">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>#</th>--}}
{{--                                <th>Receiver</th>--}}
{{--                                <th>Amount</th>--}}
{{--                                <th>When</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            <tr >--}}
{{--                                <td>183</td>--}}
{{--                                <td>John Doe</td>--}}
{{--                                <td>11-7-2014</td>--}}
{{--                                <td><span class="tag tag-success">Approved</span></td>--}}
{{--                                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>--}}
{{--                            </tr>--}}

{{--                        </table>--}}

                        @foreach($historyTransactions as $history)
                            @php($text = Auth::user()->isMyCard($history->receiverCard->id) ? 'success' : 'danger')
                            <div class="callout callout-{{$text}}">
                                <h5>{{$history->card->number}} <i class="fas fa-arrow-right"></i> {{$history->receiverCard->number}} | <b class="text-{{$text}}"> @if($text == 'success'){{'+'}} @else{{'-'}}@endif {{$history->amount}} UAH</b></h5>

                                <p>When: {{$history->created_at->format('d.m.Y h:i:s')}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
