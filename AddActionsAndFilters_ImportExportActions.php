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

require_once('AddActionsAndFilters_ViewImportExport.php');
require_once('AddActionsAndFilters_DataModel.php');

class AddActionsAndFilters_ImportExportActions
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;


    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function handleImpExp()
    {
        $view = new AddActionsAndFilters_ViewImportExport($this->plugin);

        if (isset($_REQUEST['action'])) {
            switch ($_REQUEST['action']) {

                case 'import_scep':
                    $view->outputHeader();
                    $this->importScepShortCodes();
                    break;

                case 'importfile':
                    $view->outputHeader();
                    $this->importFromFile();
                    break;

                default:
                    $view->display();
            }
        } else {
            $view->display();
        }

    }

    public function ajaxExport()
    {
        if (current_user_can('manage_options')) {
            if (!headers_sent()) {
                // Don't let IE cache this request
                header('Pragma: no-cache');
                header('Cache-Control: no-cache, must-revalidate');
                header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
                header('Content-type: application/json');
                header('Content-Disposition: attachment; filename="shortcode_actions_filters.json"');
            }
            $dataModel = new AddActionsAndFilters_DataModel($this->plugin, null);
            $ids = null;
            if (isset($_REQUEST['ids'])) {
                $ids = explode(',', $_REQUEST['ids']);
            }
            $codeItems = $dataModel->getDataItems($ids);
            if (defined('JSON_PRETTY_PRINT')) {
                echo json_encode($codeItems, JSON_PRETTY_PRINT);
            } else {
                echo json_encode($codeItems);
            }
            die();
        } else {
            die(-1);
        }
    }


    public function importFromFile()
    {
        _e('Imported Code Items: ', 'add-actions-and-filters');
        if (isset($_FILES['importfile']['tmp_name'])) {
            if (!$_FILES['importfile']['tmp_name']) {
                _e('No file specified', 'add-actions-and-filters');
                return;
            }
            $json = file_get_contents($_FILES['importfile']['tmp_name']);
            $json_array = json_decode($json, true);
            if (is_array($json_array)) {
                $dataModel = new AddActionsAndFilters_DataModel($this->plugin, null);
                $url_base = $this->plugin->getAdminPageUrl(); // . ;

                foreach ($json_array as $item) {
                    $item['stripslashes'] = false; // flag to not strip slashes before save
                    // Don't overwrite existing code items; insert new ones
                    $id = $dataModel->insertItem($item);
                    printf('<br/><a target="_blank" href="%s">%s</a>',
                        "$url_base&id=$id&action=edit",
                        $item['name']);
                }
            }
        }
    }

    public function importScepShortCodes()
    {
        $dataModel = new AddActionsAndFilters_DataModel($this->plugin, null);
        foreach ($_REQUEST as $key => $value) {
            if ($value == 'true') {
                $shortCode = array();
                $shortCode['shortcode'] = true;
                $shortCode['name'] = $key;
                $shortCode['description'] = get_option("scep_description_$key");
                $shortCode['enabled'] = get_option("scep_enabled_$key");
                $shortCode['buffer'] = get_option("scep_buffer_$key");
                $shortCode['code'] = get_option("scep_phpcode_$key");
                $shortCode['inadmin'] = 0;
                $shortCode['capability'] = '';
                //$buffer = get_option("scep_buffer_$key");
                //$param = get_option("scep_param_$key");

                $shortCode['stripslashes'] = false;
                $id = $dataModel->saveItem($shortCode);
                $url = $this->plugin->getAdminPageUrl() . "&id=$id&action=edit";
                echo __('Imported', 'add-actions-and-filters') . " <a target='_blank' href='$url'>$key</a></br>";

                // Deactivate SCEP shortcode
                update_option("scep_enabled_$key", 0);
            }
        }

    }
}