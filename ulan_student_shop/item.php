<?php
require 'auth.php';
require 'db.php';


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: 404.php');
    exit;
}

$stmt = mysqli_prepare($connection, "SELECT product_id, product_title, product_desc, product_image, product_price, product_type FROM tbl_products WHERE product_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$product = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$product) {
    header('Location: 404.php');
    exit;
}


$rstmt = mysqli_prepare($connection,
    "SELECT r.review_id, r.review_title, r.review_desc, r.review_rating, r.review_timestamp, u.user_full_name
     FROM tbl_reviews r
     JOIN tbl_users u ON u.user_id = r.user_id
     WHERE r.product_id = ?
     ORDER BY r.review_timestamp DESC");
mysqli_stmt_bind_param($rstmt, 'i', $id);
mysqli_stmt_execute($rstmt);
$reviews = mysqli_stmt_get_result($rstmt)->fetch_all(MYSQLI_ASSOC);

$avgRating  = 0;
$reviewCount = count($reviews);
if ($reviewCount > 0) {
    $sum = 0;
    foreach ($reviews as $r) { $sum += (int)$r['review_rating']; }
    $avgRating = round($sum / $reviewCount, 1);
}

$added = isset($_GET['added']);

$pageTitle  = h($product['product_title']) . ' | UCLan Student Union Shop';
$pageDesc   = 'Product detail – UCLan Student Union Shop';
$activePage = 'products';
require 'includes/header.php';
?>

    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol class="breadcrumb-list" role="list">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
        <li><span aria-current="page"><?php echo h($product['product_title']); ?></span></li>
      </ol>
    </nav>

    <?php if ($added): ?>
      <div class="alert alert-success" role="alert"><strong>&#9989; Added to basket!</strong> <a href="cart.php">View your cart &rarr;</a></div>
    <?php endif; ?>

    <section id="item-section" aria-labelledby="item-heading">
      <div class="item-layout">
        <div class="item-img-box">
          <img src="<?php echo h($product['product_image']); ?>" alt="<?php echo h($product['product_title']); ?>" width="500" height="500">
        </div>
        <div class="item-info">
          <p class="tag"><?php echo h($product['product_type']); ?></p>
          <h1 id="item-heading"><?php echo h($product['product_title']); ?></h1>
          <p class="item-price">&pound;<?php echo number_format((float)$product['product_price'], 2); ?></p>

          <p class="item-rating" aria-label="Average rating <?php echo $avgRating; ?> out of 5 from <?php echo $reviewCount; ?> reviews">
            <?php if ($reviewCount > 0): ?>
              <span class="stars" aria-hidden="true"><?php echo str_repeat('&#9733;', (int)round($avgRating)) . str_repeat('&#9734;', 5 - (int)round($avgRating)); ?></span>
              <strong><?php echo $avgRating; ?></strong> / 5 (<?php echo $reviewCount; ?> review<?php echo $reviewCount !== 1 ? 's' : ''; ?>)
            <?php else: ?>
              <span style="color:var(--dgrey);">No reviews yet — be the first!</span>
            <?php endif; ?>
          </p>

          <p class="item-desc"><?php echo h($product['product_desc']); ?></p>
          <dl class="item-meta">
            <dt>Type</dt><dd><?php echo h($product['product_type']); ?></dd>
            <dt>Price</dt><dd>&pound;<?php echo number_format((float)$product['product_price'], 2); ?></dd>
          </dl>

          <?php if (is_logged_in()): ?>
            <form action="add_to_cart.php" method="post" class="qty-row" style="flex-wrap:wrap;">
              <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
              <label for="qty-select">Quantity:</label>
              <select id="qty-select" name="qty" class="qty-select">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
              </select>
              <button type="submit" class="btn btn-gold">&#128722; Add to Basket</button>
              <a href="cart.php" class="btn btn-outline">View Cart</a>
            </form>
          <?php else: ?>
            <div class="alert alert-info">
              Please <a href="login.php?next=item.php%3Fid%3D<?php echo (int)$product['product_id']; ?>">log in</a> to add this item to your basket.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section aria-labelledby="reviews-heading" id="reviews" style="margin-top:var(--s40);">
      <h2 id="reviews-heading" class="section-heading">Reviews</h2>
      <hr class="section-divider" aria-hidden="true">

      <?php if ($reviewCount === 0): ?>
        <p style="color:var(--dgrey);margin-bottom:var(--s24);">This product has no reviews yet.</p>
      <?php else: ?>
        <div class="review-list">
          <?php foreach ($reviews as $r): ?>
            <article class="review-card">
              <div class="review-head">
                <span class="stars" aria-hidden="true"><?php echo str_repeat('&#9733;', (int)$r['review_rating']) . str_repeat('&#9734;', 5 - (int)$r['review_rating']); ?></span>
                <strong><?php echo h($r['review_title']); ?></strong>
              </div>
              <p class="review-meta">by <?php echo h($r['user_full_name']); ?> &middot; <?php echo date('d M Y', strtotime($r['review_timestamp'])); ?></p>
              <p><?php echo nl2br(h($r['review_desc'])); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (is_logged_in()): ?>
        <h3 style="margin:var(--s24) 0 var(--s16);">Leave a review</h3>
        <form action="review_process.php" method="post" class="review-form">
          <input type="hidden" name="product_id" value="<?php echo (int)$product['product_id']; ?>">
          <label for="rt">Title</label>
          <input type="text" id="rt" name="review_title" maxlength="255" required>

          <label for="rr">Rating</label>
          <select id="rr" name="review_rating" required>
            <option value="">Select a rating…</option>
            <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; — 5</option>
            <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734; — 4</option>
            <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734; — 3</option>
            <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734; — 2</option>
            <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734; — 1</option>
          </select>

          <label for="rd">Your review</label>
          <textarea id="rd" name="review_desc" rows="4" required></textarea>

          <button type="submit" class="btn btn-blue">Submit review</button>
        </form>
      <?php else: ?>
        <p><a href="login.php?next=item.php%3Fid%3D<?php echo (int)$product['product_id']; ?>">Log in</a> to leave a review.</p>
      <?php endif; ?>
    </section>

    <aside aria-label="Continue shopping" style="margin-top:var(--s40)">
      <div class="alert alert-info">
        <a href="products.php">&#8592; Back to all products</a>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <a href="cart.php">View your cart &#8594;</a>
      </div>
    </aside>

<?php require 'includes/footer.php'; ?>
