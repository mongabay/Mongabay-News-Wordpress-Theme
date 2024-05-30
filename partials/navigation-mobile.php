<nav class="navbar navbar-toggleable-md navbar-light" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
	<button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarMobileMenu">
		<div class="row">
			<div class="col-6">
				<ul>
					<strong><?php _e('News sections', 'mongabay'); ?></strong>
					<?php mongabay_menu_items(); ?>
				</ul>
			</div>
            <?php if(mongabay_subdomain_name()!=='kidsnews') {;?>
			<div class="col-6">
				<ul><strong><?php _e('Language', 'mongabay'); ?></strong>
					<li class="nav-item">
						<a class="nav-link" href="https://news.mongabaydev.wpengine.com/">English</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://es.mongabaydev.wpengine.com/">Español (Spanish)</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://fr.mongabaydev.wpengine.com/">Français (French)</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://www.mongabay.co.id/">Bahasa Indonesia (Indonesian)</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://brasil.mongabaydev.wpengine.com/">Brasil (Portuguese)</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://india.mongabaydev.wpengine.com/">India</a><a class="nav-link" href="https://hindi.mongabaydev.wpengine.com/"> (हिंदी)</a>
					</li>
				</ul>
			</div>
            <?php };?>
		</div>
	</div>
</nav>