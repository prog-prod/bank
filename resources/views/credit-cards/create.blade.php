@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Create Credit Card</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                {!!   implode('', $errors->all('<div>:message</div>')) !!}
            </div>
        @endif
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{route('credit_cards.store')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="numberInput">Card Number</label>
                            <input type="text" name="number" class="form-control" id="numberInput" maxlength="16" minlength="16" placeholder="Card Number" value="{{old('card_number')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="dateInput">Date</label>
                            <input type="date" name="date" class="form-control" id="dateInput" placeholder="Date" value="{{old('card_date')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="cvvInput">CVV</label>
                            <input type="text" name="cvv" class="form-control" id="cvvInput" placeholder="CVV"  maxlength="3" minlength="3" value="{{old('card_cvv')}}" required>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
