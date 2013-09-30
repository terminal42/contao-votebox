<?php


/**
 * Register PSR-0 namespace
 */
NamespaceClassLoader::add('Votebox', 'system/modules/votebox/library');


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
    'mod_votebox_list'              => 'system/modules/isotope/templates/backend',
    'mod_votebox_new_idea'          => 'system/modules/isotope/templates/backend',
    'mod_votebox_reader'            => 'system/modules/isotope/templates/checkout',
    'votebox_list_default'          => 'system/modules/isotope/templates/checkout',
    'votebox_new_idea_default'      => 'system/modules/isotope/templates/checkout',
    'votebox_reader_default'        => 'system/modules/isotope/templates/checkout',
));