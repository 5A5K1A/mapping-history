<?php
/**
 * @package WordPress Theme by Occhio Web Development
 * @subpackage Mapping History theme created by Occhio Web Development
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<title><?php wp_title('', true, 'right'); ?></title>
		<meta name="viewport" content="width=device-width, user-scalable=no" />
		<meta name="description" content="<?php bloginfo('description'); ?>" />
		<?php get_template_part('head', 'icons'); ?>

		<!-- Chrome, Firefox OS and Opera -->
		<meta name="theme-color" content="<?php echo BASE_COLOR; ?>">
		<!-- Windows Phone -->
		<meta name="msapplication-navbutton-color" content="<?php echo BASE_COLOR; ?>">
		<!-- iOS Safari -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="default">

		<!-- W3TC-include-js-head -->
		<?php wp_head(); ?>
		<!--[if lt IE 9]>
		<script type="text/javascript" src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		<!--
		This website/webapplication is developed by:
		Occhio
		https://www.occhio.nl/
		info@occhio.nl
		+31 (0)20 320 78 70
	-->

	</head>
	<body <?php body_class(); ?>>
		<!-- google maps app -->
		<div id="maphist_app"></div>

		<!-- sidebar -->
		<div class="swipe">
			<div class="swipe-area"></div>
			<a href="#" data-toggle=".swipe" id="sidebar-toggle">
				<svg alt="sidebar" id="sidebar-icon" xmlns="http://www.w3.org/2000/svg" width="1.1em" height="1.1em" viewBox="0 0 6 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><path d="M0 0L0 24 12.9 11.7 0 0" fill="#000000"/></g></svg>
			</a>

			<!-- added class temporarily	 -->
			<div class="container">
				<header class="site-header">
					<nav class="navbar navbar-default navbar-static-top" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="sr-only">Toggle navigation</span>
							</button>
							<div class="navbar-brand">
								<a class="logo" href="<?php echo home_url(); ?>" title="<?php bloginfo('description'); ?>">
									<?php bloginfo('name'); ?>
								</a>
							</div>
						</div>
						<div class="collapse navbar-collapse" id="navigation" data-toggle="collapse" data-target="#navigation">
							<?php Template::Render('snippet-menu-theme.php'); ?>
						</div>
					</nav>
					<section class="bottom-main-nav">
						<form class="search-address">
							<input id="search" placeholder="Zoek op adres" name="address"/>
							<button type="submit" title="Zoeken">
								<svg alt="search" class="icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="1.1em" height="1.1em" viewBox="0 0 483.1 483.1" xml:space="preserve" class="detail convertSvgInline replaced-svg" width="1.1em" height="1.1em" data-id="70490" data-kw="magnifying-glass34" fill="#000000"><path d="M332.7 315.4c30.9-33.4 50.2-78.2 50.2-127.5C382.9 84.4 298.7 0 195 0S7.2 84.4 7.2 187.9 91.3 375.7 195 375.7c42.2 0 81-13.9 112.5-37.4l139.7 139.7c3.4 3.4 7.7 5.1 11.9 5.1s8.8-1.7 11.9-5.1c6.5-6.5 6.5-17.3 0-24.1L332.7 315.4zM41.2 187.9C41.2 103.1 110 34 195 34c84.7 0 153.9 68.9 153.9 153.9S280 341.7 195 341.7 41.2 272.6 41.2 187.9z" fill=""/></svg>
							</button>
						</form>
						<div class="options">
							<!-- mobile -->
							<!-- mobile back -->
							<span class="back mobile-back icon-map-active">
								<svg class="back-link" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="1.1em" height="1.1em" viewBox="0 0 408 408" xml:space="preserve" enable-background="new 0 0 408 408">
									<g>
										<g id="arrow-back">
											<path d="M408 178.5H96.9L239.7 35.7 204 0 0 204l204 204 35.7-35.7L96.9 229.5H408V178.5z"/>
										</g>
									</g>
								</svg>
							</span>
							<button class="icon-map-inactive map mobile" title="Map">
								Kaart
								<!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" width="1.8em" height="1.8em" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
	<path d="M208.938,331.094l11.875,29.688c-11.5,4.594-22.969,8.094-34.156,10.438l-6.531-31.313  C189.5,337.938,199.188,334.969,208.938,331.094z M113.719,369.438c11.719,3.375,24.063,5.188,36.688,5.375l0.5-32  c-9.813-0.156-19.344-1.531-28.344-4.125L113.719,369.438z M365.688,313.594c9.219,1.813,18.219,4.969,26.75,9.375l14.688-28.438  c-11.25-5.781-23.094-9.938-35.25-12.313L365.688,313.594z M298.938,287.344l9.563,30.5c9.781-3.063,19.438-5,28.75-5.75  l-2.563-31.875C323,281.156,310.969,283.563,298.938,287.344z M247.563,311.406c-3.594,2.25-7.125,4.313-10.594,6.25l15.563,27.938  c3.906-2.188,7.906-4.5,11.906-7c5.5-3.406,10.875-6.438,16.156-9.125l-14.531-28.5C260.063,304.031,253.875,307.5,247.563,311.406z   M336,112c0,13.063-3.125,25.438-8.688,36.313L256,288c0,0-71.875-140.844-72.156-141.438C178.813,136.125,176,124.375,176,112  c0-44.188,35.813-80,80-80S336,67.813,336,112z M304,112c0-26.5-21.5-48-48-48s-48,21.5-48,48s21.5,48,48,48S304,138.5,304,112z   M416,192h-75.063l-16.344,32h68.344l34.531,103.625l-11.031,12.188c11.75,10.625,17.844,20.406,17.875,20.438l3.375-2.031  L467.594,448H44.375l32.063-96.094c1.125,0.781,2,1.531,3.25,2.313l17-27.063c-3.813-2.406-6.938-4.781-9.688-7L119.063,224h68.375  c-6.781-13.25-12.125-23.719-16.344-32H96L0,480h512L416,192z"/>
	</svg>				 -->
	</button>
							<button class="icon-map-active search-mobile" title="Zoeken">
								<svg alt="search" class="icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="1.1em" height="1.1em" viewBox="0 0 483.1 483.1" xml:space="preserve" class="detail convertSvgInline replaced-svg" width="1.1em" height="1.1em" data-id="70490" data-kw="magnifying-glass34" fill="#000000"><path d="M332.7 315.4c30.9-33.4 50.2-78.2 50.2-127.5C382.9 84.4 298.7 0 195 0S7.2 84.4 7.2 187.9 91.3 375.7 195 375.7c42.2 0 81-13.9 112.5-37.4l139.7 139.7c3.4 3.4 7.7 5.1 11.9 5.1s8.8-1.7 11.9-5.1c6.5-6.5 6.5-17.3 0-24.1L332.7 315.4zM41.2 187.9C41.2 103.1 110 34 195 34c84.7 0 153.9 68.9 153.9 153.9S280 341.7 195 341.7 41.2 272.6 41.2 187.9z" fill=""/></svg>
							</button>
							<div class="mobile search-mobile">
								<form class="search-address">
									<input id="search" placeholder="Zoek op adres" name="address"/>
									<button class="close-search-mobile" type="submit" title="Zoeken">
										<svg alt="search" class="icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="1.1em" height="1.1em" viewBox="0 0 483.1 483.1" xml:space="preserve" class="detail convertSvgInline replaced-svg" width="1.1em" height="1.1em" data-id="70490" data-kw="magnifying-glass34" fill="#000000"><path d="M332.7 315.4c30.9-33.4 50.2-78.2 50.2-127.5C382.9 84.4 298.7 0 195 0S7.2 84.4 7.2 187.9 91.3 375.7 195 375.7c42.2 0 81-13.9 112.5-37.4l139.7 139.7c3.4 3.4 7.7 5.1 11.9 5.1s8.8-1.7 11.9-5.1c6.5-6.5 6.5-17.3 0-24.1L332.7 315.4zM41.2 187.9C41.2 103.1 110 34 195 34c84.7 0 153.9 68.9 153.9 153.9S280 341.7 195 341.7 41.2 272.6 41.2 187.9z" fill=""/></svg>
									</button>
								</form>
							</div>
							<div class="center">
								<div class="info">
									<a href="<?php echo home_url(); ?>" alt="Info" class="info-button">
										<svg alt="info" class="icon info-icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="1.1em" height="1.1em" viewBox="0 0 510 510" xml:space="preserve" class="detail convertSvgInline replaced-svg" data-id="61093" data-kw="round52"><path d="M229.5 382.5h51v-153h-51V382.5zM255 0C114.8 0 0 114.8 0 255s114.8 255 255 255 255-114.7 255-255S395.3 0 255 0zM255 459c-112.2 0-204-91.8-204-204S142.8 51 255 51s204 91.8 204 204S367.2 459 255 459zM229.5 178.5h51v-51h-51V178.5z"/></svg>
									</a>
								</div>
							</div>
							<div class="opacity icon-map-active">
								<button id="opacity" title="Verander doorzichtigheid kaart">
									<svg alt="opacity" class="icon" id="opacity-icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="1.1em" height="1.1em" viewBox="0 0 31 31" xml:space="preserve" class="detail convertSvgInline replaced-svg" width="1.1em" height="1.1em" data-id="927" data-kw="contrast"><path d="M29.1 14.6c0 0 0-0.1 0-0.1C29.1 6.5 22.6 0 14.6 0 14.6 0 14.6 0 14.6 0h0l0 0C6.5 0 0.1 6.5 0 14.5c0 0 0 0.1 0 0.1v0l0 0c0 8 6.5 14.6 14.6 14.6l0 0h0c0 0 0 0 0 0 8 0 14.5-6.5 14.5-14.5l0 0L29.1 14.6 29.1 14.6zM14.6 2.1c6.9 0 12.5 5.6 12.5 12.5 0 6.9-5.6 12.5-12.5 12.5V2.1z"/></svg>
								</button>
								<div class="opacity-active">
									<button type="button" class="satelliet map-mode" data-hybridtext="<?php _e('satelliet'); ?>" data-maptext="<?php _e('kaart'); ?>">
										<?php _e('kaart'); ?>
									</button>
									<form id="set-opacity" method="get" action="/">
										<input id="contrast" type="range" value="100" max="100" min="0" step="0.01"/><span id="contrast-value">100</span>
									</form>
								</div>
							</div>
							<nav class="btn-group year">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									2015
								</button>
								<?php Template::Render('snippet-menu-year.php'); ?>
							</nav>
						</div>
					</section>
				</header>
