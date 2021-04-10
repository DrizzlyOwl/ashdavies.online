<?php

/**
 * Frontpage template
 */

namespace Ash;

get_header();

the_post();
?>
<main id="contentarea">
    <div class="masthead">
        <header class="masthead__inner">
            <h1 class="masthead__heading"><?php the_title(); ?></h1>
            <div class="masthead__blurb"><?php the_excerpt(); ?></div>
            <p class="masthead__connect">
                <img src="<?php theme_dir(); ?>/dist/twitter.svg" alt="Twitter icon">
                <a href="https://twitter.com/DrizzlyOwl" aria-label="Connect with me on Twitter @DrizzlyOwl">DrizzlyOwl</a>
            </p>
            <p class="masthead__connect">
                <img src="<?php theme_dir(); ?>/dist/keybase.svg" alt="Keybase icon">
                <a href="https://keybase.io/DrizzlyOwl" aria-label="Talk to me securely using Keybase @DrizzlyOwl">Keybase</a>
            </p>
        </header>
    </div>
    <img class="avatar" src="<?php theme_dir(); ?>/dist/profile.jpg" alt="Picture of Ash Davies">
    <img class="avatar avatar--alt" src="<?php echo get_home_url(); ?>/content/uploads/2020/11/3853061.jpeg" alt="Drizzly Owl Logo">
    <div class="skillbox">
        <h2 id="coreskills" class="skillbox__title">My core skills</h2>
        <div class="skillbox__wrap">
            <article class="skill">
                <figure class="skill__icon skill__icon--wp">
                    <img src="<?php theme_dir(); ?>/dist/wp.svg" alt="WordPress icon">
                    <figcaption class="screen-reader-text">WordPress</figcaption>
                </figure>
                <h3 class="skill__heading">WordPress</h3>
                <p class="skill__text">
                    I continue to build and maintain bespoke themes and plugins for the NHS, Police, local councils
                    and other public sector entities. It's my bread and butter.
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--php">
                    <img src="<?php theme_dir(); ?>/dist/php.svg" alt="PHP icon">
                    <figcaption class="screen-reader-text">PHP: Hypertext Preprocessor</figcaption>
                </figure>
                <h3 class="skill__heading">PHP</h3>
                <p class="skill__text">
                    I'm no stranger to frameworks like <a href="https://laravel.com/" rel="nofollow">Laravel</a> and
                    <a href="https://symfony.com/" rel="nofollow">Symfony</a> and love to get stuck in to some
                    Object-oriented Programming (OOP) once in a while.
                </p>
            </article>
            <article class="skill">
                <figure class="skill__icon skill__icon--sys">
                    <img src="<?php theme_dir(); ?>/dist/ubuntu.svg" alt="Ubuntu icon">
                    <figcaption class="screen-reader-text">Ubuntu enterprise</figcaption>
                </figure>
                <h3 class="skill__heading">Ubuntu</h3>
                <p class="skill__text">
                    I'm adept at using the command line interface. I'm responsible for the upkeep, configuration, and
                    reliable operation of Cloud Hosting within Mixd.
                </p>
            </article>
        </div>
    </div>
    <section class="wrapper">
        <article class="contentarea"><?php the_content(); ?></article>
    </section>
</main>
<?php
get_footer();
