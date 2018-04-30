<!-- this page contains the product listings, ie. all the reds or all the rollers -->
<?php 
	$family = FamilyPage::create_fromobject($page);
	$paginator = new Paginator($input->pageNum, $page->children->count, $page->fullURL->getUrl(), $page->name); 
	$limit = $session->display;
?>
<?php include('./_head.php'); ?>
	<div class="container page">
		<ol class="breadcrumb">
			<?php foreach ($family->generate_breadcrumbs() as $crumb) : ?>
				<li><a href="<?= $crumb->url; ?>"><?= $crumb->title; ?></a></li>
			<?php endforeach; ?>
		  	<li><?php echo $family->title; ?></li>
		</ol>
		
		<?= $paginator->generate_showonpage(); ?>
			<div class="row space">
			<?php foreach ($family->get_familylinks($limit) as $child) : ?>
				<?php $product = $pages->get($child['id']); ?>
				<div class="col-xs-6 col-md-3 form-group">
					<a href="<?php echo $product->url; ?>">
						<img class="img-responsive" src="<?php echo $product->product_image->height(300)->url; ?>" alt="<?php echo $product->title; ?>">
					</a>
					<h4><a href="<?php echo $product->url; ?>"><?php echo $product->title; ?></a></h4>
					<p>Model: <?php echo $product->itemid; ?></p>
					<p class="text-right"><span class="price">$<?php echo $product->price; ?></span></p>
					<a href="<?php echo $product->url; ?>" class="btn btn-info btn-block">See more</a>
				</div>
			<?php endforeach; ?>
			</div>
		<?= $paginator; ?>
	</div>
<?php include('./_foot.php'); // include footer markup ?>
