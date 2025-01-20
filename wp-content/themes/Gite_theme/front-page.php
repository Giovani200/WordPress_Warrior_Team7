<?php get_header(); ?>

<!-- <section class="front-container" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/arriere_plan.jpg); background-size: cover; background-position: center; height: 80vh; color: white; position: relative; text-align: center;">
    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); z-index: 1;"></div>

    <div class="container" style="position: relative; z-index: 2; top: 10%; transform: translateY(-50%);">
        <h1 style="font-size: 4rem; margin: 0;">Un séjour authentique au cœur du Perche,</h1>
    </div>
</section>

<section>
    <div class="container">
        <h1 style="margin: 50px; text-align: center;">Un séjour authentique au cœur du Perche,</h1>
        <h2 style="margin: 20px; text-align: center; position: relative; top: -39px;">où nature et confort se rencontrent</h2>
        <p style="font-size: 1.2rem; margin-top: 20px; max-width: 800px; text-align: center; margin-left: auto; margin-right: auto;">
            Plongez dans l’univers enchanteur du Gîte Montplaisir. À seulement 1h45 de Paris, notre gîte vous accueille pour une expérience unique, au milieu d’une nature préservée. Profitez de moments de détente autour de la piscine, en famille ou entre amis, et laissez-vous séduire par la tranquillité de notre havre de paix.
        </p>
    </div>
</section>

<section style="padding: 40px;">
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
        <!- Image principale à gauche -->
        <!-- <div style="grid-column: 1 / 2; display: flex; flex-direction: column; overflow: hidden; height: 100%;">
            <img src="https://images.unsplash.com/photo-1519824145371-296894a0daa9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fG5hdHVyZSUyMHBhcmt8ZW58MHx8fHwxNjg2NzUwODI4&ixlib=rb-4.0.3&q=80&w=800" alt="Maison dans la nature" style="width: 100%; height: 66%; object-fit: cover;">
            <div style="padding: 20px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 10px;">Un lieu d’exception, pensé pour votre confort et votre bien-être</h2>
                <p style="font-size: 1rem; margin-bottom: 15px;">Explorez chaque détail de notre gîte, du confort des chambres à son cadre naturel unique.</p>
                <a href="#" style="display: inline-block; padding: 10px 20px; font-size: 1rem; background: none; border: 2px solid black; border-radius: 4px; text-decoration: none; color: black; cursor: pointer; transition: all 0.3s ease;">En savoir plus</a>
            </div>
        </div>

        <!- Section du milieu --
        <div style="grid-column: 2 / 3; display: flex; flex-direction: column; overflow: hidden; height: 100%;">
            <img src="https://images.unsplash.com/photo-1519824145371-296894a0daa9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fG5hdHVyZSUyMHBhcmt8ZW58MHx8fHwxNjg2NzUwODI4&ixlib=rb-4.0.3&q=80&w=800" alt="Salon confortable" style="width: 100%; height: 54%; object-fit: cover;">
            <div style="padding: 20px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 10px;">Des chambres spacieuses, un confort absolu</h2>
                <p style="font-size: 1rem; margin-bottom: 15px;">Choisissez votre chambre idéale, alliant charme et confort pour un séjour serein.</p>
                <a href="#" style="display: inline-block; padding: 10px 20px; font-size: 1rem; background: none; border: 2px solid black; border-radius: 4px; text-decoration: none; color: black; cursor: pointer; transition: all 0.3s ease;">En savoir plus</a>
            </div>
        </div> -->

        <!-- Section à droite --
        <div style="grid-column: 3 / 4; display: flex; flex-direction: column; overflow: hidden;">
            <img src="https://images.unsplash.com/photo-1519824145371-296894a0daa9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fG5hdHVyZSUyMHBhcmt8ZW58MHx8fHwxNjg2NzUwODI4&ixlib=rb-4.0.3&q=80&w=800" alt="Paysage du Perche" style="width: 100%; height: 54%; object-fit: cover;">
            <div style="padding: 20px;">
                <h2 style="font-size: 1.5rem; margin-bottom: 10px;">Le Perche, une région à découvrir</h2>
                <p style="font-size: 1rem; margin-bottom: 15px;">Randonnée, culture, gastronomie : le Perche regorge de trésors à découvrir.</p>
                <a href="#" style="display: inline-block; padding: 10px 20px; font-size: 1rem; background: none; border: 2px solid black; border-radius: 4px; text-decoration: none; color: black; cursor: pointer; transition: all 0.3s ease;">En savoir plus</a>
            </div>
        </div>
    </div> -->
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

<!-- <section style="background-image: url('https://images.unsplash.com/photo-1519824145371-296894a0daa9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDJ8fG5hdHVyZSUyMHBhcmt8ZW58MHx8fHwxNjg2NzUwODI4&ixlib=rb-4.0.3&q=80&w=800'); background-size: cover; display: flex; justify-content: center; align-items: center; height: 45vh; margin: 0; margin-top: 14%;">
    <div style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 400px; width: 100%;">
        <h1 style="font-size: 18px; margin-bottom: 20px;">Contactez-nous pour toute question ou réservation</h1>
        <form>
            <input type="text" name="nom" placeholder="Nom (obligatoire)" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <input type="email" name="email" placeholder="E-mail (obligatoire)" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <input type="text" name="date" placeholder="Date de séjour souhaitée" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;">
            <textarea name="message" placeholder="Message" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
            <button type="submit" style="width: 100%; padding: 10px; background-color: black; color: white; border: none; border-radius: 5px; cursor: pointer;">Envoyer</button>
        </form>
    </div>
</section> -->

<?php get_footer(); ?>
