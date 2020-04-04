<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12" >
            @if ($errors->any())
                <div class="alert alert-danger mt-1 mb-1">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif ($message = session('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
        </div>
    </div>
</div>
