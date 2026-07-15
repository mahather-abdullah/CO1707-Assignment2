<?php
require 'auth.php';
require 'db.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = $_GET['error'] ?? null;

$pageTitle = 'Register | UCLan Student Union Shop';
$pageDesc  = 'Create an account with the UCLan Student Union Shop.';
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
            <p>Create an account to check out faster and leave product reviews.</p>
          </div>
        </div>

        <div class="split-auth-form">
          <h1 id="register-heading">User Registration</h1>

          <?php if ($error === 'exists'): ?>
            <div class="auth-alert auth-alert--error" role="alert">&#10060; An account with that email already exists. Please <a href="login.php">log in</a> instead.</div>
          <?php elseif ($error === 'password'): ?>
            <div class="auth-alert auth-alert--error" role="alert">&#10060; Password must be at least 8 characters long.</div>
          <?php elseif ($error === 'fields'): ?>
            <div class="auth-alert auth-alert--error" role="alert">&#10060; Please fill in all fields with a valid email address.</div>
          <?php endif; ?>

          <form action="register_process.php" method="post" class="auth-form">
            <div class="field field-icon">
              <label for="fullname" class="sr-only">Full Name</label>
              <span class="icon">&#128100;</span>
              <input type="text" id="fullname" name="fullname" required
                     autocomplete="name" placeholder="Full name">
            </div>

            <div class="field field-icon">
              <label for="address" class="sr-only">Address</label>
              <span class="icon">&#127968;</span>
              <input type="text" id="address" name="address" required
                     autocomplete="street-address" placeholder="Address">
            </div>

            <div class="field field-icon">
              <label for="email" class="sr-only">Email</label>
              <span class="icon">&#9993;</span>
              <input type="email" id="email" name="email" required
                     autocomplete="email" placeholder="Email address">
            </div>

            <div class="field field-icon field-password">
              <label for="password" class="sr-only">Password</label>
              <span class="icon">&#128274;</span>
              <input type="password" id="password" name="password" required minlength="8"
                     autocomplete="new-password" placeholder="Password" aria-describedby="pw-hint">
             
            </div>
            <p id="pw-hint" class="field-hint" style="margin-top:-8px;">Minimum 8 characters.</p>

            <button type="submit" class="auth-submit-pill">Register</button>
          </form>

          <div class="split-auth-links">
            <a href="login.php">Already have an account? Login here &rarr;</a>
          </div>
        </div>

      </div>
    </div>

<?php require 'includes/footer.php'; ?>