<?php

$pageTitle  = $pageTitle  ?? 'UCLan Student Union Shop';
$pageDesc   = $pageDesc   ?? 'UCLan Student Union Shop – official UCLan merchandise.';
$activePage = $activePage ?? '';
$cartQty    = is_logged_in() ? cart_count() : 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo h($pageDesc); ?>">
  <title><?php echo h($pageTitle); ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <a href="#main-content" class="skip-link">Skip to main content</a>

  <header role="banner">
    <div class="header-inner">
      <a href="index.php" class="site-logo" aria-label="UCLan Student Union Shop – go to homepage">
        <img src="images/logo_reverse.png" alt="University of Lancashire crest and wordmark" height="60">
        <span class="logo-tagline">Student Union Shop</span>
      </a>

      <nav aria-label="Primary navigation">
        <button class="hamburger" id="hamburger-btn"
                aria-expanded="false" aria-controls="nav-links"
                aria-label="Toggle navigation menu">
          <span></span><span></span><span></span>
        </button>

        <ul class="nav-links" id="nav-links" role="list">
          <li><a href="index.php"<?php echo $activePage === 'home' ? ' class="active" aria-current="page"' : ''; ?>>Home</a></li>
          <li><a href="products.php"<?php echo $activePage === 'products' ? ' class="active" aria-current="page"' : ''; ?>>Products</a></li>
          <li>
            <a href="cart.php" class="cart-link<?php echo $activePage === 'cart' ? ' active' : ''; ?>"<?php echo $activePage === 'cart' ? ' aria-current="page"' : ''; ?> aria-label="Shopping cart">
              &#128722; Cart
              <span class="cart-badge" id="cart-count" aria-live="polite" aria-atomic="true" style="<?php echo $cartQty > 0 ? 'display:flex;' : ''; ?>"><?php echo (int)$cartQty; ?></span>
            </a>
          </li>
          <?php if (is_logged_in()): ?>
            <li><span class="nav-greeting">Hi, <?php echo h($_SESSION['user_name']); ?></span></li>
            <li><a href="logout.php">Logout</a></li>
          <?php else: ?>
            <li><a href="login.php">Login</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </header>

  <main id="main-content" tabindex="-1">
