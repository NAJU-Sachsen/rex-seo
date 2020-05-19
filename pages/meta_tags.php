<?php

$fragment = new rex_fragment();
$content = '';

if (rex_get('func') == 'edit' && rex_get('tag') == 'description') {
    $description = rex_post('meta_description');
    rex_sql::factory()
    ->setQuery('INSERT INTO naju_seo (meta_key, meta_value) VALUES (:key , :val) ON DUPLICATE KEY UPDATE meta_value=:val',
        ['key' => 'description', 'val' => $description]);
    naju_kvs::put('naju.seo.description', $description);
}

$currentMetaDescription = rex_sql::factory()
    ->setQuery('select meta_value from naju_seo where meta_key = :key', ['key' => 'description'])
    ->getArray();
if (!$currentMetaDescription) {
    $currentMetaDescription = '';
} else {
    $currentMetaDescription = $currentMetaDescription[0]['meta_value'];
}

$content .= '
    <form action="' . rex_url::currentBackendPage(['func' => 'edit', 'tag' => 'description']) . '" method="post" style="margin: 15px;">
        <div class="control-group">
            <label for="meta_description">Seitenbeschreibung</label>
            <textarea name="meta_description" id="meta_description" class="form-control">' . htmlspecialchars($currentMetaDescription) . '</textarea>
        </div>
        <div class="control-group" style="margin-top: 10px;">
            <button type="submit" class="btn btn-primary">Aktualisieren</button>
        </div>
    </form>
';

$fragment->setVar('content', $content, false);
echo $fragment->parse('core/page/section.php');

