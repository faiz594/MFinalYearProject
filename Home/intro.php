<?php 
include "header.php"; 
?>

    <style>
        .hod-section {
            padding: 50px 0;
        }

        .cs-img {
            position: relative;
            display: inline-block;
        }

        .cs-img img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        .cs-img::before {
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

        .cs-img::after {
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
        <h2 class="fw-bold">About Us</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Introduction</li>
            </ol>
        </nav>
    </div>

    <!-- HOD Section -->
    <div class="container hod-section">
        <div class="row align-items-center">
            <!-- Image -->
            <div class="col-md-5 text-center mb-4 mb-md-0">
                <div class="cs-img">
                    <img src="../img/cs-bg.png" alt="CS Department">
                </div>
            </div>
            <!-- Message -->
            <div class="col-md-7">
                <h3 class="fw-bold mb-3">Introduction</h3>
                <p>
                    The Department of Computer Science at Govt. Degree College Chitral is a growing hub of knowledge and
                    innovation in the field of Computer Science. Established with the vision of providing quality
                    education in one of the most remote yet culturally rich regions of Pakistan, the department is
                    committed to preparing students with strong academic foundations and practical skills.
                    Despite being relatively young compared to larger universities, the department has quickly emerged
                    as a vital center for technology education in the mountainous district of Chitral. It has played a
                    meaningful role in creating awareness about the importance of Information Technology in education,
                    business, governance, and community development.
                    The department not only imparts theoretical knowledge but also emphasizes hands-on experience
                    through well-equipped computer laboratories and applied projects. Graduates of the department are
                    contributing in diverse fields, including education, software development, IT services, and
                    entrepreneurship, both within the country and abroad.
                    Surrounded by the scenic beauty of Chitral's mountains and valleys, the department offers a unique
                    academic environment where learning blends with creativity and inspiration. With a dedicated
                    faculty, the department is committed to nurturing talent, encouraging research, and fostering
                    innovation that addresses both national and local needs.
                   <p> <strong fw-bold>Vision & Mission</strong></p>
                    The Department of Computer Science at Govt. Degree College Chitral aims to empower students with the
                    skills, knowledge, and confidence required to meet the challenges of the modern digital world. Its
                    mission is to prepare graduates who are not only competent professionals but also socially
                    responsible citizens who can contribute positively to society.
                    Vision highlights include:
                    <p>• Providing quality education through an updated curriculum, practical training, and research-driven
                    learning.</p>
                    <p>• Building strong academic and professional linkages with universities, industries, and
                    organizations at regional, national, and international levels.</p>
                    <p>• Encouraging research and projects that focus on solving local community problems through IT-based
                    solutions.</p>
                    <p>• Organizing seminars, workshops, job fairs, and career counseling activities to connect students
                    with potential employers and industry experts.</p>
                   <p> • Preparing graduates who can pursue higher education, join the global IT workforce, or contribute
                    as entrepreneurs and innovators.
                    By focusing on academic excellence, industry collaboration, and community-oriented research, the
                    department seeks to become a leading contributor to the IT landscape of Chitral and beyond.
                    </p>
                </p>
            </div>
        </div>
    </div>

</body>

</html>