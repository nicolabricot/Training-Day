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
        <div class="titre"></div>
        <div class="ss-titre"></div>
        <div class="description"></div>
    </article>
	<?php
	}
	?>
</section>