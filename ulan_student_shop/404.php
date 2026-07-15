<?php
require 'auth.php';
require 'db.php';
http_response_code(404);

$pageTitle = 'Page Not Found | UCLan Student Union Shop';
$pageDesc  = 'The page you were looking for could not be found.';
require 'includes/header.php';
?>

    <section class="notfound-section" aria-labelledby="nf-heading">
      <p class="notfound-code" aria-hidden="true">404</p>
      <h1 id="nf-heading" class="section-heading">Page not found</h1>
      <p style="margin-bottom:var(--s24);">Sorry, we couldn't find the page or product you were looking for. It may have been removed, or the link might be out of date.</p>
      <div class="hero-btns">
        <a href="index.php" class="btn btn-blue">Back to Home</a>
        <a href="products.php" class="btn btn-outline">Browse Products</a>
      </div>
    </section>

<?php require 'includes/footer.php'; ?>
