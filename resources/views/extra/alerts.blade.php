@foreach ($errors->all() as $error)
    <script>
        var msg = "{{ $error }}";

        $.notify({
            icon: "error",
            message: msg,
        },{
            type: 'danger'
        });
    </script>
@endforeach

@if(Session::has('error'))
    <script>
        var msg = "{{ Session::get('error') }}";

        $.notify({
            icon: "error",
            message: msg,
        },{
            type: 'danger'
        });
    </script>
@endif

@if(Session::has('store_error'))
    <script>
        var msg = "{{ Session::get('store_error') }}";

        $.notify({
            icon: "error",
            message: msg,
        },{
            type: 'danger'
        });
    </script>
@endif

@if(Session::has('success'))
    <script>
        var msg = "{{ Session::get('success') }}";

        $.notify({
            icon: "check_circle",
            message: msg,
        },{
            type: 'success'
        });
    </script>
@endif