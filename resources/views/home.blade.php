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
                <div class="card-header">
                    <h3 class="card-title">History transaction</h3>

                    <div class="card-tools">
                        {{$historyTransactions->links()}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body table-responsive p-0">
                        @forelse($historyTransactions as $history)
                            @php($text = Auth::user()->isMyCard($history->receiverCard->id) ? 'success' : 'danger')
                            <div class="callout callout-{{$text}}">
                                <h5>{{$history->card->number}} <i class="fas fa-arrow-right"></i> {{$history->receiverCard->number}} | <b class="text-{{$text}}"> @if($text == 'success'){{'+'}} @else{{'-'}}@endif {{$history->amount}} UAH</b></h5>

                                <p>When: {{$history->created_at->format('d.m.Y h:i:s')}}</p>
                            </div>
                        @empty
                            <div class="text-left">You hadn't transactions.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
