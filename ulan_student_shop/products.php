<?php
require 'auth.php';
require 'db.php';


$validTypes = ['UCLan Hoodie', 'UCLan Logo Tshirt', 'UCLan Logo Jumper'];
$type   = $_GET['type'] ?? 'all';
$search = trim($_GET['q'] ?? '');

$where  = [];
$params = [];
$types  = '';

if (in_array($type, $validTypes, true)) {
    $where[]  = 'product_type = ?';
    $params[] = $type;
    $types   .= 's';
}

if ($search !== '') {
 
    $where[]  = '(product_title LIKE ? OR product_desc LIKE ?)';
    $like     = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
    $types   .= 'ss';
}

$sql = "SELECT product_id, product_title, product_desc, product_image, product_price, product_type FROM tbl_products";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY product_type, product_title";

$stmt = mysqli_prepare($connection, $sql);
if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

$pageTitle  = 'Products | UCLan Student Union Shop';
$pageDesc   = 'Browse official UCLan hoodies, t-shirts and jumpers.';
$activePage = 'products';
require 'includes/header.php';
?>

    <nav class="breadcrumb" aria-label="Breadcrumb">
      <ol class="breadcrumb-list" role="list">
        <li><a href="index.php">Home</a></li>
        <li><span aria-current="page">Products</span></li>
      </ol>
    </nav>

    <section aria-labelledby="prod-heading">
      <h1 id="prod-heading" class="section-heading">UCLan Merchandise</h1>
      <hr class="section-divider" aria-hidden="true">

      <form method="get" action="products.php" class="search-form" role="search">
        <label for="q" class="sr-only">Search products</label>
        <input type="text" id="q" name="q" placeholder="Search products…" value="<?php echo h($search); ?>">
        <button type="submit" class="btn btn-blue btn-sm">Search</button>
        <?php if ($search !== ''): ?>
          <a href="products.php<?php echo $type !== 'all' ? '?type=' . urlencode($type) : ''; ?>" class="btn btn-outline btn-sm">Clear search</a>
        <?php endif; ?>
      </form>

      <div class="filter-bar" role="group" aria-label="Filter by product type">
        <label>Filter:</label>
        <a href="products.php?<?php echo http_build_query(array_filter(['type' => 'all', 'q' => $search ?: null])); ?>"
           class="f-btn<?php echo $type === 'all' ? ' active' : ''; ?>" aria-pressed="<?php echo $type === 'all' ? 'true' : 'false'; ?>">All</a>
        <a href="products.php?<?php echo http_build_query(array_filter(['type' => 'UCLan Logo Tshirt', 'q' => $search ?: null])); ?>"
           class="f-btn<?php echo $type === 'UCLan Logo Tshirt' ? ' active' : ''; ?>" aria-pressed="<?php echo $type === 'UCLan Logo Tshirt' ? 'true' : 'false'; ?>">T-Shirts</a>
        <a href="products.php?<?php echo http_build_query(array_filter(['type' => 'UCLan Logo Jumper', 'q' => $search ?: null])); ?>"
           class="f-btn<?php echo $type === 'UCLan Logo Jumper' ? ' active' : ''; ?>" aria-pressed="<?php echo $type === 'UCLan Logo Jumper' ? 'true' : 'false'; ?>">Jumpers</a>
        <a href="products.php?<?php echo http_build_query(array_filter(['type' => 'UCLan Hoodie', 'q' => $search ?: null])); ?>"
           class="f-btn<?php echo $type === 'UCLan Hoodie' ? ' active' : ''; ?>" aria-pressed="<?php echo $type === 'UCLan Hoodie' ? 'true' : 'false'; ?>">Hoodies</a>
      </div>

      <p id="results-count" aria-live="polite" aria-atomic="true" style="font-size:.82rem; color:var(--dgrey); margin-bottom:var(--s24);">
        Showing <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?><?php echo $search !== '' ? ' for "' . h($search) . '"' : ''; ?>.
      </p>

      <div class="product-grid" id="product-grid" role="list" aria-label="Products">
        <?php if (empty($products)): ?>
          <p style="grid-column:1/-1;text-align:center;padding:40px 0;color:var(--dgrey)">No products match your search or filter.</p>
        <?php endif; ?>

        <?php foreach ($products as $p): ?>
          <article class="pcard" role="listitem" aria-label="<?php echo h($p['product_title']); ?>">
            <div class="pcard-img-wrap">
              <img class="pcard-img" src="<?php echo h($p['product_image']); ?>" alt="<?php echo h($p['product_title']); ?>" width="400" height="400" loading="lazy">
            </div>
            <div class="pcard-body">
              <p class="pcard-colour"><?php echo h($p['product_type']); ?></p>
              <h2 class="pcard-name"><?php echo h($p['product_title']); ?></h2>
              <p class="pcard-desc"><?php echo h($p['product_desc']); ?></p>
              <div class="pcard-foot">
                <span class="pcard-price">&pound;<?php echo number_format((float)$p['product_price'], 2); ?></span>
                <a class="btn btn-blue btn-sm" href="item.php?id=<?php echo (int)$p['product_id']; ?>">Read more</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

<?php require 'includes/footer.php'; ?>
