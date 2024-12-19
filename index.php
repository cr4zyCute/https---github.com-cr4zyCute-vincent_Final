<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css"rel="stylesheet"/>
    <link rel="icon" href="./images/bsitlogo.png">
    <link rel="stylesheet" href="./css/index.css" />
    <title>CTU-BSIT-Home</title>
  </head>
  <body>
    ok
    <nav>
      <div class="nav__header">
            <div class="nav__logo">
            <a href="#">
                <img src="./images/bsitlogo.png" alt="Logo" class="logo">
                BSIT
            </a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="#home">HOME</a></li>
        <li><a href="#about">ABOUT US</a></li>
        <li><a href="#footer">CONTACT</a></li>
        <li>
          <div class="container__login">
         <button class="btn">Log In</button>
       </div>
        </li>
      </ul>
    </nav>


    <div id="home" class="container">
      <div class="container__left">
      </div>
      <div class="container__right">
        <div class="images">
          <img src="./images/bsitlogo.png" alt="image" class="tent-1" />
        </div>
        <div class="content">
           <h2>Bachelor of Science <br> in Information Technology</h2>
          <h3>Your Future Starts Here!!</h3>
          <p>
           The Bachelor of Science in Information Technology (BSIT) equips students with cutting-edge skills in programming, networking, preparing them to thrive in the rapidly evolving tech landscape.
          </p>
        </div>
      </div>
    </div>

    <section id="about">
    <div class="intro-text">
        <h2>Explore BSIT Programs</h2>
        <p>
            Discover the wide-ranging opportunities offered by our Bachelor of Science in Information Technology program. Whether you’re passionate about innovation, technology, or academic excellence, our BSIT program equips you with the tools for success in the ever-evolving IT industry.
        </p>
    </div>
    
    <div class="container">
        <div class="cards reveal">
            <div class="text-card">
                <img src="./images/bg.jpg" alt="Description of Image" class="card-image">
                <h3>BSIT</h3>
                <p>Celebrate academic excellence with the Bachelor of Science in Information Technology program at Cebu Technological University – Consolacion Campus. Whether you're gearing up for the future or proudly donning your graduation attire, this program paves the way for success in the dynamic IT field.</p>
            </div>
        </div>
        <div class="cards reveal">
            <div class="text-card">
                <img src="./images/bg.jpg" alt="Description of Image" class="card-image">
                <h3>BSIT</h3>
                <p>Celebrate academic excellence with the Bachelor of Science in Information Technology program at Cebu Technological University – Consolacion Campus. Whether you're gearing up for the future or proudly donning your graduation attire, this program paves the way for success in the dynamic IT field.</p>
            </div>
        </div>
        <div class="cards reveal">
            <div class="text-card">
                <img src="./images/bg.jpg" alt="Description of Image" class="card-image">
                <h3>BSIT</h3>
                <p>Celebrate academic excellence with the Bachelor of Science in Information Technology program at Cebu Technological University – Consolacion Campus. Whether you're gearing up for the future or proudly donning your graduation attire, this program paves the way for success in the dynamic IT field.</p>
            </div>
        </div>
    </div>
</section>
<div id="footer">

<?php 
  include './includes/footer.php'
?>
</div>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="./js/index.js"></script>
  </body>
</html>