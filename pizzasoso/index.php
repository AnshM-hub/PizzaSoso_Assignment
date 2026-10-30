<?php require 'includes/bootstrap.php'; $title = 'Fresh Pizza, Delivered Hot';
$pop = $pdo->query("SELECT * FROM pizza WHERE is_active=1 ORDER BY pizza_id LIMIT 4")->fetchAll();
include 'includes/header.php'; ?>
<section class="hero"><div class="wrap two"><div><span class="pill">YOUR CRAVING, OUR COMMAND</span>
<h1>Fresh Pizza,<br><em>Delivered Hot</em> to<br>Your Door</h1>
<p class="lead">Artisan slow-rising dough, lush vine-ripened tomatoes, and exceptional local ingredients. Served oven-hot for pickup or swift home delivery.</p>
<a class="btn" href="menu.php">Order Delivery</a> <a class="btn ghost" href="menu.php">Carry Out</a>
<div class="stats"><div><b class="red">24 Min</b><small>Average delivery time</small></div><div><b class="amber">4.8 Stars</b><small>From 2,000+ local reviews</small></div></div></div>
<div class="heroimg"><img src="assets/img/pizza.svg" alt="Pepperoni pizza"></div></div></section>
<section class="sec"><div class="wrap center"><span class="pill">POPULAR PIZZAS</span><h2>Our Customers' Absolute Favorites</h2>
<p class="sub">Check out what's flying out of our stone ovens today. Hand-stretched and baked to perfect, crispy perfection.</p>
<div class="grid4"><?php foreach ($pop as $p) pizza_card($p); ?></div></div></section>
<section class="sec cream" id="deals"><div class="wrap center"><span class="pill">SPECIAL PROMOTIONS</span><h2>Unbeatable Oven-Hot Pizza Deals</h2>
<div class="grid3"><div class="deal"><small class="red">WEEKEND SAVER</small><h3>Family Feast Combo</h3><p>2 Large Pizzas, 1 Garlic Bread, plus a 2-Liter soda.</p><b>Rs 800</b><br><a class="btn" href="menu.php">Get Deal</a></div></div></div></section>
<section class="sec"><div class="wrap center"><span class="pill">OVEN TO DOORSTEP</span><h2>How to Get Your Pizza Fix</h2>
<div class="grid3 steps"><div><h4>1. Browse Our Menu</h4><p>Explore our selection of specialty pizzas, craft toppings, starters, drinks, and fresh baked desserts.</p></div>
<div><h4>2. Place Your Order</h4><p>Select Delivery or Carry-out. 100% secure checkout with transparent pricing and zero surprise fees.</p></div>
<div><h4>3. Eat Hot &amp; Fresh</h4><p>Track your pizza from oven to your doorstep. Our delivery drivers keep your pie flat and piping hot.</p></div></div></div></section>
<section class="sec cream" id="delivery"><div class="wrap two"><div class="mapbox">Delivery map</div><div><span class="pill">DELIVERY ZONE</span><h2>Hot Pizza to Most of the City</h2>
<p class="lead">We deliver 20Km radius within of our downtown kitchen, guaranteeing absolute oven-fresh quality.</p>
<ul class="ticks"><li><b>Estimated Time:</b> 20 - 35 minutes depending on traffic</li><li><b>Minimum Order:</b> Free delivery on a purchase of 500rs</li><li><b>Delivery Hours:</b> Till 11:00 AM - 11:00 PM</li></ul>
<a class="btn" href="checkout.php">Check Your Address</a></div></div></section>
<section class="sec"><div class="wrap center"><span class="pill">LOCAL LOVE</span><h2 class="red">Reviews &amp; Ratings</h2><div class="grid3 revs">
<div class="rev"><p>"Hands down the best crust in town. It actually stays crispy even after a 20-minute delivery. The Hot Pepperoni with honey drizzle is a weekly ritual in our house."</p><b>Gerard Le diable</b><small>Verified Local Guide</small></div>
<div class="rev"><p>"I'm extremely picky about San Marzano sauce, but Pizza Soso nailed it. It has that perfectly balanced sweet and tangy flavor. Plus, delivery arrived in just 22 minutes!"</p><b>Rehana Bibi Mungly-Guibul</b><small>Verified Buyer</small></div>
<div class="rev"><p>"Ordering online was incredibly easy. Customizing half-and-half toppings worked seamlessly, and the delivery driver was extremely friendly. Highly recommend!"</p><b>Luck Elmia</b><small>Regular Customer</small></div></div></div></section>
<section class="cta"><div class="wrap row"><div><h3>Craving Something Delicious?</h3><p>Get your custom pizza crafted and sent to the stone oven in less than 3 minutes.</p></div><a class="btn white" href="menu.php">Order Delivery</a></div></section>
<?php include 'includes/footer.php'; ?>
