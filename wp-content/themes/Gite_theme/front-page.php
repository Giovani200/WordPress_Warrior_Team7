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

<section>
<div class="container2">
        <!-- <div class="image-section">
            <img src="<?php echo get_theme_file_uri('img/arriere_plan.jpg'); ?>" alt="Maison de campagne">
        </div>
        <div class="form-section">
            <h2>Contactez-nous pour toute <em>question ou réservation</em></h2>
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                <?php wp_nonce_field('contact_form_submit', 'contact_nonce'); ?>
                <input type="hidden" name="action" value="contact_form_submission">
                <input type="text" name="name" placeholder="Nom (obligatoire)" required>
                <input type="email" name="email" placeholder="E-mail (obligatoire)" required>
                <textarea name="message" placeholder="Message"></textarea>
                <button type="submit">Envoyer</button>
            </form>
        </div>
</div> -->
</section>


<?php get_footer(); ?>
