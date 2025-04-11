<?php
include 'config.php';

$stmt = $pdo->query("SELECT * FROM profiles LIMIT 1");
$profile = $stmt->fetch();

$stmt = $pdo->query("SELECT * FROM profile_cards");
$profileCards = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM gallery");
$galleryImages = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM skills ORDER BY id ASC");
$skills = $stmt->fetchAll();

// map collapse id to img path (for swapping in js)
$skillsImageMap = array_column($skills, 'image_path', 'collapse_id');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($profile['name'] ?? 'Profile'); ?> - Profile</title>

    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- style -->
    <link rel="stylesheet" href="styles.css">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  </head>
  <body>
    <!-- nav -->
    <nav class="navbar navbar-expand-lg px-5">
      <div class="container-fluid">
        <a class="navbar-brand" href="#hero"><?php echo htmlspecialchars($profile['name'] ?? 'Your Name'); ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#hero">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#skills">Skills</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#gallery">Gallery</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- hero -->
    <section class="hero light-mode" id="hero">
      <div class="hero-bg"></div>
      <div class="hero-content">
        <h1 class="display-3">Hello, I'm <span id="typed-name"></span></h1>
        <p class="lead"><?php echo htmlspecialchars($profile['role'] ?? 'Digital Creator, Web Developer & Game Artist'); ?></p>
        <p class="lead"><?php echo htmlspecialchars($profile['tagline'] ?? 'I transform ideas into interactive digital experiences.'); ?></p>
        <div class="scroll-arrow align-items-center">
          <a href="#profile">
            <i class="bi bi-chevron-double-down"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- profile -->
    <div class="container mt-5 px-5" id="profile">
      <div class="row align-items-center">
        <div class="col-md-4 text-center">
          <img src="<?php echo htmlspecialchars($profile['profile_picture'] ?? 'img/pfp.png'); ?>" alt="Profile Picture" class="profile-pic mica">
        </div>
        <div class="col-md-8 mt-4">
          <h1><?php echo htmlspecialchars($profile['name'] ?? 'Your Name'); ?></h1>
          <p class="bio mt-4">
            <?php echo nl2br(htmlspecialchars($profile['bio'] ?? '')); ?>
          </p>
        </div>
      </div>
    </div>

    <!-- profile cards -->
    <div class="container my-5 px-5">
      <div class="row g-4">
        <?php foreach ($profileCards as $card) : ?>
          <div class="col-md-4">
            <div class="card mica">
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($card['title']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($card['description']); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- marquee -->
    <div class="marquee enable-animation">
      <div class="marquee__content">
        <span>Web Developer</span>
        <span> • </span>
        <span>UI/UX Designer</span>
        <span> • </span>
        <span>Game Artist</span>
        <span> • </span>
        <span>Character Design Enthusiast</span>
        <span> • </span>
        <span>Crafting Experiences that Matter</span>
        <span> • </span>
      </div>
      <div aria-hidden="true" class="marquee__content">
        <span>Web Developer</span>
        <span> • </span>
        <span>UI/UX Designer</span>
        <span> • </span>
        <span>Game Artist</span>
        <span> • </span>
        <span>Character Design Enthusiast</span>
        <span> • </span>
        <span>Crafting Experiences that Matter</span>
        <span> • </span>
      </div>
    </div>
    
    <!-- skills -->
    <div class="white-bg" id="skills">
      <div class="container my-5 light-mode skill-list px-5">
        <div class="row align-items-center">
          <h1 class="text-center">My Skills</h1>
          <div class="d-flex align-items-center mb-4">
            <hr class="flex-grow-1" style="height: 1px; border: none; background-color: #000000;">
            <span class="mx-3">What I Can Do</span>
            <hr class="flex-grow-1" style="height: 1px; border: none; background-color: #000000;">
          </div>
          
          <div class="row">
            <div class="col-lg-6">
              <div class="accordion" id="skillsAccordion">
                <?php foreach ($skills as $skill) : ?>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <!-- Use the collapse_id from the DB for both target and for JS mapping -->
                      <button class="accordion-button <?php echo ($skill === reset($skills)) ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo htmlspecialchars($skill['collapse_id']); ?>">
                        <?php echo htmlspecialchars($skill['title']); ?>
                      </button>
                    </h2>
                    <div id="<?php echo htmlspecialchars($skill['collapse_id']); ?>" class="accordion-collapse collapse <?php echo ($skill === reset($skills)) ? 'show' : ''; ?>" data-bs-parent="#skillsAccordion">
                      <div class="accordion-body">
                        <?php echo htmlspecialchars($skill['description']); ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- image -->
            <div class="col-lg-6 order-2 order-lg-2 text-center mt-4">
              <img id="display-image" src="banner.jpg" alt="Skill Image" class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- stat -->
    <div class="container px-5 my-5">
      <div class="row g-3">
        <div class="col-md-3">
          <div class="counter-box dark-mode d-flex flex-column justify-content-center align-items-center p-4">
            <h2 class="counter">120+</h2>
            <p>Projects Completed</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-box light-mode d-flex flex-column justify-content-center align-items-center p-4">
            <h2 class="counter">50+</h2>
            <p>Happy Clients</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-box dark-mode d-flex flex-column justify-content-center align-items-center p-4">
            <h2 class="counter">300+</h2>
            <p>Cups of Coffee</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-box light-mode d-flex flex-column justify-content-center align-items-center p-4">
            <h2 class="counter">5+</h2>
            <p>Years of Experience</p>
          </div>
        </div>
      </div>
    </div>

    <!-- galer -->
    <div class="white-bg light-mode" id="gallery">
      <div class="container my-5 image-grid px-5">
        <h1 class="text-center">Gallery</h1>
        <div class="d-flex align-items-center mb-4">
          <hr class="flex-grow-1" style="height: 1px; border: none; background-color: #000000;">
          <span class="mx-3">See My Works</span>
          <hr class="flex-grow-1" style="height: 1px; border: none; background-color: #000000;">
        </div>
        <div class="row">
          <?php foreach ($galleryImages as $image): ?>
            <div class="col-md-4 mb-4">
              <div class="grid-item">
                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['alt_text']); ?>" class="grid-image">
                <div class="overlay">
                  <span class="overlay-text"><?php echo htmlspecialchars($image['overlay_text']); ?></span>
                  <button class="expand-btn">
                    <i class="bi bi-arrows-fullscreen"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- src: https://github.com/mattboldt/typed.js -->
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <script>
      // onscroll
      window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });
      
      // build js map from php arr
      const skillsData = <?php echo json_encode($skillsImageMap); ?>;
      
      // onclick
      // collapse id (without #) = image path
      document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', () => {
          const skillId = button.getAttribute('data-bs-target').replace('#', '');
          const newSrc = skillsData[skillId];
          const displayImage = document.getElementById('display-image');
          if(newSrc && displayImage) {
            displayImage.style.opacity = '0';
            setTimeout(() => {
              displayImage.src = newSrc;
              displayImage.style.opacity = '1';
            }, 200);
          }
        });
      });
      
      // onload (set init img)
      document.addEventListener('DOMContentLoaded', () => {
        const openAccordion = document.querySelector('.accordion-collapse.show');
        if (openAccordion) {
          const button = openAccordion.previousElementSibling.querySelector('.accordion-button');
          if (button) {
            const skillId = button.getAttribute('data-bs-target').replace('#', '');
            const displayImage = document.getElementById('display-image');
            if(displayImage && skillsData[skillId]) {
              displayImage.src = skillsData[skillId];
            }
          }
        }

        // ref: https://github.com/mattboldt/typed.js#customization
        const typed = new Typed('#typed-name', {
          strings: ['Paris', 'a Developer', 'a Designer', 'a Creator'],
          typeSpeed: 50,
          backSpeed: 30,
          backDelay: 1000,
          loop: true,
        });
      });
    </script>
  </body>
</html>
