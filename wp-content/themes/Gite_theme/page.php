<?php get_header(); ?>

<section class="content-section">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                // the_title('<h1>', '</h1>'); // Affiche le titre de la page
                the_content(); // Affiche le contenu de la page
            endwhile;
        else :
            echo '<p>Aucun contenu n’a été trouvé pour cette page.</p>';
        endif;
        ?>
    </div>
</section>

<?php get_footer(); ?>
