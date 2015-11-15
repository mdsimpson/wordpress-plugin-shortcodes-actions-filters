<?php

/*
    "Add Shortcodes, Actions and Filters" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of Add Actions and Filters for WordPress.

    Add Actions and Filters is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Add Actions and Filters is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

class AddActionsAndFilters_ViewAdminPage
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    /**
     * @var AddActionsAndFilters_CodeListTable
     */
    var $table;
    
    
    public function __construct(&$plugin, &$table)
    {
        $this->plugin = $plugin;
        $this->table = $table;
    }

    public function display()
    {
        echo '<div class="wrap">';
        // Header
        printf('<table width="%s"><tbody><tr><td><img src="%s"/></td><td align="right"><a href="%s"><img src="%s"/></a><a href="%s"><img src="%s"/></a></td></tr></tbody></table>',
            '100%',
            $this->plugin->getPluginFileUrl('img/admin-banner.png'),
            'admin.php?page=' . $this->plugin->getImportExportSlug(),
            $this->plugin->getPluginFileUrl('img/import-export.png'),
            'admin.php?page=' . $this->plugin->getSettingsSlug(),
            $this->plugin->getPluginFileUrl('img/settings.png')
        );
        printf('<table><tbody><tr><td></td></tr></tbody></table>');

        // Table Styles
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 7%;}';
        echo '.wp-list-table .column-enabled { width: 12%; text-align: center;}';
        echo '.wp-list-table .column-shortcode { width: 14%; text-align: center;}';
        echo '.wp-list-table .column-name { width: 25%; }';
        echo '.wp-list-table .column-description { width: 42%; }';
        echo '.wp-list-table .item-inactive { font-style: italic; opacity: 0.6; filter: alpha(opacity = 60); /* MSIE */ }';
        echo '</style>';

        // Form for bulk actions
        printf('<form action="admin.php?page=%s%s" method="post">',
            $this->plugin->getAdminPageSlug(),
            (isset($_REQUEST['paged']) && $_REQUEST['paged']) ? ('&paged=' . $_REQUEST['paged']) : ''
        );

        // Code table
        $this->table->display();

        // Closing Tags
        echo '</form>';
        echo '</div>';

    }
}