<h2>Tournois</h2>

<section class="games">
	<?php
	use lib\content\Image;
	use lib\lan\Game;
	use lib\lan\Tournament;
	$tournaments = Tournament::getTournaments();
	foreach($tournaments as $tournament){
	?>
    <article>
        <img src="<?php echo Image::thumb('250', '360', 'uploads/', $tournament->getGame()->getCover(), 'fill'); ?>" alt="" />
        <div class="name"><?php echo $tournament->getName(); ?></div>
        <div class="ss-name">Jeu : <?php echo $tournament->getGame()->getName(); ?></div>
        <div class="description"><?php echo $tournament->getDescription(); ?></div>
    </article>
	<?php
	}
	?>
</section>