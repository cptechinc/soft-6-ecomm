<?php include('./_head.php'); ?>

	<div class="container page">
		<div class="product-container">
			<div class="row">
				<div class="col-md-12">
					<h1><a href="#">Paint</a> > <a href="#" class="product-category">Colors</a> > <?php echo $page->product_title; ?></h1>
				</div>
			</div>
			<div class="row">
			  <div class="col-md-6">
	        <img class="img-responsive" src="<?php echo $page->product_image->url; ?>" alt="">
	        <p><?php echo $page->product_description; ?></p>
			  </div>
	      <div class="col-md-6">
					<h2>Rating</h2>
					<p><i class="fa fa-star fa-2x" aria-hidden="true"></i>&nbsp;<i class="fa fa-star fa-2x" aria-hidden="true"></i>&nbsp;<i class="fa fa-star fa-2x" aria-hidden="true"></i>&nbsp;<i class="fa fa-star fa-2x" aria-hidden="true"></i>&nbsp;<i class="fa fa-star-o fa-2x" aria-hidden="true"></i></p>
	        <h2>Features</h2>
	        <p><?php echo $page->product_features; ?></p>
	        <h2>Specifications</h2>
	        <p><?php echo $page->product_specifications; ?></p>
					<h3 class="price">$<?php echo $page->price; ?></h3>
					<form class="" action="index.html" method="post">
						<label for="quantity">Quantity:</label>
						<input class="quantity" type="text" name="quantity" value="" size="3"><br>
						<button class="btn btn-info add_to_cart" type="button" name="add_to_cart">Add to Cart</button>
					</form>
	      </div>
			</div>
		</div>
		<!-- END PRODUCT CONTAINER -->
		<div class="related-products">
			<div class="row">
				<div class="col-md-12">
					<h3>Related Products</h3>
				</div>
			</div>
			<div class="row">
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $page->product_image->height(300)->url ?>" alt="">
						<h4>Product 1</h4>
						<p><?php echo $page->product_features; ?></p>
						<h4 class="price">$<?php echo $page->price; ?></h4>
						<button class="btn btn-info" type="button" name="button">See more...</button>
					</div>
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $page->product_image->height(300)->url ?>" alt="">
						<h4>Product 1</h4>
						<p><?php echo $page->product_features; ?></p>
						<h4 class="price">$<?php echo $page->price; ?></h4>
						<button class="btn btn-info" type="button" name="button">See more...</button>
					</div>
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $page->product_image->height(300)->url ?>" alt="">
						<h4>Product 1</h4>
						<p><?php echo $page->product_features; ?></p>
						<h4 class="price">$<?php echo $page->price; ?></h4>
						<button class="btn btn-info" type="button" name="button">See more...</button>
					</div>
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo $page->product_image->height(300)->url ?>" alt="">
						<h4>Product 1</h4>
						<p><?php echo $page->product_features; ?></p>
						<h4 class="price">$<?php echo $page->price; ?></h4>
						<button class="btn btn-info" type="button" name="button">See more...</button>
					</div>
			</div>
		</div>
		<!-- END RELATED PRODUCTS -->
</div>

<?php include('./_foot.php'); // include footer markup ?>
