<?php get_header(); ?>

<section>
    <div class="container">
        <?php if (have_posts()): ?>
            <?php while (have_posts()) : the_post(); ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php the_content() ?>
                </div>
            </div>
            
            <?php endwhile; ?>


        <?php else: ?>
            <div class="row">
                <div class="col-xs-12">
                    <p>Il n'y a pas de contenu à afficher</p>
                </div>
            </div>

        <?php endif; ?>

    </div><!-- /container -->
</section>

<?php get_footer(); ?>