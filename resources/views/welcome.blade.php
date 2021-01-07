<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <base href="/">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Wellington Agricultural Services</title>

    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />

    <!--  Main CSS File -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <!--<link href="css/owl.theme.css" rel="stylesheet">-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCymw6I3DWcllnp9aCBz3HYTWqtj916DFA&libraries=geometry,places"></script>
</head>

<body class="home-page">
    <div id="app">
        <app></app>
    </div>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/owl.carousel.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src='https://npmcdn.com/@turf/turf/turf.min.js'></script>
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "100%";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
        $(document).ready(function() {
            feather.replace();
            setTimeout(function() {
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
            }, 200);
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

        setTimeout(function() {
            $(document).ready(function() {
                if (!$.fn.dataTable.isDataTable(".basic-table")) {
                    $(".basic-table").DataTable({
                        "bSort": false,
                        oLanguage: {
                            sSearch: "",
                            "sEmptyTable": "No data available."
                        },
                        drawCallback: function(settings) {
                            $(".dataTables_paginate .paginate_button.previous").html(
                                $("#table-chevron-left").html()
                            );
                            $(".dataTables_paginate .paginate_button.next").html(
                                $("#table-chevron-right").html()
                            );
                        },
                    });
                    $(".dataTables_filter").append($("#search-input-icon").html());
                    $(".dataTables_filter input").attr(
                        "placeholder",
                        "Search Pickup by Pickup ID / Service Name / Farm Location"
                    );
                    $(".dataTables_paginate .paginate_button.previous").html(
                        $("#table-chevron-left").html()
                    );
                    $(".dataTables_paginate .paginate_button.next").html(
                        $("#table-chevron-right").html()
                    );

                    $("#all-farms-table_filter input").attr(
                        "placeholder",
                        "Search Farms by Farm Location / Manager"
                    );
                    $("#all-drivers-table_filter input").attr(
                        "placeholder",
                        "Search Drivers by Name / Email / Phone / Address"
                    );
                    $("#all-managers-table_filter input").attr(
                        "placeholder",
                        "Search Managers by Name / Email / Phone / Address"
                    );
                }
                $(".basic-table").css({
                    opacity: 1
                });
            });
        }, 1000);
        $(".go-to-link").click(function() {
            var href = $(this).attr(href);
            $('html, body').animate({
                scrollTop: $(href).offset().top
            }, 2000);
        });
        AOS.init({
            duration: 1200,
        });
    </script>

</body>
</html>