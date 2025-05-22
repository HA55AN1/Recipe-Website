<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Including Bootstrap Icons for social media icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="styles.css"/>
    <title>About Us</title>
  </head>
  <body>

    <!-- Include Header -->
    <?php include 'header.php';?>

    <!-----------------------About Us Section------------------------>
    <!-- Section for the main About Us heading and description -->
    <div class="about-heading">
      <h1>About Us</h1>
      <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsa animi 
      ex accusantium architecto esse temporibus vero, cum qui quo sunt optio 
      labore quisquam! Sunt, dolores. Quo sit distinctio cupiditate! Facere.
      </p>
    </div>

    <!-- Container for About Us content -->
    <div class="about-container">
      <section class="about">
        <div class="about-image">
          <!-- Image of the company or restaurant -->
          <img src="images/about.jpg" alt="About Us Image">
        </div>
        <div class="about-content">
          <h2>Warm embrace</h2>
          <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsa animi 
            ex accusantium architecto esse temporibus vero, cum qui quo sunt optio 
            labore quisquam! Sunt, dolores. Quo sit distinctio cupiditate! Facere.
          </p>
          <!-- A placeholder link for 'Read More' functionality (could be updated later) -->
          <a href="" class="read-more">Read More</a>
          <br><br />
          <br><br />
          <br><br />
        </div>
      </section>
    </div>

    <!-----------------------Team Section----------------------->
    <!-- Section introducing the chef team of the company -->
    <section>
      <div class="team">
        <div class="title">
          <h1>Our Chef Team</h1> <!-- Title of the team section -->
        </div>
        
        <div class="row">
          <!-- Repeated box for each team member (Chef) -->
          <div class="box">
            <div class="img-box">
              <!-- Chef Image -->
              <img src="images/chef1.jpg" alt="Chef 1">
            </div>
            <div class="detail">
              <span>Head Chef</span>
              <h4>Miguel Rodrigez</h4>
              <div class="icons">
                <!-- Social media icons for Chef's social profiles -->
                <i class="bi bi-instagram"></i>
                <i class="bi bi-youtube"></i>
                <i class="bi bi-twitter"></i>
                <i class="bi bi-behance"></i>
                <i class="bi bi-whatsapp"></i>
              </div>
            </div>
          </div>

          <!-- Repeat box for other chefs, with same layout -->
          <div class="box">
            <div class="img-box">
              <img src="images/chef1.jpg" alt="Chef 2">
            </div>
            <div class="detail">
              <span>Chef</span>
              <h4>Miguel Rodrigez</h4>
              <div class="icons">
                <i class="bi bi-instagram"></i>
                <i class="bi bi-youtube"></i>
                <i class="bi bi-twitter"></i>
                <i class="bi bi-behance"></i>
                <i class="bi bi-whatsapp"></i>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="img-box">
              <img src="images/chef1.jpg" alt="Chef 3">
            </div>
            <div class="detail">
              <span>Chef</span>
              <h4>Miguel Rodrigez</h4>
              <div class="icons">
                <i class="bi bi-instagram"></i>
                <i class="bi bi-youtube"></i>
                <i class="bi bi-twitter"></i>
                <i class="bi bi-behance"></i>
                <i class="bi bi-whatsapp"></i>
              </div>
            </div>
          </div>

          <div class="box">
            <div class="img-box">
              <img src="images/chef1.jpg" alt="Chef 4">
            </div>
            <div class="detail">
              <span>Chef</span>
              <h4>Miguel Rodrigez</h4>
              <div class="icons">
                <i class="bi bi-instagram"></i>
                <i class="bi bi-youtube"></i>
                <i class="bi bi-twitter"></i>
                <i class="bi bi-behance"></i>
                <i class="bi bi-whatsapp"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Include footer-->
    <?php include 'footer.php';?>

    <!-- Include Script -->
    <script src="script.js"></script>

  </body>
</html>
