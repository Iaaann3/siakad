<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - SnapFolio Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets-/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets-/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets-/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets-/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets-/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets-/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets-/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets-/css/main.css') }}" rel="stylesheet">

</head>

<body class="index-page">

  <header id="header" class="header dark-background d-flex flex-column justify-content-center">
    <i class="header-toggle d-xl-none bi bi-list"></i>

    <div class="header-container d-flex flex-column align-items-start">
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active"><i class="bi bi-house navicon"></i>Home</a></li>
          <li><a href="#about"><i class="bi bi-person navicon"></i> About</a></li>
          <li><a href="#resume"><i class="bi bi-file-earmark-text navicon"></i> Resume</a></li>
          <li><a href="#portfolio"><i class="bi bi-images navicon"></i> Portfolio</a></li>
          <li><a href="#services"><i class="bi bi-hdd-stack navicon"></i> Services</a></li>
          <li><a href="#contact"><i class="bi bi-envelope navicon"></i> Contact</a></li>
        </ul>
      </nav>

    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="background-elements">
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
      </div>

      <div class="hero-content">
        <div class="container">
          <div class="row align-items-center">

            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
              <div class="hero-text">
                <h1>Siakad<span class="accent-text">Assalaam</span></h1>
                <h2>Alexander Chen</h2>
                <p class="lead">I'm a <span class="typed" data-typed-items="UI/UX Designer, Web Developer, Brand Strategist, Creative Director"></span></p>
              </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
              <div class="hero-visual">
                <div class="profile-container">
                  <div class="profile-background"></div>
                  <img src="{{ asset('assets-/img/profile/profile-2.webp') }}" alt="Alexander Chen" class="profile-image">
                  <div class="floating-elements">
                    <div class="floating-icon icon-1"><i class="bi bi-palette"></i></div>
                    <div class="floating-icon icon-2"><i class="bi bi-code-slash"></i></div>
                    <div class="floating-icon icon-3"><i class="bi bi-lightbulb"></i></div>
                    <div class="floating-icon icon-4"><i class="bi bi-graph-up"></i></div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">
      <!-- Fade-in attribute for JS control -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="stats-wrapper">
              <div class="stats-item" data-aos="zoom-in" data-aos-delay="150">
                <div class="icon-wrapper">
                  <i class="bi bi-emoji-smile"></i>
                </div>
                <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                <p>Happy Clients</p>
              </div>

              <div class="stats-item" data-aos="zoom-in" data-aos-delay="200">
                <div class="icon-wrapper">
                  <i class="bi bi-journal-richtext"></i>
                </div>
                <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
                <p>Projects</p>
              </div>

              <div class="stats-item" data-aos="zoom-in" data-aos-delay="250">
                <div class="icon-wrapper">
                  <i class="bi bi-headset"></i>
                </div>
                <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1" class="purecounter"></span>
                <p>Hours Of Support</p>
              </div>

              <div class="stats-item" data-aos="zoom-in" data-aos-delay="300">
                <div class="icon-wrapper">
                  <i class="bi bi-people"></i>
                </div>
                <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1" class="purecounter"></span>
                <p>Hard Workers</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section>

  </main>

  <footer id="footer" class="footer position-relative">
    <div class="container">
      <div class="copyright text-center">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">iPortfolio</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets-/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets-/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets-/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets-/vendor/typed.js/typed.umd.js') }}"></script>
  <script src="{{ asset('assets-/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets-/vendor/waypoints/noframework.waypoints.js') }}"></script>
  <script src="{{ asset('assets-/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets-/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets-/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets-/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('assets-/js/main.js') }}"></script>
  </body>

</html>
