<script>
    $(document).ready(function () {
        @if (session('success') && !is_array(session('success')))
            toastr['success'](
                '{{ session("success") }}',
                'Success',
                {
                    closeButton: true,
                    tapToDismiss: false,
                }
            );
        @endif

        @if (session('error') && !is_array(session('error')))
            toastr['error'](
                '{{ session("error") }}',
                'Error',
                {
                    closeButton: true,
                    tapToDismiss: false,
                }
            );
        @endif

        @if (count(@$errors) > 0)
            
            @foreach ($errors->all() as $error)
                toastr['error'](
                    '{{ $error }}',
                    'Whoops!',
                    {
                        closeButton: true,
                        tapToDismiss: false,
                    }
                );
            @endforeach
        @endif

        @if (session('error_api') && !is_array(session('error_api')))
            toastr['error'](
                '{{ session("error_api") }}',
                'Whoops!',
                {
                    closeButton: true,
                    tapToDismiss: false,
                }
            );
        @endif

        @if (!empty(session('errors_api')))
            @php 
                $errors_api = collect(session('errors_api'))->map(function($error){
                    return (array) $error;
                })
            @endphp
            @foreach ($errors_api as $error)
                @foreach ($error as $err)
                toastr['error'](
                    '{{ $err }}',
                    'Whoops!',
                    {
                        closeButton: true,
                        tapToDismiss: false,
                    }
                );
                @endforeach
            @endforeach
        @endif

    });
</script>
