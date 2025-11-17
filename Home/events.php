<?php 
include "header.php"; 
?>

    <!-- Hero Section with Bootstrap only -->
    <section class="position-relative"
        style="background: url('../img/faculty/faculty.jpg') center/cover no-repeat; height: 330px;">
        <!-- light overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white" style="opacity: .2;"></div>

        <!-- Content centered -->
        <div class="position-relative d-flex flex-column align-items-center justify-content-center h-100 text-center">
            <h1 class="fw-bold text-warning mt-5">Upcoming Events</h1>
            <h2 class="text-warning">Home / Events</h2>
        </div>
    </section>
    <!--================End Home Banner Area =================-->
    <div class="container section">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section-head"><a href="events.html">Events</a> </h2>
            </div>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="news-post">
                            <h4 id="news-heading">CS Department hosts exciting events — from hackathons and project
                                exhibitions to guest lectures and career sessions — giving students opportunities to
                                learn, network, and shine.</h4>
                            <img id="news-img" src="../img/events/event1.jpg" alt="">
                            <h3><a href=""> Project Exhibition</a></h3>
                            <div class="post-date"> Ausust 6, 2025</div>
                            <p>Our department hosts exciting events — from hackathons and project exhibitions to guest
                                lectures and career sessions — giving students opportunities to learn, network, and
                                shine.</p>
                            <a href="" class="readmore">Read More</a>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-4">
                        <div class="news-post">
                            <h4 id="news-heading">Lorem ipsum dolor sit amet consectetur.Lorem, ipsum dolor sit amet
                                consectetur
                                adipisicing elit. Eligendi, eaque?</h4>
                            <img id="news-img" src="../img/events/event2.jpg" alt="">
                            <h3><a href=""> News heading here</a></h3>
                            <div class="post-date"> Ausust 6, 2025</div>
                            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Earum, aperiam.</p>
                            <a href="" class="readmore">Read More</a>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>

    <!--================ Start footer Area  =================-->
    <?php include "footer.php"; ?>