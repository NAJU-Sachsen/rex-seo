<?php

$seo_table = rex_sql_table::get('naju_seo');

if ($seo_table->exists()) {
    // create meta description if necessary
    $sql = rex_sql::factory()->setQuery('select meta_value from naju_seo where meta_key = :key', ['key' => 'description']);

    if (!$sql->getRows()) {
        rex_sql::factory()->setQuery('insert into naju_seo (meta_key, meta_value) values (:key, :val)', ['key' => 'description', 'val' => '']);
    }
} else {
    $seo_table
        ->addColumn(new rex_sql_column('meta_key', 'varchar(30)'))
        ->addColumn(new rex_sql_column('meta_value', 'mediumtext'))
        ->setPrimaryKey('meta_key')
        ->create();

    rex_sql::factory()->setQuery('insert into naju_seo (meta_key, meta_value) values (:key, :val)', ['key' => 'description', 'val' => '']);
}

rex_sql_table::get('naju_contact_info')
    ->ensureColumn(new rex_sql_column('seo_title_prefix', 'varchar(120)'))
    ->ensureColumn(new rex_sql_column('seo_description', 'text'))
    ->alter();
