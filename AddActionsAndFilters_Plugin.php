<?php

/*
    "Add Shortcodes, Actions and Filters" Copyright (C) 2013-2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

include_once('AddActionsAndFilters_LifeCycle.php');

class AddActionsAndFilters_Plugin extends AddActionsAndFilters_LifeCycle
{

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData()
    {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            '_version' => array('Installed Version'), // For testing upgrades
            'NumberItemsPerPage' => array('Number of items shown in the Administration Page at a time'), // For testing upgrades
            'DropOnUninstall' => array(__('Delete all added code and settings for this plugin\'s when uninstalling', 'add-actions-and-filters'), 'false', 'true')
        );
    }

    public function getPluginDisplayName()
    {
        return __('Shortcodes, Actions and Filters', 'add-actions-and-filters');
    }

    protected function getMainPluginFileName()
    {
        return 'add-actions-and-filters.php';
    }

    function getTableName()
    {
        return $this->prefixTableName('usercode');
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $this->getTableName();

        $sql =
            "CREATE TABLE $table_name (\n" .
            "id mediumint(9) NOT NULL AUTO_INCREMENT, \n" .
            "enabled boolean DEFAULT 0 NOT NULL, \n" .
            "shortcode boolean DEFAULT 0 NOT NULL, \n" .
            "name tinytext DEFAULT '' NOT NULL, \n" .
            "description tinytext DEFAULT '' NOT NULL, \n" .
            "echo boolean DEFAULT 0 NOT NULL, \n" .
            "code text DEFAULT '' NOT NULL, \n" .
            "UNIQUE KEY id (id) \n" .
            ") $charset_collate;";

//        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//        dbDelta($sql);

        $wpdb->show_errors();
        $wpdb->query($sql);
        $wpdb->hide_errors();
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables()
    {
        if ('true' == $this->getOption('DropOnUninstall', 'false')) {
            global $wpdb;
            $table_name = $this->getTableName();
            $wpdb->query("DROP TABLE IF EXISTS $table_name");
        }
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade()
    {
        $upgradeOk = true;
        $savedVersion = $this->getVersionSaved();
        if ($this->isVersionLessThan($savedVersion, '2.0')) {
            $this->installDatabaseTables();
        }

        // Post-upgrade, set the current version in the options
        $codeVersion = $this->getVersion();
        if ($upgradeOk && $savedVersion != $codeVersion) {
            $this->saveInstalledVersion();
        }
    }


    public function addActionsAndFilters()
    {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addToolsAdminPage'));
        add_action('admin_menu', array(&$this, 'addSettingsPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        if (strpos($_SERVER['REQUEST_URI'], $this->getAdminPageSlug()) !== false) {
            add_action('admin_enqueue_scripts', array(&$this, 'enqueueAdminPageStylesAndScripts'));
        }

        // Add Actions & Filters
        // http://plugin.michael-simpson.com/?page_id=37

        // todo: revisit
        $tmpCode = $this->getOption('tmp_code', '');
        $code = $this->getOption('code');
        if (!empty($tmpCode) && $tmpCode != $code) {
            // Test that the code works
            $this->updateOption('tmp_code', '');
            $this->updateOption('fatal_code', $tmpCode);
            eval($tmpCode); // Make raise FATAL error
            $this->updateOption('code', $tmpCode);
            $this->updateOption('fatal_code', '');
        } else {
            eval($code);
        }

        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41
        add_action('wp_ajax_addactionsandfilters_save', array(&$this, 'ajaxSave'));


    }

    // todo new JS editor
    function enqueueAdminPageStylesAndScripts()
    {
        wp_enqueue_script('edit_area', plugins_url('/edit_area/edit_area_full.js', __FILE__));
        //wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
    }

    /**
     * @return string slug for plugin's main administration page
     */
    public function getAdminPageSlug()
    {
        return 'ShortcodesActionsFilters';
    }

    /**
     * @return string slug for plugin's Settings page
     */
    public function getSettingsSlug()
    {
        return $this->getAdminPageSlug() . 'Settings';
    }

    /**
     * @return string slug for plugin's Settings page
     */
    public function getImportExportSlug()
    {
        return $this->getAdminPageSlug() . 'ImpExp';
    }


    /**
     * Add the plugin's admin page under the Tools menu in the WP Dashboard
     */
    function addToolsAdminPage()
    {
        if (current_user_can('manage_options')) {
            $this->requireExtraPluginFiles();
            $displayName = $this->getPluginDisplayName();
            add_submenu_page('tools.php',
                $displayName,
                $displayName,
                'manage_options',
                $this->getAdminPageSlug(), // slug
                array(&$this, 'handleAdminPageUrl'));
        }
    }

    /**
     * Create a settings page for the plugin, but not in the Dashboard menus
     */
    function addSettingsPage()
    {
        // Setting Page
        if (current_user_can('manage_options')) {
            $this->requireExtraPluginFiles();
            $displayName = $this->getPluginDisplayName();
            add_submenu_page(null, // null parent => not in menus
                $displayName . ' Settings',
                $displayName . ' Settings',
                'manage_options',
                $this->getSettingsSlug(), // slug
                array(&$this, 'settingsPage'));

            // Import/Export Page
            add_submenu_page(null, // null parent => not in menus
                $displayName . ' Import/Export',
                $displayName . ' Import/Export',
                'manage_options',
                $this->getImportExportSlug(), // slug
                array(&$this, 'displayImportExportPage'));
        }
    }


    /**
     * Handle the URL request for the WP dashboard admin page.
     * Handle any actions indicated in the URL GET parameters
     */
    function handleAdminPageUrl()
    {
        $this->securityCheck();

        require_once('AddActionsAndFilters_AdminPageActions.php');
        require_once('AddActionsAndFilters_DataModelConfig.php');
        include_once('AddActionsAndFilters_DataModel.php');

        // Set up Data Model
        $config = new AddActionsAndFilters_DataModelConfig();
        if (isset($_REQUEST['orderby'])) {
            $config->setOrderby($_REQUEST['orderby']);
        }
        if (isset($_REQUEST['order'])) {
            $config->setAsc($_REQUEST['order'] != 'desc');
        }
        $perPage = $this->getOption('NumberItemsPerPage', '10');
        $config->setNumberPerPage($perPage);


        // Init Table
        $dataModel = new AddActionsAndFilters_DataModel($config);
        include_once('AddActionsAndFilters_CodeListTable.php');
        $table = new AddActionsAndFilters_CodeListTable($dataModel);
        $table->prepare_items();

        // May be changed if a different page is to be displayed
        $showAdminPage = true;

        // Look for actions to be performed
        $action = $table->current_action();
        if ($action && $action != -1) {
            $actions = new AddActionsAndFilters_AdminPageActions();
            $ids = null;
            if (isset($_REQUEST['cb']) && is_array($_REQUEST['cb'])) {
                // check nonce which is on the bulk action form only
                if (wp_verify_nonce($_REQUEST['_wpnonce'])) {
                    $ids = $_REQUEST['cb'];
                }
            } else if (isset($_REQUEST['id'])) {
                $ids = array($_REQUEST['id']);
            }

            // Perform Actions
            if ($ids) {
                switch ($action) {
                    case $actions->getActivateKey():
                        $dataModel->activate($ids);
                        break;
                    case $actions->getDeactivateKey():
                        $dataModel->deactivate($ids);
                        break;
                    case $actions->getDeleteKey();
                        $dataModel->delete($ids);
                        break;
                    case $actions->getExportKey();
                        // todo: probably need a different mechanism
                        $dataModel->export($ids);
                        break;
                    case $actions->getEditKey();
                        $item = $dataModel->getDataItem($_REQUEST['id']);
                        $showAdminPage = false; // show edit page instead
                        $this->displayEditPage($item);
                        break;
                    default:
                        break;
                }
            }

        }

        // Display Admin Page
        if ($showAdminPage) {
            $this->displayAdminTable($table);
        }

    }

    /**
     * Display the Plugin's administration page in the WordPress Dashboard
     * @param $table AddActionsAndFilters_CodeListTable
     */
    function displayAdminTable(&$table)
    {
        require_once('AddActionsAndFilters_ViewAdminPage.php');
        $view = new AddActionsAndFilters_ViewAdminPage($this, $table);
        $view->display();
    }

    public function securityCheck()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'add-actions-and-filters'));
        }
    }

    public function settingsPage()
    {
        $this->securityCheck();
        require_once('AddActionsAndFilters_ViewSettingsPage.php');
        $view = new AddActionsAndFilters_ViewSettingsPage($this);
        $view->display();
    }

    public function displayEditPage($item)
    {
        $this->securityCheck();
        require_once('AddActionsAndFilters_ViewEditPage.php');
        $view = new AddActionsAndFilters_ViewEditPage($this);
        $view->display($item);
    }

    public function displayImportExportPage()
    {
        $this->securityCheck();
        require_once('AddActionsAndFilters_ViewImportExport.php');
        $view = new AddActionsAndFilters_ViewImportExport($this);
        $view->display();
    }


    // todo: move to an actions class
    public function ajaxSave()
    {
        if (current_user_can('manage_options')) {
            if (!headers_sent()) {
                // Don't let IE cache this request
                header("Pragma: no-cache");
                header("Cache-Control: no-cache, must-revalidate");
                header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

                header("Content-type: text/plain");
            }

            $code = stripslashes($_REQUEST['code']);

            // Save it as temporarily, potentially fatal code
            $this->updateOption('tmp_code', $code); // todo use data model
            die();
        } else {
            die(-1);
        }
    }

}
