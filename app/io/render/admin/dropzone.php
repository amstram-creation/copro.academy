<?php if (!isset($dropzone_relative_path)) {
    throw new RuntimeException('Missing $dropzone_relative_path variable in ' . __FILE__);
} ?>
<section class="media-box panel drop-zone" data-upload="/admin/upload/<?= $dropzone_relative_path ?>">
    <?php if (isset($dropzone_new) && $dropzone_new === true): ?>
        <input type="hidden" name="dropzone_new" value="1">
    <?php endif; ?>
    <figure>

        <img src="<?= $preview_src ?? '' ?>" class="drop-preview" />
        <figcaption>Photo principale</figcaption>
    </figure>

    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" data-keep-filename="<?= $dropzone_keep_filename ?? 0 ?>" hidden>
    <label for="avatar" class="drop-label">
        <span></span>
        <strong class="btn primary">Ajouter JPEG, PNG ou WebP.<br>Max 20MB.</strong>
    </label>
</section>