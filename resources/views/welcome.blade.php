<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Wellington Agriculuture Services</title>
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <!--  Main CSS File -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <link href="css/owl.theme.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

</head>

<body class="home-page">
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "100%";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }

    </script>

    <script>
        $(document).ready(function() {
            $('#service-slide-home').owlCarousel({
                loop: true,
                pagination: false,
                navigation: false, // Show next and prev buttons
                autoplay: true,
                autoplayTimeout: 5000,
                margin: 0,
                nav: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 2
                    }
                }
            })

            $('#testimonial-slide-home').owlCarousel({
                loop: true,
                pagination: false,
                navigation: false, // Show next and prev buttons
                autoplay: true,
                autoplayTimeout: 5000,
                margin: 0,
                nav: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 2
                    }
                }
            })


        });

    </script>

    <script>
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();

            if (scroll >= 200) {
                $(".site-header").addClass("stickyHeader");
            } else {
                $(".site-header").removeClass("stickyHeader");
            }
        });

    </script>

    <script>
        AOS.init({
            duration: 1200,
        })

    </script>

</body>

</html>
