<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Accounting System') }}</title> --}}
    <title>@yield('title')</title>

    <script src="{{ asset('js/jquery.min.js')}}"></script>
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- CSS --}}
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
</head>

<body>
    <div class="loader">
        <img src="{{ asset('images/3.gif')}}">
    </div>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            {{-- <div class="sidebar-heading"><img src="images/logo.png" alt="company_logo" width="165px"></div> --}}
            <div class="sidebar-heading text-center">Dental Service</div>
            <div class="list-group list-group-flush">
                <ul>
                    <li class="sidebar_dashboard @yield('dashboard')"><a href="/dashboard"
                            class="list-group-item list-group-item-action"><i class="fas fa-cube icon"></i>Dashboard
                        </a></li>
                    <!-- <li class="sidebar_company @yield('company_info')"><a href="/company_info"
                                class="list-group-item list-group-item-action"><i class="fas fa-building icon"></i>Company
                                Information</a></li> -->
                    <li class="sidebar_chart @yield('manage_accounts')"> <a href="/account"
                            class="list-group-item list-group-item-action"><i class="fas fa-clipboard icon"></i>Chart of
                            Accounts</a>
                    </li>
                    <li class="sidebar_journal @yield('journal_entries')"><a href="/journal"
                            class="list-group-item list-group-item-action"><i class="fas fa-pen-alt icon"></i>Journal
                            Entries</a></li>
                    <li class="sidebar_reports @yield('reports')"> <a href="/reports"
                            class="list-group-item list-group-item-action"><i
                                class="fas fa-chart-area icon"></i>Reports</a></li>
                </ul>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
                <button class="btn btn-custom\" id="menu-toggle">Toggle Menu</button>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item log_out" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"><i
                                        class="fas fa-sign-out-alt mr-1"></i>
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </nav>


            <div class="container">
                {{-- data-autohide="false" --}}
                {{-- toast --}}
                <div aria-live="polite" aria-atomic="true" role="alert" style="position: relative; z-index: 9999;">
                    <div class="toast" style="" role="alert" aria-live="assertive" data-delay="5000">
                    {{-- <div class="toast" role="alert" aria-live="assertive" aria-live="assertive" data-autohide="false"> --}}
                        <div class="toast-body">
                            <i class="mr-1 width="></i><span id="toast_message"></span><span><button type="button"
                                    class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></span>
                        </div>
                    </div>
                </div>

                {{-- <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <img src="..." class="rounded mr-2" alt="...">
                        <strong class="mr-auto">Bootstrap</strong>
                        <small>11 mins ago</small>
                        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="toast-body">
                        Hello, world! This is a toast message.
                    </div>
                </div> --}}

                <h1 class="h3 mb-3 mt-4 hays">@yield('header')</h1>

                @yield('content')

            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    {{-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> --}}

    <!-- Menu Toggle Script -->
    <script>

        $(document).ready(function () {

            $('.loader').hide();

            $('.print').click(function () {
                window.print();
            })
        });

        function toast_message(type, toast_message) {
            if (type == 'success') {
                $('#toast_message').html(toast_message);
                $('.toast').addClass('toast-success-border');
                $('.toast-body i').addClass('fas fa-check-circle text-success');
            }


            $(".toast").toast('show');
        }


        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    @yield('scripts')
</body>

</html>