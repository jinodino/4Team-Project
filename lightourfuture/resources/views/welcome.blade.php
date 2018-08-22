<!--welcome.blade.php-->
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Light Our Future</title>
        <!-- Script for polyfilling Promises on IE9 and 10 -->
        {{-- <link href="{{asset('css/font-awesome.css')}}" rel="stylesheet" type="text/css"> --}}
        {{-- <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet" type="text/css"> --}}
        {{-- <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css"> --}}
        <link href="https://cdn.materialdesignicons.com/2.1.19/css/materialdesignicons.min.css" media="all" rel="stylesheet" type="text/css" />
        <style>
            body, html {
                background : #dbdcd4;
                height: 100%;
                width: 100%;
                margin:  0;
                padding: 0;
                font-family: "Noto Sans", sans-serif;
        }
        </style>
    </head>
    
    <body>
        <div id="app"></div>
         
         <script>
           window.Laravel = <?php echo json_encode([
               'csrfToken' => csrf_token(),
                    ]); ?>
          </script>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
