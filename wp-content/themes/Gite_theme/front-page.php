<?php get_header(); ?>


</section>

<section class="content-section">
    <div class="container">
        <!-- Appelle le contenu principal édité depuis WordPress -->
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_content(); // Affiche le contenu édité depuis l'administration WordPress
            endwhile;
        else :
            echo '<p>Aucun contenu n’a été trouvé pour cette page.</p>';
        endif;
        ?>
    </div>
</section>


<?php get_footer(); ?>
