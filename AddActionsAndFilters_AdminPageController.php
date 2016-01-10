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


class AddActionsAndFilters_AdminPageController
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    /**
     * AddActionsAndFilters_AdminPageController constructor.
     * @param AddActionsAndFilters_Plugin $plugin
     */
    public function __construct(AddActionsAndFilters_Plugin $plugin)
    {
        $this->plugin = $plugin;
    }


    /**
     * Handle the URL request for the WP dashboard admin page.
     * Handle any actions indicated in the URL GET parameters
     */
    function handleAdminPageUrl()
    {
        $this->plugin->securityCheck();

        // set-screen-option callback - does not work
        // this is the work-around
        if (isset($_REQUEST['wp_screen_options']) && is_array($_REQUEST['wp_screen_options'])) {
            AddActionsAndFilters_ViewAdminPage::setScreenOptionCallback(
                $_REQUEST['wp_screen_options']['option'],
                $_REQUEST['wp_screen_options']['value']
            );
        }

        require_once('AddActionsAndFilters_DataModelConfig.php');
        require_once('AddActionsAndFilters_DataModel.php');

        // Look for Sorting, ordering and searching
        $config = new AddActionsAndFilters_DataModelConfig();
        if (isset($_REQUEST['orderby'])) {
            $config->setOrderby($_REQUEST['orderby']);
        }
        if (isset($_REQUEST['order'])) {
            $config->setAsc($_REQUEST['order'] != 'desc');
        }
        if (isset($_REQUEST['s'])) {
            $config->setSearch($_REQUEST['s']);
        }

        // Init DataModel and Table
        $dataModel = new AddActionsAndFilters_DataModel($this->plugin, $config);
        require_once('AddActionsAndFilters_CodeListTable.php');
        $table = new AddActionsAndFilters_CodeListTable($dataModel);

        // May be changed if a different page is to be displayed
        $showAdminPage = true;

        // Look for actions to be performed
        $action = $table->current_action();
        if ($action && $action != -1) {
            require_once('AddActionsAndFilters_AdminPageActions.php');
            $actions = new AddActionsAndFilters_AdminPageActions();
            $ids = null;
            if (isset($_REQUEST['cb']) && is_array($_REQUEST['cb'])) {
                // check nonce which is on the bulk action form only
                if ($table->verifyBulkNonce($_REQUEST['_wpnonce'])) {
                    $ids = $_REQUEST['cb'];
                }
            } else if (isset($_REQUEST['id'])) {
                $ids = array($_REQUEST['id']);
            } else if (isset($_REQUEST['ids'])) {
                $ids = explode(',', $_REQUEST['ids']);
            }

            // Perform Actions
            if ($action == $actions->getEditKey()) {
                $item = isset($_REQUEST['id']) ?
                    $dataModel->getDataItem($_REQUEST['id']) :
                    array();
                $showAdminPage = false; // show edit page instead
                $this->plugin->displayEditPage($item);
            } else if ($ids) {
                switch ($action) {
                    case $actions->getActivateKey():
                        $dataModel->activate($ids, true);
                        break;
                    case $actions->getDeactivateKey():
                        $dataModel->activate($ids, false);
                        break;
                    case $actions->getDeleteKey();
                        $dataModel->delete($ids);
                        break;
                    case $actions->getExportKey();
                        if (!empty($ids)) {
                            require_once('AddActionsAndFilters_ViewImportExport.php');
                            $view = new AddActionsAndFilters_ViewImportExport($this->plugin);
                            $view->outputBulkExport($ids);
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        // Display Admin Page
        if ($showAdminPage) {
            $table->prepare_items();
            $this->displayAdminTable($table);
        }

    }

    /**
     * Display the Plugin's administration page in the WordPress Dashboard
     * @param $table AddActionsAndFilters_CodeListTable
     */
    public function displayAdminTable(&$table)
    {
        require_once('AddActionsAndFilters_ViewAdminPage.php');
        $view = new AddActionsAndFilters_ViewAdminPage($this->plugin, $table);
        $view->display();
    }

    public function ajaxSave()
    {
        if (current_user_can('manage_options')) {
            if (ob_get_length()) {
                // eliminate any debug output from polluting the ajax return value
                // https://codex.wordpress.org/AJAX_in_Plugins
                ob_clean();
            }

            if (!headers_sent()) {
                // Don't let IE cache this request
                header('Pragma: no-cache');
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
                header('Content-type: text/plain');
            }

            require_once('AddActionsAndFilters_DataModelConfig.php');
            require_once('AddActionsAndFilters_DataModel.php');
            $config = new AddActionsAndFilters_DataModelConfig();
            $dataModel = new AddActionsAndFilters_DataModel($this->plugin, $config);
            $id = $dataModel->saveItem($_REQUEST);
            echo $id;
            die();
        } else {
            die(-1);
        }
    }

}