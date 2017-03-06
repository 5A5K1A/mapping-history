<main class="articles" id="post-<?php echo $this->id; ?>">
	<article class="<?php echo $this->classes ?>">

		<!-- Video embed -->
		<div class="videoFrame">
			<iframe id="ytplayer" src="https://www.youtube.com/embed/lPyALGWFCd8?rel=0&autoplay=1&showinfo=0&controls=0&loop=1&enablejsapi=1" frameborder="0"></iframe>
		</div>

		<!-- Content -->
		<div class="entry">
			<?php echo $this->content; ?>
		</div>

		<!-- Social shares -->
		<?php echo $this->social; ?>

	</article>
</main>
