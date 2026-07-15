<?php
require 'auth.php';
require 'db.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$next  = $_GET['next'] ?? $_POST['next'] ?? 'index.php';
$error = isset($_GET['error']);

$pageTitle = 'Login | UCLan Student Union Shop';
$pageDesc  = 'Log in to the UCLan Student Union Shop.';
require 'includes/header.php';
?>

    <div class="split-auth-wrap">
      <div class="split-auth-card">

        <div class="split-auth-visual" aria-hidden="true">
          <div class="blob blob--ring"></div>
          <div class="blob blob--1"></div>
          <div class="blob blob--2"></div>
          <div class="split-auth-brand">
            
            <h2>UCLan Student Shop</h2>
            <p>Log in to track orders, save your details, and leave reviews.</p>
          </div>
        </div>

        <div class="split-auth-form">
          <h1 id="login-heading">User Login</h1>

          <?php if ($error): ?>
            <div class="auth-alert auth-alert--error" role="alert">&#10060; Incorrect email or password. Please try again.</div>
          <?php endif; ?>

          <form action="login_process.php" method="post" class="auth-form">
            <input type="hidden" name="next" value="<?php echo h($next); ?>">

            <div class="field field-icon">
              <label for="email" class="sr-only">Email</label>
              <span class="icon">&#128100;</span>
              <input type="email" id="email" name="email" required
                     autocomplete="email" placeholder="Email address">
            </div>

            <div class="field field-icon field-password">
              <label for="password" class="sr-only">Password</label>
              <span class="icon">&#128274;</span>
              <input type="password" id="password" name="password" required
                     autocomplete="current-password" placeholder="Password">
              
            </div>

            <button type="submit" class="auth-submit-pill">Login</button>
          </form>

          <div class="split-auth-links">
            <a href="#">Forgot Username/Password?</a>
            <a href="register.php">Create Your Account &rarr;</a>
          </div>
        </div>

      </div>
    </div>

<?php require 'includes/footer.php'; ?>