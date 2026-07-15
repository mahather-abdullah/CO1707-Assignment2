<?php
require 'auth.php';
require 'db.php';
require_login();
cart_init();


$items = [];
$grandTotal = 0.0;

if (!empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = mysqli_prepare($connection, "SELECT product_id, product_title, product_image, product_price FROM tbl_products WHERE product_id IN ($placeholders)");
    mysqli_stmt_bind_param($stmt, $types, ...$ids);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    foreach ($rows as $row) {
        $pid  = (int)$row['product_id'];
        $qty  = (int)$_SESSION['cart'][$pid];
        $line = $qty * (float)$row['product_price'];
        $grandTotal += $line;
        $items[] = [
            'id'    => $pid,
            'title' => $row['product_title'],
            'image' => $row['product_image'],
            'price' => (float)$row['product_price'],
            'qty'   => $qty,
            'line'  => $line,
        ];
    }
}

$ordered = $_GET['ordered'] ?? null;

// Apply promo code discount to the cart total.
$promoCode      = $_SESSION['promo_code'] ?? null;
$promoCodes     = valid_promo_codes();
$discountPct    = ($promoCode && isset($promoCodes[$promoCode])) ? $promoCodes[$promoCode] : 0;
$discountAmount = $grandTotal * ($discountPct / 100);
$finalTotal     = $grandTotal - $discountAmount;
$promoError     = $_SESSION['promo_error'] ?? null;
unset($_SESSION['promo_error']);

$pageTitle  = 'Cart | UCLan Student Union Shop';
$pageDesc   = 'Your shopping cart – UCLan Student Union Shop';
$activePage = 'cart';
require 'includes/header.php';
?>

    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol class="breadcrumb-list" role="list">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><span aria-current="page">Cart</span></li>
      </ol>
    </nav>

    <h1 class="section-heading">Your Shopping Cart</h1>
    <hr class="section-divider" aria-hidden="true">

    <?php if ($ordered === '1'): ?>
      <div class="alert alert-success" role="alert" aria-live="polite">
        <strong>&#127881; Thank you for your order!</strong> Your order has been placed successfully.
      </div>
    <?php elseif ($ordered === '0'): ?>
      <div class="alert alert-info" role="alert">Something went wrong placing your order. Please try again.</div>
    <?php endif; ?>

    <div class="cart-layout">
      <section aria-labelledby="ci-heading">
        <h2 id="ci-heading" class="sr-only">Cart Items</h2>
        <div class="cart-list" id="cart-list">
          <?php if (empty($items)): ?>
            <div class="empty-cart" role="status">
              <p style="font-size:2rem;margin-bottom:8px">&#128722;</p>
              <p style="margin-bottom:var(--s24)">Your cart is empty.</p>
              <a href="products.php" class="btn btn-blue">Start Shopping</a>
            </div>
          <?php else: ?>
            <?php foreach ($items as $item): ?>
              <article class="cart-card" aria-label="<?php echo h($item['title']); ?>">
                <img src="<?php echo h($item['image']); ?>" alt="<?php echo h($item['title']); ?>" width="80" height="80" loading="lazy">
                <div>
                  <h2><a href="item.php?id=<?php echo $item['id']; ?>"><?php echo h($item['title']); ?></a></h2>
                  <p class="each">&pound;<?php echo number_format($item['price'], 2); ?> each</p>
                  <div class="cart-qty">
                    <form action="update_cart.php" method="post" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                      <input type="hidden" name="action" value="decrease">
                      <button type="submit" aria-label="Decrease quantity">&#8722;</button>
                    </form>
                    <span aria-live="polite"><?php echo $item['qty']; ?></span>
                    <form action="update_cart.php" method="post" style="display:inline;">
                      <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                      <input type="hidden" name="action" value="increase">
                      <button type="submit" aria-label="Increase quantity">&#43;</button>
                    </form>
                  </div>
                </div>
                <div class="cart-actions">
                  <span class="cart-subtotal">&pound;<?php echo number_format($item['line'], 2); ?></span>
                  <form action="update_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="btn btn-red btn-sm" aria-label="Remove item">Remove</button>
                  </form>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

      <aside aria-label="Order summary">
        <div class="cart-summary">
          <h2>Order Summary</h2>

          <div class="sum-row"><span>Subtotal</span><span>&pound;<?php echo number_format($grandTotal, 2); ?></span></div>

          <?php if ($discountPct > 0): ?>
            <div class="sum-row disc"><span>Promo (<?php echo h($promoCode); ?> &ndash; <?php echo $discountPct; ?>% off)</span><span>&minus;&pound;<?php echo number_format($discountAmount, 2); ?></span></div>
          <?php endif; ?>

          <div class="sum-row total"><span>Total</span><span>&pound;<?php echo number_format($finalTotal, 2); ?></span></div>

          <div class="promo-box">
            <?php if ($promoCode): ?>
              <p class="promo-applied" role="status">&#10003; Code <strong><?php echo h($promoCode); ?></strong> applied</p>
              <form action="update_cart.php" method="post">
                <input type="hidden" name="action" value="remove_promo">
                <button type="submit" class="btn btn-outline btn-sm btn-full">Remove promo code</button>
              </form>
            <?php else: ?>
              <form action="update_cart.php" method="post" class="promo-form">
                <input type="hidden" name="action" value="apply_promo">
                <label for="promo_code" class="promo-label">Promo code</label>
                <div class="promo-input-row">
                  <input type="text" id="promo_code" name="promo_code" placeholder="Enter code" autocomplete="off">
                  <button type="submit" class="btn btn-blue btn-sm">Apply</button>
                </div>
              </form>
              <?php if ($promoError): ?>
                <p class="promo-error" role="alert"><?php echo h($promoError); ?></p>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <form action="checkout.php" method="post">
            <button type="submit" class="btn btn-blue btn-full" <?php echo empty($items) ? 'disabled' : ''; ?>>Proceed to Checkout</button>
          </form>
          <a href="products.php" class="btn btn-outline btn-full" style="text-align:center;">Continue Shopping</a>
          <form action="update_cart.php" method="post">
            <input type="hidden" name="action" value="empty">
            <button type="submit" class="btn btn-red btn-sm btn-full" <?php echo empty($items) ? 'disabled' : ''; ?>>&#128465; Empty Cart</button>
          </form>
        </div>
      </aside>
    </div>

<?php require 'includes/footer.php'; ?>
