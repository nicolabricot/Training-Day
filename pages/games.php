<h2>Jeux</h2>
<section class="games">
	<?php
use lib\content\Image;

use lib\lan\Game;
	$games = Game::getGames();
	foreach($games as $game){
	?>
	    <article>
	        <img src="<?php echo Image::thumb('250', '360', 'uploads/', $game->getCover(), 'fill'); ?>" alt="" />
	        <div class="name"><?php echo $game->getName(); ?></div>
	        <div class="description"><?php echo $game->getDescription(); ?></div>
	    </article>
	<?php
	}
	?>
</section>