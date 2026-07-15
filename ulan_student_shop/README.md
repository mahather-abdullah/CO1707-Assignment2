# CO1707 – Assignment 2 (Server-Side / Back-End)
# MD Mahather Bin Abdullah
# Student ID: G21404460

## Overview
Server-side extension of the UCLan Student Union Shop front-end from Assignment 1.
The site now communicates with a MySQL database via PHP (mysqli, prepared
statements throughout) instead of hardcoded JS arrays or client-only storage.


## Dummy account for marking
| Field | Value |
|---|---|
| Email | `mbates5@uclan.ac.uk` |
| Password |  |

You can also just click **Register** on the site and create a brand-new account —
registration is fully functional and does not require the dummy account.

## Local setup (XAMPP)
1. Copy the `ulan_student_shop` folder into `htdocs`.
2. Start **Apache** and **MySQL** in the XAMPP control panel.
3. In phpMyAdmin, create a database called `ulan_student_shop`.
4. Import `module.sql` into that database (this is the only SQL file that needs
   to be imported).
5. Visit `http://localhost/ulan_student_shop/index.php`.
6. Database credentials are set in `db.php` (default XAMPP: user `root`, empty
   password). Edit this file if your local MySQL setup differs.

## Pages / Files
| Page | File | Description |
|---|---|---|
| Home | `index.php` | Hero banner, offers pulled live from `tbl_offers`, personalised welcome message, About section, videos |
| Products | `products.php` | Product grid built entirely from `tbl_products`; category filter buttons + keyword search (both re-query the database with prepared statements / `LIKE`) |
| Item Detail | `item.php` | Driven by `?id=` GET parameter (no sessionStorage); shows product info, reviews, and average star rating computed from `tbl_reviews`; lets logged-in users add to basket and post a review |
| Cart | `cart.php` | Server-side cart stored in `$_SESSION['cart']`; prices always re-fetched live from the database; checkout creates a real order |
| Login | `login.php` / `login_process.php` | Validates against `tbl_users` using `password_verify()` (bcrypt) |
| Register | `register.php` / `register_process.php` | Creates a new `tbl_users` row with a bcrypt-hashed password |
| 404 | `404.php` | Custom not-found page, wired up via `.htaccess` |

### Supporting scripts
- `auth.php` – session bootstrap, `is_logged_in()`, `require_login()`, cart helpers, `h()` output-escaping helper.
- `db.php` – single mysqli connection, shared by every page.
- `add_to_cart.php` – adds an item to the session cart (login required).
- `update_cart.php` – increases/decreases/removes cart lines, or empties the cart.
- `checkout.php` – writes an order into `tbl_orders` (`user_id`, `product_ids` as `id:qty` pairs) and empties the cart. No payment gateway or payment data is used, per the assignment brief.
- `review_process.php` – validates and inserts a new row into `tbl_reviews`.

## Database
`tbl_products`, `tbl_users`, `tbl_reviews`, `tbl_offers`, `tbl_orders` — schema
and starter data as supplied in `module.sql` .

## Security measures implemented
- **Prepared statements** (`mysqli_prepare` / bound parameters) for every query
  that includes user input — login, register, product filter/search, reviews,
  add-to-cart, checkout.
- **Passwords** hashed and salted with `password_hash(..., PASSWORD_BCRYPT)`
  and verified with `password_verify()`. Raw passwords are never stored.
- **Output escaping** — all database/user-supplied content is passed through
  `htmlspecialchars()` (the `h()` helper) before being echoed, to prevent
  stored/reflected XSS.
- **Server-side validation** on registration (email format, password length),
  reviews (rating range 1–5, required fields), and add-to-cart (product must
  exist, quantity capped and cast to int).
- **Access control** — `require_login()` redirects guests to `login.php`
  (preserving the page they were trying to reach via `?next=`) whenever they
  try to view the cart, add an item, post a review, or check out.
- **Open-redirect protection** — the `next` redirect target after login is
  checked against a safe character allow-list before use.

## Decisions made during development
- The cart was implemented using **PHP sessions** (`$_SESSION['cart']`)
  rather than `localStorage`, so it is tied to the authenticated user and
  matches the brief's hint about using sessions to gate the cart/checkout.
- Cart line prices are always re-read from `tbl_products` at render time
  (never trusted from the session), so price changes in the database are
  reflected immediately.
- `tbl_orders.product_ids` stores each line as `product_id:quantity`,
  comma-separated, since the supplied schema has a single `text` column for
  this rather than a separate order-items table.
- Shared page chrome (header/nav/footer) was factored into
  `includes/header.php` and `includes/footer.php` to avoid duplicating the
  same markup across six pages and to keep navigation links consistent.

## References
- PHP Manual. (2026). *mysqli — Prepared Statements*. https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
- PHP Manual. (2026). *password_hash*. https://www.php.net/manual/en/function.password-hash.php
- W3C Web Accessibility Initiative. (2021). *WCAG 2.1 Quick Reference*. https://www.w3.org/WAI/WCAG21/quickref/
- MDN Web Docs. (2024). *CSS Grid Layout*. https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_grid_layout
