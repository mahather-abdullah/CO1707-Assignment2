<?php
require 'auth.php';
require 'db.php';


$sql    = "SELECT offer_id, offer_title, offer_dec FROM tbl_offers ORDER BY offer_id";
$result = mysqli_query($connection, $sql);

$pageTitle  = 'Home | UCLan Student Union Shop';
$pageDesc   = 'UCLan Student Union Shop – official UCLan merchandise.';
$activePage = 'home';
require 'includes/header.php';
?>

    <section class="hero" aria-labelledby="hero-heading">
      <div class="hero-content">
        <h1 id="hero-heading">
          UCLan
          <span>Student Union Shop</span>
        </h1>
        <?php if (is_logged_in()): ?>
          <p>Welcome back, <strong><?php echo h($_SESSION['user_name']); ?></strong>! Browse our full range of official <strong>UCLan</strong> hoodies, t-shirts and jumpers.</p>
        <?php else: ?>
          <p>Browse our full range of official <strong>UCLan</strong> hoodies, t-shirts and jumpers. <a href="login.php" style="color:var(--gold);text-decoration:underline;">Log in</a> to start shopping.</p>
        <?php endif; ?>
        <div class="hero-btns">
          <a href="products.php" class="btn btn-gold">Shop the Range</a>
          <a href="#about" class="btn btn-outline-white">About the University</a>
        </div>
      </div>
    </section>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <section id="offers" aria-labelledby="offers-heading">
      <h2 id="offers-heading" class="section-heading">Current Offers</h2>
      <hr class="section-divider" aria-hidden="true">
      <div class="facts-grid">
        <?php while ($offer = mysqli_fetch_assoc($result)): ?>
          <div class="fact-card">
            <h3><?php echo h($offer['offer_title']); ?></h3>
            <p><?php echo h($offer['offer_dec']); ?></p>
          </div>
        <?php endwhile; ?>
      </div>
    </section>
    <?php endif; ?>

    <section id="about" aria-labelledby="about-heading">
      <h2 id="about-heading" class="section-heading">About the University of Lancashire</h2>
      <hr class="section-divider" aria-hidden="true">

      <article class="about-box" aria-label="University of Lancashire overview">
        <p>
          The <strong>University of Lancashire</strong> (UoL), based in <strong>Preston</strong>,
          is one of the UK's largest and most dynamic universities. Founded in <strong>1828</strong>
          as the Institution for the Diffusion of Knowledge, it has grown into a globally respected
          institution serving over <strong>38,000 students</strong> from more than 100 countries.
        </p>
        <p>
          Formerly known as the <strong>University of Central Lancashire (UCLan)</strong>, the
          university rebranded in 2025 to reflect its deep roots in the Lancashire region. The
          rebrand introduced a refreshed identity built around the historic Lancashire rose crest
          and a modern blue colour palette — hence this range of original UCLan merchandise.
        </p>
        <p>
          The university offers over <strong>500 undergraduate and postgraduate programmes</strong>
          spanning arts, sciences, business, health, engineering, law, and computing, with
          state-of-the-art facilities and world-leading research centres.
        </p>

        <aside aria-label="University key facts">
          <div class="facts-grid">
            <div class="fact-card">
              <h3>&#127979; Founded 1828</h3>
              <p>Nearly 200 years of history in education and research in the heart of Lancashire.</p>
            </div>
            <div class="fact-card">
              <h3>&#127758; 100+ Countries</h3>
              <p>Students from across the globe study at UoL, with campuses and partnerships worldwide.</p>
            </div>
            <div class="fact-card">
              <h3>&#127891; 500+ Courses</h3>
              <p>Undergraduate, postgraduate, and professional programmes across all major subjects.</p>
            </div>
            <div class="fact-card">
              <h3>&#127775; World Research</h3>
              <p>Home to Jeremiah Horrocks Institute, Institute for Forensics, and more.</p>
            </div>
          </div>
        </aside>
      </article>
    </section>

    <section class="video-section" aria-labelledby="video-heading">
      <h2 id="video-heading" class="section-heading">Videos</h2>
      <hr class="section-divider" aria-hidden="true">

      <div class="video-grid">
        <div>
          <div class="video-wrap">
            <video controls preload="metadata" aria-label="UCLan Student Union Shop introduction video">
              <source src="video.mp4" type="video/mp4">
              <p>Your browser does not support HTML5 video.
                 <a href="video.mp4">Download the video</a> instead.</p>
            </video>
          </div>
          <p class="video-label">&#127910; UCLan Student Union Shop – Introduction</p>
        </div>

        <div>
          <div class="video-wrap">
            <iframe
              src="https://www.youtube.com/embed/vzbO3x3OUJQ"
              title="University of Lancashire campus and student life"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
              loading="lazy">
            </iframe>
          </div>
          <p class="video-label">&#127910; University of Lancashire – Campus Tour</p>
        </div>
      </div>
    </section>

<?php require 'includes/footer.php'; ?>
