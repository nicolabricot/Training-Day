<h2>Jeux</h2>
<section class="game">
	<?php
	$games = Game::getGames();
	foreach($games as $game){
	?>
	    <article>
	        <div class="image">
	            <img src="<?php Image::thumb('250', '400', 'uploads', $game->getCover(), 'fill'); ?>" alt="" />
	        </div>
	        <div class="<?php $game->getTitle(); ?>"></div>
	        <div class="<?php $game->getDescription(); ?>"></div>
	    </article>
	<?php
	}
	?>
</section>