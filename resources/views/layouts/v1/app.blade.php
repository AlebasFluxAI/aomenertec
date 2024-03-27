<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <link rel="stylesheet" href="/css/adminlte.css">
    <script src="https://js.pusher.com/7.0/pusher-with-encryption.min.js"></script>

    <!-- Vendor CSS Files -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/flatpickr.min.css">


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{\App\Http\Resources\V1\Subdomain::getIcon()}}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{\App\Http\Resources\V1\Subdomain::getTitle()}}</title>


    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css"
          rel="stylesheet">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/i18n/defaults-es_ES.min.js"></script>


    <!-- Template Main CSS File -->
    <link href="{{asset(\App\Http\Resources\V1\Style::getStyle())}}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/277123ced7.js" crossorigin="anonymous"></script>


    @livewireStyles
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- DSP library -->
    <script type="text/javascript" src="{{asset('js/library/acwf-core.js')}}"></script>
    <!-- ACWF core analysis library -->
    <script type="text/javascript" src="{{asset('js/library/acwf-canvas.js')}}"></script>
    <!-- ACWF canvas plotting library -->

    <script>
        // Function to validate screen size and send the information to the backend
        function validateScreenSizeAndSendToBackend() {
            // Get the width of the screen
            var screenWidth = window.innerWidth;

            // Determine if the screen is considered a mobile device
            var isMobile = screenWidth < 768; // Adjust the threshold according to your requ
            console.log('{!! \Illuminate\Support\Facades\Request::url() !!}')
            @if(!\Illuminate\Support\Facades\Request::has('isMobile'))
            if (isMobile)
                window.location = '{!! \Illuminate\Support\Facades\Request::url() !!}?isMobile=true';
            @endif
        }

        // Call the function when the document is ready
        validateScreenSizeAndSendToBackend();
    </script>
</head>

<body>
@if(\Illuminate\Support\Facades\Request::has('isMobile') || isset($without_header))
    <div>
        @auth
            @include("layouts.menu.v1.header_menu")
        @endauth

        <section class="top-info">
            @yield('header')
        </section>
        <section class="top-info">
            @livewire('livewire-toast')
            <div class="loader-page">
                <h2 style="margin-top: 150px;"></h2>
            </div>
            <div class="container mt-3">
                <div>
                    @yield('content')
                </div>
            </div>
        </section>


    </div>
    <div>
        @else
            <div>
                @auth
                    @include("layouts.menu.v2.header_menu")
                @endauth

                <section class="top-info">
                    @yield('header')
                </section>
                <section class="top-info">
                    @livewire('livewire-toast')
                    <div class="loader-page">
                        <h2 style="margin-top: 150px;"></h2>
                    </div>
                    <div class="container mt-3">
                        <div>
                        </div>
                    </div>
                </section>


            </div>
            <div>
                @endif

                @include("footer")

            </div>

            <!-- Vendor JS Files -->
            <script>
                $(window).on('load', function () {
                    setTimeout(function () {
                        $(".loader-page").css({visibility: "hidden", opacity: "0"})
                    }, 10);
                });
            </script>
            @livewireScripts
            <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
            <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
            <script type="text/javascript"
                    src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <script
                src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/flatpickr.min.js"></script>


            <script src="{{ asset('js/app.js') }}"></script>
            @include("partials.v1.whatsapp-button")
            <!-- Template Main JS File -->

            <script>

                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl)
                });

                $(function () {
                    $('[data-toggle="popover"]').popover();
                    $('[data-toggle="tooltip"]').tooltip();
                })

            </script>


            <script>
                // Jquery Dependency

                $("input[data-type='currency']").on({
                    keyup: function () {
                        formatCurrency($(this));
                    },
                    blur: function () {
                        formatCurrency($(this), "blur");
                    }
                });


                function formatNumber(n) {
                    // format number 1000000 to 1,234,567
                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                }


                function formatCurrency(input, blur) {
                    // appends $ to value, validates decimal side
                    // and puts cursor back in right position.

                    // get input value
                    var input_val = input.val();

                    // don't validate empty input
                    if (input_val === "") {
                        return;
                    }

                    // original length
                    var original_len = input_val.length;

                    // initial caret position
                    var caret_pos = input.prop("selectionStart");

                    // check for decimal
                    if (input_val.indexOf(".") >= 0) {

                        // get position of first decimal
                        // this prevents multiple decimals from
                        // being entered
                        var decimal_pos = input_val.indexOf(".");

                        // split number by decimal point
                        var left_side = input_val.substring(0, decimal_pos);
                        var right_side = input_val.substring(decimal_pos);

                        // add commas to left side of number
                        left_side = formatNumber(left_side);

                        // validate right side
                        right_side = formatNumber(right_side);

                        // On blur make sure 2 numbers after decimal
                        if (blur === "blur") {
                            right_side += "00";
                        }

                        // Limit decimal to only 2 digits
                        right_side = right_side.substring(0, 2);

                        // join number by .
                        input_val = "$" + left_side + "." + right_side;

                    } else {
                        // no decimal entered
                        // add commas to number
                        // remove all non-digits
                        input_val = formatNumber(input_val);
                        input_val = "$" + input_val;

                        // final formatting
                        if (blur === "blur") {
                            input_val += ".00";
                        }
                    }

                    // send updated string to input
                    input.val(input_val);

                    // put caret back in the right position
                    var updated_len = input_val.length;
                    caret_pos = updated_len - original_len + caret_pos;
                    input[0].setSelectionRange(caret_pos, caret_pos);
                }


            </script>


</body>

</html>
