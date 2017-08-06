<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link href="https://bootswatch.com/slate/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="//connect.facebook.net/en_US/sdk.js"></script>
    <script type="text/javascript" class="removeMe">
        FB.init({
            appId: '{{ env("FACEBOOK_APP_ID") }}', // replace this with your id
            status: true,
            cookie: true,
            version: '{{ env("FACEBOOK_DEFAULT_GRAPH_VERSION") }}'
        });

        // attach login click event handler
        $(document).on('click', "div.service.service-facebook:not(.connected)", function(){
            FB.login(processLoginClick, {scope:'public_profile,email,user_friends,manage_pages,publish_actions', return_scopes: true});  
        });

        // function to send uid and access_token back to server
        // actual permissions granted by user are also included just as an addition
        function processLoginClick (response) {
            console.log(response);
            var data = response.authResponse;
                data._token = '{{ csrf_token() }}';
            postData("{{ route('services.auth.service-facebook') }}", data, "post");
        }

        // function to post any data to server
        function postData(url, data, method) 
        {
            method = method || "post";
            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", url);
            for(var key in data) {
                if(data.hasOwnProperty(key)) 
                {
                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", key);
                    hiddenField.setAttribute("value", data[key]);
                    form.appendChild(hiddenField);
                 }
            }
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    @include('layouts.navbar-left')

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    @include('layouts.navbar-right')
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
    <div id="footer">
        <div class="container">
            <p class="text-muted credit text-center">
                &copy; 2017 <a href="#">VF-Websolutions Ltd.</a> Powered by: <a href="http://laravel.com/" alt="Laravel 5.4">Laravel 5.4</a></p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>
