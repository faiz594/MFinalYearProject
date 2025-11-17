<?php 
include "header.php"; 
?>


    <style>
        .hod-section {
            padding: 50px 0;
        }

        .hod-img {
            position: relative;
            display: inline-block;
        }

        .hod-img img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        .hod-img::before {
            content: "";
            position: absolute;
            top: -15px;
            left: -15px;
            width: 100%;
            height: 100%;
            border: 6px solid #6f42c1;
            /* purple border */
            z-index: -1;
            border-radius: 10px;
        }

        .hod-img::after {
            content: "";
            position: absolute;
            bottom: -15px;
            right: -15px;
            width: 100%;
            height: 100%;
            border: 6px solid #ffc107;
            /* yellow border */
            z-index: -1;
            border-radius: 10px;
        }
    </style>
</head>

    <div class="bg-light py-4 text-center">
        <h2 class="fw-bold">Head of the Department Message</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Head of the Department Message</li>
            </ol>
        </nav>
    </div>

    <!-- HOD Section -->
    <div class="container hod-section">
        <div class="row align-items-center">
            <!-- Image -->
            <div class="col-md-5 text-center mb-4 mb-md-0">
                <div class="hod-img">
                    <img src="../img/faculty/Sir-Shakeel.JPG" alt="Shakeel Ahmad Khan">
                </div>
            </div>
            <!-- Message -->
            <div class="col-md-7">
                <h3 class="fw-bold mb-3">Head of the Department Message</h3>
                <p>
                    Welcome to the Department of Computer Science at Govt. Degree College Chitral. Our department is
                    committed to
                    providing quality education and nurturing the next generation of computer scientists, innovators,
                    and IT
                    professionals. In today’s fast-evolving digital age, information technology plays a vital role in
                    every aspect
                    of society, and we aim to equip our students with the skills and knowledge necessary to thrive in
                    this dynamic
                    environment.
                </p>
                <p>
                    The department takes pride in its dedicated faculty, enthusiastic students, and steadily growing
                    alumni network
                    who are contributing in diverse sectors, including education, software development, IT services, and
                    entrepreneurship. Through a balance of theoretical foundations and practical training, we prepare
                    our students
                    to meet both local and global challenges in the field of computer science.
                </p>
                <p>
                    We encourage creativity, critical thinking, and innovation by engaging students in research
                    projects, applied
                    learning, and community-driven IT solutions. Our focus extends beyond academics—we aim to shape
                    graduates who
                    are not only skilled professionals but also responsible individuals committed to serving society.
                </p>
                <p>
                    I warmly welcome you to be part of the Department of Computer Science at Govt. Degree College
                    Chitral, and I
                    look forward to seeing our students achieve excellence, contribute to the IT sector, and play their
                    role in
                    shaping a better future.
                </p>
                <p class="mt-4">
                    <strong>Sincerely,</strong><br>
                    Shakeel Ahmad Khan<br>
                    Head, Department of Computer Science<br>
                    Govt. Degree College Chitral
                </p>
            </div>
        </div>
    </div>

</body>

</html>