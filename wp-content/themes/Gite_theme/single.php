<?php get_header(); ?>

<section>
    <div class="container">
        <?php if (have_posts()): ?>
            <?php while (have_posts()) : the_post(); ?>

                <div class="row m-dw-30">
                    <div class="col-xs-12">
                        <!-- function prédéfinie de wordpress pour afficher le texte et les paragaphes -->
                        <h1><?php the_title(); ?></h1>

                        <!-- <p>
                            <?php echo gite_post_meta(
                                esc_attr(get_the_date('c')),
                                esc_html(get_the_date()),
                                get_the_category_list(',')
                            ); ?>
                        </p> -->

                        <?php the_content(); ?>
                    </div>
                </div><!-- /row -->

            <?php endwhile; ?>

            <div class="row">
                <div class="col-xs-12">
                    <nav>
                        <ul class="machin-pager">
                            <li class="pull-left"><?php previous_post_link(); ?></li>
                            <li class="pull-right"><?php next_post_link(); ?></li>

                        </ul>
                    </nav>
                </div>
            </div>

        <?php else: ?>
            <div class="row">
                <div class="col-xs-12">
                    <p>Il n'y a pas de contenu à afficher</p>
                </div>
            </div>
    </div>
<?php endif; ?>

</div><!-- /container -->
</section>

<?php get_footer(); ?>