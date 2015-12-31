<?php

/*
    "Add Shortcodes, Actions and Filters" Copyright (C) 2013-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

require_once('AddActionsAndFilters_AdminPageActions.php');

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


    /**
     * Sets Admin page screen options
     */
    public static function addAdminPageScreenOptions()
    {
        require_once('AddActionsAndFilters_DataModelConfig.php');
        $option = 'per_page';
        $args = array(
            'label' => 'Code Items',
            'default' => AddActionsAndFilters_DataModelConfig::PER_PAGE_DEFAULT,
            'option' => AddActionsAndFilters_DataModelConfig::PER_PAGE_OPTION
        );
        add_screen_option($option, $args);
    }

    // set-screen-option callback - does not work
//    public static function setScreenOptionCallback($status, $option, $value)
//    {
//        // http://chrismarslender.com/2012/01/26/wordpress-screen-options-tutorial/
//        if (AddActionsAndFilters_DataModelConfig::PER_PAGE_OPTION == $option) {
//            return $value;
//        }
//        return $status;
//    }

    // Work-around for the above callback not working
    public static function setScreenOptionCallback($option, $value)
    {
        if (AddActionsAndFilters_DataModelConfig::PER_PAGE_OPTION == $option) {
            $userId = get_current_user_id();
            if ($userId != 0) {
                update_user_option($userId, $option, $value);
            }
        }
    }

    public static function addHelpTab()
    {
        $screen = get_current_screen();
        $screen->add_help_tab(array(
            'id' => 'AddActionsAndFilters_help',
            'title' => __('Help'),
            'content' =>
                '<a href="https://codex.wordpress.org/Shortcode_API" target="_blank">Shortcode</a></br>
                <a href="https://codex.wordpress.org/Function_Reference/add_action" target="_blank">add_action</a></br>
                <a href="https://codex.wordpress.org/Function_Reference/add_filter" target="_blank">add_filter</a>',
            //'callback' => $callback
        ));
    }

    public function __construct(&$plugin, &$table)
    {
        $this->plugin = $plugin;
        $this->table = $table;
    }

    public function display()
    {
        require_once('AddActionsAndFilters_AdminViewUrlBuilder.php');
        $urlBuilder = new AddActionsAndFilters_AdminViewUrlBuilder();
        $cleanUrl = $urlBuilder->buildUrl(); // no action value in it

        echo '<div class="wrap">';
        // Header
        $adminUrl = get_admin_url() . 'admin.php?page=';
        printf('<table width="%s"><tbody><tr><td><a href="%s"><img src="%s"/></a></td><td align="right"><span style="white-space: nowrap;"><a href="%s"><img src="%s"/></a><a href="%s"><img src="%s"/></a></span></td></tr></tbody></table>',
            '100%',
            $cleanUrl,
            $this->plugin->getPluginFileUrl('img/admin-banner.png'),
            $adminUrl . $this->plugin->getImportExportSlug(),
            $this->plugin->getPluginFileUrl('img/import-export.png'),
            $adminUrl . $this->plugin->getSettingsSlug(),
            $this->plugin->getPluginFileUrl('img/settings.png')
        );
        printf('<table><tbody><tr><td></td></tr></tbody></table>');

        $actions = new AddActionsAndFilters_AdminPageActions();
        printf('<a href="%s%s%s%s%s" class="page-title-action">%s</a>',
            get_admin_url(),
            'admin.php?page=',
            $this->plugin->getAdminPageSlug(),
            '&action=',
            $actions->getEditKey(),
            __('Add New'));

        // Table Styles
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 7%; }';
        echo '.wp-list-table .column-enabled { width: 10%; }';
        echo '.wp-list-table .column-shortcode { width: 7%; }';
        echo '.wp-list-table .column-name { width: 25%; }';
        echo '.wp-list-table .column-capability { width: 20%; }';
        echo '.wp-list-table .column-description { width: 31%; }';
        echo '.wp-list-table .item-inactive { font-style: italic; opacity: 0.6; filter: alpha(opacity = 60); /* MSIE */ }';
        echo '</style>';

        // Form for bulk actions
        printf('<form action="%s" method="post">', $cleanUrl);

        // Search box
        $this->table->search_box('search', 'search_id');

        // Code table
        $this->table->display();

        // Closing Tags
        echo '</form>';
        echo '</div>';

    }
}