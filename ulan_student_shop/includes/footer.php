  </main>

  <footer role="contentinfo">
    <div class="footer-inner">
      <section class="footer-section" aria-labelledby="f1">
        <h2 id="f1">UCLan Student Union Shop</h2>
        <p>Official UCLan Student Union merchandise store.</p>
      </section>
      <section class="footer-section" aria-labelledby="f2">
        <h2 id="f2">Navigation</h2>
        <nav aria-label="Footer navigation">
          <ul role="list">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
          </ul>
        </nav>
      </section>
      <section class="footer-section" aria-labelledby="f3">
        <h2 id="f3">Contact</h2>
        <p>Students' Union Building<br>University of Lancashire<br>Preston, PR1 2HE<br>
        <a href="mailto:su@lancashire.ac.uk">su@lancashire.ac.uk</a></p>
      </section>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 University of Lancashire Students' Union. All rights reserved.</p>
      
    </div>
  </footer>

  <script>
    'use strict';
    var hb = document.getElementById('hamburger-btn');
    var nl = document.getElementById('nav-links');
    if (hb && nl) {
      hb.addEventListener('click', function () {
        var open = nl.classList.toggle('is-open');
        hb.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    }
  </script>
</body>
</html>
