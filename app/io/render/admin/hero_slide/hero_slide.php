<header class="page-header">
    <h1>Hero Slides - Page d'accueil</h1>
</header>

<div class="hero-slide-gallery">
    <?php foreach ($slides as $slide) : ?>
        <div class="hero-slide">
            <img src="<?= $slide ?>" alt="Hero Slide Image" class="hero-slide__image">
            <div class="hero-slide__actions">
                <a href="/admin/home?delete_asset=<?= urlencode($slide) ?>" class="btn btn--danger">Supprimer</a>
            </div>
        </div>
    <?php endforeach; ?>

    <?php
    $dropzone_relative_path = 'home/hero_slide/';
    $dropzone_new = true;
    $dropzone_keep_filename = 1;
    include('app/io/render/admin/dropzone.php');
    ?>
</div>