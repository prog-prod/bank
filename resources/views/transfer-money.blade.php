@extends('adminlte::page')

@section('title', 'Transfer money')

@section('content_header')
    @include('includes.messages')
    <h1>Transfer Money</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <form role="form" action="{{route('transfer_money.store')}}" method="post">
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
                                        <select class="form-control mb-2" name="user_from" id="user_from" required>
                                            <option value="">-</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"> {{$user->email}}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-control card-select mb-2" name="card_from" id="card_from"  required>
                                            <option value="">-</option>
                                        </select>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="exp_date_from" class="form-control expDateInput" id="dateInput" placeholder="Exp Date" maxlength="5" minlength="5" value="{{old('exp_date_from')}}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="cvv_from" class="form-control" id="cvvInput" placeholder="CVV"  maxlength="3" minlength="3" value="{{old('cvv_from')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <!-- select -->
                                    <div class="form-group">
                                        <label>User To</label>
                                        <select class="form-control mb-2" name="user_to" id="user_to" required>
                                            <option value="">-</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"> {{$user->email}}</option>
                                            @endforeach
                                        </select>
                                        <select class="form-control card-select mb-2" name="card_to" id="card_to" required>
                                            <option value="">-</option>
                                        </select>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="exp_date_to" class="form-control expDateInput" id="dateInput" placeholder="Exp Date" maxlength="5" minlength="5" value="{{old('exp_date_to')}}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="cvv_to" class="form-control" id="cvvInput" placeholder="CVV"  maxlength="3" minlength="3" value="{{old('cvv_to')}}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group mb-5">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="text" class="form-control" name="amount" value="{{old('amount')}}" required>
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
@section('js')
    <script>

        $('#user_from, #user_to').change(function () {
            const $this = $(this);
            let text = '<option value="">-</option>';
            if(!this.value)
            {
                return $this.siblings('.card-select').html(text);
            }
            $.get('/transfer-money/get-cards/' + this.value).then( cards => {

                for(let key in cards)
                {
                    text += '<option value=' + key + '>' + cards[key] + '</option>'
                }
                $this.siblings('.card-select').html(text);
            });
        });
    </script>
@endsection
