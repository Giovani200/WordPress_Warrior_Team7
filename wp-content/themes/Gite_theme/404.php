<?php
get_header(); ?>

<div class="container-404">
    <div class="content-404">
        <img src="https://www.gitemontplaisir.com/wp-content/uploads/2025/02/error-404-1024x471.png" alt="Page non trouvée">
        <h1>Oups ! Page non trouvée</h1>
        <p>La page que vous recherchez semble introuvable. Peut-être qu'elle a été déplacée ou supprimée.</p>
        <a href="<?php echo home_url(); ?>" class="btn-404">Retour à l'accueil</a>
    </div>
</div>

<style>
    .container-404 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 100vh;
        background-color: #f2f2e9;
        font-family: 'Georgia', serif;
        color: #52623a;
    }

    .content-404 {
        max-width: 600px;
        padding: 20px;
    }

    .content-404 img {
        max-width: 100%;
        height: auto;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    p {
        font-size: 1.2rem;
        margin-bottom: 20px;
    }

    .btn-404 {
        display: inline-block;
        padding: 10px 20px;
        background-color: #72884b;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: 0.3s;
    }

    .btn-404:hover {
        background-color: #5a6d3b;
    }
</style>

<?php
get_footer();
?>

