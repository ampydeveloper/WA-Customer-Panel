<template>
    <div>
        <header class="site-header">
            <div class="container-fluid">
                <div class="logo-area">
                    <a href="#"><img src="img/main-logo.png" alt=""/></a>
                </div>
                <span class="menu-toggle" onclick="openNav()">&#9776;</span>
            </div>
            <div id="mySidenav" class="sidenav">
                <a
                    href="javascript:void(0)"
                    class="closebtn"
                    onclick="closeNav()"
                    >&times;</a
                >
                <a href="#">About</a>
                <a href="#">Services</a>
                <a href="#">Clients</a>
                <a href="#">Contact</a>
            </div>
        </header>

        <div class="banner-section">
            <div class="container">
                <div class="row">
                    <div class="banner-text-inner">
                        <h1>Progress is at your Fingertips</h1>
                        <p>
                            Wellington Agricultural services has been a premium
                            agricultural services provide in the industry for
                            over 30 years.
                        </p>

                        <div class="banner-btn">
                            <a href="#">How it works</a>
                            <a href="#" class="start-btn">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="lets-started-section" data-aos="fade-down">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 md-none">
                        <img src="img/screendisplay-im.png" alt="" />
                    </div>

                    <div class="col-md-6">
                        <div class="let-strt-text">
                            <h2>
                                Let’s <br />
                                Get Started
                            </h2>
                            <ul>
                                <li>
                                    <span class="lets-str-icon"
                                        ><img
                                            src="img/gps-tracker-icon.png"
                                            alt=""
                                    /></span>
                                    <h5>True GPS Driver Tracking</h5>
                                    <p>
                                        We offer our customers peace of mind by
                                        offering easy access to our driver’s
                                        locations.
                                    </p>
                                </li>

                                <li>
                                    <span class="lets-str-icon"
                                        ><img src="img/device-icon.png" alt=""
                                    /></span>
                                    <h5>Use on Any Device</h5>
                                    <p>
                                        Our native iOS and android apps allow
                                        you to request service appointments, get
                                        free estimates view history and manage
                                        contact information from any device.
                                    </p>
                                </li>

                                <li>
                                    <span class="lets-str-icon"
                                        ><img src="img/scissors-icon.png" alt=""
                                    /></span>
                                    <h5>On Demand Modifications</h5>
                                    <p>
                                        Our system offers you a dynamic
                                        experience to modify and adjust our
                                        services with a click of the button.
                                    </p>
                                </li>

                                <li>
                                    <span class="lets-str-icon"
                                        ><img
                                            src="img/media-upload-icon.png"
                                            alt=""
                                    /></span>
                                    <h5>Media Upload</h5>
                                    <p>
                                        Our system allows you and our drivers to
                                        archive visual references for your
                                        service.
                                    </p>
                                </li>
                            </ul>

                            <h4>
                                Download and stay <span>connected</span> with
                                <span>desktop or mobile</span> applications
                            </h4>
                            <div class="download-from-strore">
                                <a href="#" class="btnpblack">Create Account</a>
                                <a href="#"
                                    ><img
                                        src="img/app-stor-icon-gray.png"
                                        alt=""
                                /></a>
                                <a href="#"
                                    ><img
                                        src="img/google-play-icon-gray.png"
                                        alt=""
                                /></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="how-it-work" data-aos="fade-down">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>How We Work</h2>
                    </div>
                    <div class="col-md-12">
                        <ul class="row">
                            <li class="col-md-6">
                                <div class="how-work-text">
                                    <span class="small-icon"
                                        ><img src="img/download-icon.png" alt=""
                                    /></span>
                                    <div class="how-work-text-inner">
                                        <h5>Download</h5>
                                        <p>
                                            We strongly recommend downloading
                                            our mobile app for the best and
                                            easiest user experience.
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <li class="col-md-6">
                                <div class="how-work-text">
                                    <span class="small-icon"
                                        ><img src="img/book-icon.png" alt=""
                                    /></span>
                                    <div class="how-work-text-inner">
                                        <h5>Book</h5>
                                        <p>
                                            Schedule your manure removal pickup
                                            for the most convenient available
                                            time for you
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <li class="col-md-6">
                                <div class="how-work-text">
                                    <span class="small-icon"
                                        ><img
                                            src="img/displtache-icon.png"
                                            alt=""
                                    /></span>
                                    <div class="how-work-text-inner">
                                        <h5>Dispatched</h5>
                                        <p>
                                            Once your load has been confirmed
                                            our trucks will be dispatched to
                                            your location at the appropriate
                                            time.
                                        </p>
                                    </div>
                                </div>
                            </li>

                            <li class="col-md-6">
                                <div class="how-work-text">
                                    <span class="small-icon"
                                        ><img
                                            src="img/manured-removed.png"
                                            alt=""
                                    /></span>
                                    <div class="how-work-text-inner">
                                        <h5>Manure Removed</h5>
                                        <p>
                                            We strongly recommend downloading
                                            our mobile app for the best and
                                            easiest user experience.
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <section class="our-services-home" data-aos="fade-up">
            <div class="container">
                <h2>
                    Our <br />
                    Services
                </h2>

                <div class="services-slider">
                    <div class="owl-carousel owl-theme" id="service-slide-home">
                        <div class="item">
                            <div class="service-block-custom-inner">
                                <h4>20 YARD SERVICE</h4>
                                <p>
                                    This service is most commonly used by our
                                    customers. It includes 20 yard service of
                                    your farm including manure pickup and taking
                                    it to our dumping station.
                                </p>

                                <ul>
                                    <li>Year Round Service</li>
                                    <li>Weekly or Daily Schedule</li>
                                    <li>Scale Back In The Summer</li>
                                    <li>Auto Pick Up</li>
                                    <li>Largest Volume</li>
                                </ul>

                                <a href="#" class="schedule-now"
                                    ><i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Schedule Now</a
                                >
                            </div>
                        </div>

                        <div class="item">
                            <div class="service-block-custom-inner">
                                <h4>STANDARD LOAD</h4>
                                <p>
                                    This service is most commonly used by our
                                    customers. It includes 20 yard service of
                                    your farm including manure pickup and taking
                                    it to our dumping station.
                                </p>

                                <ul>
                                    <li>25 yards</li>
                                    <li>Largest Load Removal</li>
                                    <li>Online Booking</li>
                                    <li>Available On Any Day</li>
                                    <li>Use It When Needed</li>
                                </ul>

                                <a href="#" class="schedule-now"
                                    ><i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Schedule Now</a
                                >
                            </div>
                        </div>
                        <div class="item">
                            <div class="service-block-custom-inner">
                                <h4>20 YARD SERVICE</h4>
                                <p>
                                    This service is most commonly used by our
                                    customers. It includes 20 yard service of
                                    your farm including manure pickup and taking
                                    it to our dumping station.
                                </p>

                                <ul>
                                    <li>Year Round Service</li>
                                    <li>Weekly or Daily Schedule</li>
                                    <li>Scale Back In The Summer</li>
                                    <li>Auto Pick Up</li>
                                    <li>Largest Volume</li>
                                </ul>

                                <a href="#" class="schedule-now"
                                    ><i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Schedule Now</a
                                >
                            </div>
                        </div>

                        <div class="item">
                            <div class="service-block-custom-inner">
                                <h4>STANDARD LOAD</h4>
                                <p>
                                    This service is most commonly used by our
                                    customers. It includes 20 yard service of
                                    your farm including manure pickup and taking
                                    it to our dumping station.
                                </p>

                                <ul>
                                    <li>25 yards</li>
                                    <li>Largest Load Removal</li>
                                    <li>Online Booking</li>
                                    <li>Available On Any Day</li>
                                    <li>Use It When Needed</li>
                                </ul>

                                <a href="#" class="schedule-now"
                                    ><i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i>
                                    Schedule Now</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="testimonial-new" data-aos="fade-down">
            <div class="container">
                <h2>
                    This is what our<br />
                    <span class="bg-custom-thickness">clients say</span> about
                    us
                </h2>

                <div class="testimonial-slider">
                    <div
                        class="owl-carousel owl-theme"
                        id="testimonial-slide-home"
                    >
                        <div class="item">
                            <div class="test-text-inner">
                                <div class="testi-avtar-im">
                                    <img src="img/tesi-two.jpg" alt="" />
                                </div>

                                <p>
                                    WA allows the customer to choose how they
                                    want to choose services they want. Time is
                                    our most valuable resource, and we were able
                                    to shorten the amount of time that people
                                    spent trying to follow up by a lot.
                                </p>

                                <span class="author-name"
                                    >Sany Rivers, Project Manager</span
                                >
                            </div>
                        </div>
                        <!------------item-div-end------------->

                        <div class="item">
                            <div class="test-text-inner">
                                <div class="testi-avtar-im">
                                    <img src="img/tesi-one.jpg" alt="" />
                                </div>

                                <p>
                                    WA allows the customer to choose how they
                                    want to choose services they want. Time is
                                    our most valuable resource, and we were able
                                    to shorten the amount of time that people
                                    spent trying to follow up by a lot.
                                </p>

                                <span class="author-name"
                                    >Sany Rivers, Project Manager</span
                                >
                            </div>
                        </div>
                        <!------------item-div-end------------->

                        <div class="item">
                            <div class="test-text-inner">
                                <div class="testi-avtar-im">
                                    <img src="img/tesi-two.jpg" alt="" />
                                </div>

                                <p>
                                    WA allows the customer to choose how they
                                    want to choose services they want. Time is
                                    our most valuable resource, and we were able
                                    to shorten the amount of time that people
                                    spent trying to follow up by a lot.
                                </p>

                                <span class="author-name"
                                    >Sany Rivers, Project Manager</span
                                >
                            </div>
                        </div>
                        <!------------item-div-end------------->

                        <div class="item">
                            <div class="test-text-inner">
                                <div class="testi-avtar-im">
                                    <img src="img/tesi-one.jpg" alt="" />
                                </div>

                                <p>
                                    WA allows the customer to choose how they
                                    want to choose services they want. Time is
                                    our most valuable resource, and we were able
                                    to shorten the amount of time that people
                                    spent trying to follow up by a lot.
                                </p>

                                <span class="author-name"
                                    >Sany Rivers, Project Manager</span
                                >
                            </div>
                        </div>
                        <!------------item-div-end------------->
                    </div>
                </div>
            </div>
        </section>
        <section class="news-latest" data-aos="fade-up">
            <div class="container">
                <h2>
                    Check out <br />
                    our
                    <span class="bg-custom-thickness">latest news</span>
                </h2>
                <div class="tabs-get">
                    <div class="news-tabs">
                        <h5>Work</h5>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a
                                    data-toggle="tab"
                                    href="#menu1"
                                    aria-expanded="false"
                                    >news</a
                                >
                            </li>
                            <li class="">
                                <a
                                    data-toggle="tab"
                                    href="#menu2"
                                    aria-expanded="false"
                                    >pulvinar</a
                                >
                            </li>
                            <li>
                                <a
                                    data-toggle="tab"
                                    href="#menu3"
                                    aria-expanded="true"
                                    >risus</a
                                >
                            </li>
                            <li>
                                <a
                                    data-toggle="tab"
                                    href="#menu4"
                                    aria-expanded="true"
                                    >amet</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div id="menu1" class="tab-pane fade active show">
                            <div class="products-inner row">
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-1.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-2.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-3.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                            </div>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <div class="products-inner row">
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-3.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-2.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-1.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                            </div>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <div class="products-inner row">
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-1.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-2.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-3.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                            </div>
                        </div>
                        <div id="menu4" class="tab-pane fade">
                            <div class="products-inner row">
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-3.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-2.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                                <div class="col-md-4">
                                    <div class="news-latest-cols">
                                        <div class="nes-im">
                                            <a href="#">
                                                <img
                                                    src="img/news-1.jpg"
                                                    alt=""
                                                />
                                            </a>
                                        </div>
                                        <span class="vendor">Work</span>
                                        <h3>
                                            <a href="#"
                                                >Donec nec justo eget felis
                                                facilisis digniss Aliquam</a
                                            >
                                        </h3>
                                        <p>
                                            Adapting wild plants and animals for
                                            people to use is called farmers were
                                            domestication....
                                        </p>
                                    </div>
                                </div>
                                <!-------------col-md-3------------>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="instagram-section" data-aos="fade-down">
            <div class="container">
                <h2>
                    Our <br />
                    <span class="bg-custom-thickness">social</span> media
                </h2>

                <div class="row">
                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-1.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-2.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-3.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-4.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-5.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post">
                        <a href="#">
                            <div class="insta-im">
                                <img src="img/instagram-6.jpg" alt="" />
                            </div>
                            <div class="insta-overlay">
                                <h4>Donec nec justo eget</h4>
                            </div>
                        </a>
                    </div>
                    <!-------------post-end------------>

                    <div class="insta-post insta-normal-text">
                        <a href="#">
                            <h4>
                                Check out instagram
                                <span class="more-icon"
                                    ><i
                                        class="fa fa-angle-right"
                                        aria-hidden="true"
                                    ></i
                                ></span>
                            </h4>
                        </a>
                    </div>
                    <!-------------post-end------------>
                </div>
            </div>
        </section>
        <footer class="site-footer" data-aos="fade-down">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 foo-top-text">
                        <h3>
                            Wellington Agricultural services has been a premium
                            agricultural service provider in the industry for
                            over 30 years. We are one of the largest
                            agricultural & manure removal companies in the palm
                            beach area.
                        </h3>
                    </div>
                    <div class="footer-middle-part row">
                        <div class="col-md-6 footr-links-upper">
                            <div class="col-md-6">
                                <h4>Resources</h4>
                                <ul class="footer-links">
                                    <li>
                                        <a href="#">How it Works</a>
                                    </li>
                                    <li>
                                        <a href="#">FAQ</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Company</h4>
                                <ul class="footer-links">
                                    <li>
                                        <a href="#">About</a>
                                    </li>
                                    <li>
                                        <a href="#">Contact</a>
                                    </li>
                                    <li>
                                        <a href="#">Term & Conditions</a>
                                    </li>
                                    <li>
                                        <a href="#">Privacy Policy</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h4>Get in touch</h4>
                                <div class="footer-address">
                                    <p>33565 NW 59th ST Miami, Florida</p>
                                    <p>
                                        <a
                                            href="mailto:info@wellingtonagricultural.com"
                                            >info@wellingtonagricultural.com</a
                                        >
                                    </p>
                                    <p>
                                        <a href="tel:+052-5401-3322"
                                            >(+052) 5401 3322</a
                                        >
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 btn-store-app">
                            <h2>
                                Make your life easier. Create your account now
                                or download our apps.
                            </h2>
                            <div class="footer-download-btn">
                                <a href="#" class="btn btn-account"
                                    >Create Account</a
                                >
                                <a href="#">
                                    <img
                                        src="img/app-stor-icon-gray.png"
                                        alt=""
                                    />
                                </a>
                                <a href="#">
                                    <img
                                        src="img/google-play-icon-gray.png"
                                        alt=""
                                    />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-boottom">
                <div class="container">
                    <div class="row">
                        <div class="bootm-social-links col-md-6">
                            <ul>
                                <li>
                                    <a href="#">
                                        <i
                                            class="fa fa-facebook-square"
                                            aria-hidden="true"
                                        ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i
                                            class="fa fa-instagram"
                                            aria-hidden="true"
                                        ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i
                                            class="fa fa-twitter"
                                            aria-hidden="true"
                                        ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i
                                            class="fa fa-pinterest"
                                            aria-hidden="true"
                                        ></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i
                                            class="fa fa-linkedin"
                                            aria-hidden="true"
                                        ></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="copy-right-text col-md-6">
                            <p>2020 © Wellington Agricultural Services Inc.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-----------------banner-section---------------->
</template>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
    }
};
</script>
