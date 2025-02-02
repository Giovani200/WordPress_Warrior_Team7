<?php
/*
Template Name: Full Width
*/
get_header(); ?>

<style>
    .full-width-banner {
        width: 100vw; /* Prend toute la largeur de l'écran */
        height: 400px; /* Ajuste selon besoin */
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #000; /* Fond par défaut */
    }

    .banner-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Assure que l'image couvre bien l'espace */
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
    }

    .banner-content {
        color: white;
        position: relative;
        z-index: 1;
        padding: 20px;
        background: rgba(0, 0, 0, 0.5); /* Fond semi-transparent */
        border-radius: 10px;
    }
</style>

<div class="full-width-banner">
    <?php if (has_post_thumbnail()) : ?>
        <div class="banner-image">
            <?php the_post_thumbnail('full'); ?>
        </div>
    <?php endif; ?>
    
    <div class="banner-content">
        <h1><?php the_title(); ?></h1>
        <p><?php the_excerpt(); ?></p>
    </div>
</div>

<?php get_footer(); ?>
