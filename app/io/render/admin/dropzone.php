<?php if (!isset($dropzone_relative_path)) {
    throw new RuntimeException('Missing $dropzone_relative_path variable in ' . __FILE__);
} ?>
<section class="media-box panel drop-zone" data-upload="/admin/upload/<?= $dropzone_relative_path ?>">
    <?php if (!isset($dropzone_new) || $dropzone_new === false): ?>
        <figure>
            <img src="/asset/image/<?= $dropzone_relative_path ?>.webp" class="drop-preview" alt=" - Photo manquante - " loading="lazy" />
            <figcaption>Photo principale</figcaption>
        </figure>
    <?php else: ?>
        <input type="hidden" name="dropzone_new" value="1">
    <?php endif; ?>
    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" data-keep-filename="<?= $dropzone_keep_filename ?? 0 ?>" hidden>
    <label for="avatar" class="drop-label">
        <span></span>
        <strong>JPEG, PNG ou WebP.<br>Max 2MB.</strong>
    </label>
</section>