<style>
/* -------------------------------------- */
/* START: MODERN FOOTER CSS */
/* -------------------------------------- */
.modern-footer {
    /* Light Black/Dark Gray Background */
    background-color: #212529;
    color: #adb5bd;
    padding-top: 3rem;
    padding-bottom: 3rem;
}

.modern-footer h4 {
    color: #ffffff;
    /* INCREASED SIZE: Made headings slightly larger */
    font-size: 1.25rem; 
    margin-bottom: 1.5rem;
    font-weight: 600;
    display: inline-block;
    padding-bottom: 5px;
}

.modern-footer ul {
    list-style: none;
    padding: 0;
}

.modern-footer ul a {
    color: #adb5bd;
    text-decoration: none;
    transition: color 0.3s ease, padding-left 0.3s ease;
    display: inline-block;
    padding-left: 0;
    /* REDUCED SPACE: Tighter vertical spacing between links */
    margin-bottom: 0.3rem; 
    /* INCREASED SIZE: Made link text slightly larger */
    font-size: 1.05rem; 
}

.modern-footer ul a:hover {
    color: #0d6efd;
    padding-left: 5px; /* Subtle slide effect on hover */
}

/* Styling for Contact/Address information */
.contact-info p {
    /* INCREASED SIZE: Made contact text slightly larger */
    font-size: 1.05rem; 
    margin-bottom: 0.5rem;
    display: flex;
    align-items: flex-start; /* Aligns text block to the top when icons are present */
}

.contact-info i {
    margin-right: 10px;
    color: #0d6efd; /* Icon color */
    /* Ensure icon is aligned at the start of multiple lines of text */
    padding-top: 0.2rem; 
    flex-shrink: 0; /* Prevents the icon from shrinking */
}

/* Copyright Section Styling */
.copyright-text {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 1rem;
    margin-top: 1rem;
    font-size: 1rem;
    text-align: center;
    color: #adb5bd;
}
</style>
<footer id="footer" class="modern-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="alumni.php">Alumni</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="about-us.php">About Us</a></li>
                    <li><a href="login.php">Sign in</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h4>Legal</h4>
                <ul>
                    <li><a href="policy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Use</a></li>
                    <li><a href="#">Sitemap</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h4>Contact Us</h4>
                <div class="contact-info">
                    <p class="justify-content-center justify-content-lg-center"><i class="fas fa-phone-alt"></i> +92 3454262922</p>
                    <p class="justify-content-center justify-content-lg-center"><i class="fas fa-envelope"></i> abc@gmail.com</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 mb-4 mb-lg-0 text-center text-lg-start">
                <h4>Address</h4>
                <div class="contact-info">
                    <p class="justify-content-center justify-content-lg-start">
                        <i class="fas fa-map-marker-alt"></i> 
                        Govt College Lower Chitral, Near Scout Headquarter, Khyber Pakhtunkhwa, Pakistan.
                    </p>
                    <p class="justify-content-center justify-content-lg-start">
                        <i class="fas fa-university"></i> 
                        Affiliated with the University of Chitral.
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12">
                <p class="copyright-text">
                    © 2025 Government College Chitral CS Department. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
