<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2012-2013
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @package    votebox
 * @license    LGPL
 * @filesource
 */


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