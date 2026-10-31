# PizzaSoso_Assignment
Online pizza ordering web app built with PHP and MySQL for ICT2213(3) at the University of Mauritius.
## Team
| Member | Student ID || Main area |
|---|---|---|---|
| Bundhoo Mohamed Zubair | 2514768 | Registration, login, sessions, access control |
| Shibnauth Dishav | 2516105 | Menu, search and filter |
| Boodhun Bibi Zahrah | 2516012 | Pizza customiser and cart |
| Muhun Sarveshsing | 2514878 | Checkout, payment, confirmation |
| Rajoo Yashveer | 2514771 | Order tracking, reviews, staff dashboard |
| Akbur Bibi Zakiyya | 2514729 | Admin menu management, noindex / robots.txt |

## Links
- Figma design: https://www.figma.com/design/t0aryoA3yY1AcqA8pw3hmX/Pizzasoso?node-id=2-27&t=SoJWSQFxUNR4B9q2-1
- Project video and slides: 
  
## Technology
HTML5, CSS, JavaScript, PHP 8 (PDO) and MySQL/MariaDB, run locally with XAMPP.

## Folder structure
```
pizza-soso/   (local folder name; repository is PizzaSoso_Assignment)
├── README.md
├── robots.txt            (copy to the web root, e.g. htdocs/robots.txt)
├── index.php, login.php, register.php, logout.php
├── config/               database settings (db.php is not committed)
├── includes/             shared PHP: session, auth, header, helper functions
├── customer/             menu, customise, cart, checkout, orders, reviews
├── staff/                order dashboard
├── admin/                menu management
├── assets/               css, js, images
├── database/             pizza_ordering_db.sql
└── docs/                 spec, ERD, diagrams
```

## Who built what
| Member | Branch | Files |
|---|---|---|
| Bundhoo Mohamed Zubair | `feature/auth` | includes/session.php, includes/auth.php, register.php, login.php, logout.php |
| Shibnauth Dishav | `feature/menu` | customer/menu.php, assets/js/menu.js |
| Boodhun Bibi Zahrah | `feature/customise-cart` | customer/customise.php, customer/cart.php, includes/pricing.php, includes/cart_functions.php |
| Muhun Sarveshsing | `feature/checkout` | customer/checkout.php, customer/confirmation.php |
| Rajoo Yashveer | `feature/orders-reviews-staff` | customer/orders.php, customer/review.php, staff/orders.php |
| Akbur Bibi Zakiyya | `feature/admin` | admin/menu.php, robots.txt |

## Feature status
The status of each functional requirement (HTML, CSS, client-side validation, PHP
validation, database operations) is in the project PowerPoint. Files containing `TODO`
comments are still **in progress**. Features with no files yet are **not started**.

## Security measures
- Passwords hashed with `password_hash()` and checked with `password_verify()`
- Prepared statements (PDO) for every database query, against SQL injection
- Output encoding with `htmlspecialchars()` on all displayed data, against XSS
- CSRF tokens on all forms that change data
- Client-side (HTML5 and JavaScript) and server-side (PHP) validation on every form
- Role-based access control (Customer, Staff, Admin) checked on every protected page
- Sessions expire after 15 minutes of inactivity, with HttpOnly and SameSite cookies
- Pages hidden from search engines: `X-Robots-Tag` header, `noindex` meta tag and `robots.txt`
- `config/` and `includes/` blocked from direct web access with `.htaccess`

## Notes
- Card payment is **simulated**: no card number is collected or stored.
- Mobile money, inventory management, promotions and audit logging are not part of this
  mini project submission.
