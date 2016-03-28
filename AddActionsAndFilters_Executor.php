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

require_once('AddActionsAndFilters_ShortCode.php');

class AddActionsAndFilters_Executor {

    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    /**
     * @var array used as a temporary variable during code execution
     */
    var $codeItem;

    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param bool $is_admin code to be executed on an admin (dashboard) page
     * @return array|null|object
     */
    public function getCodeItemsToExecute($is_admin) {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = "select * from $table where enabled = 1";
        if ($is_admin) {
            $sql .= " and inadmin = 1";
        }
        $sql .= " order by id";
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * @param $codeItems array output from getCodeItemsToExecute()
     */
    public function executeCodeItems($codeItems) {
        register_shutdown_function(array(&$this, 'fatalErrorHandler'));
        $php7Plus = $this->isPhp7OrLater();
        foreach ($codeItems as $this->codeItem) {
            if ($this->codeItem['shortcode']) {
                $sc = new AddActionsAndFilters_ShortCode($this->plugin, $this->codeItem);
                $sc->register_shortcode();
            } else {
                // Execute PHP Code
                if ($php7Plus) {
                    try {
                        $result = eval($this->codeItem['code']);
                        if ($result === FALSE) {
                            $this->printErrorMessage($this->codeItem);
                        }
                    } catch (Throwable $ex) { // Throwable only in PHP 7+
                        $this->printErrorMessage($this->codeItem, $ex->getMessage());
                    }
                } else {
                    // Prior to PHP 7, does not throw Exception that can be caught
                    $result = eval($this->codeItem['code']);
                    if ($result === FALSE) {
                        $this->printErrorMessage($this->codeItem);
                    }
                }
            }
        }
        $this->codeItem = null;
    }

    public function fatalErrorHandler() {
        if ($this->codeItem) {
            $this->printErrorMessage($this->codeItem);
        }
    }

    /**
     * @param $codeItem array
     */
    public function printErrorMessage($codeItem, $msg = null) {
        echo '<div style="background-color: white; text-decoration-color: black">';
        echo '<p>&nbsp;</p>'; // helps prevent header from covering first line of output
        $url = $this->plugin->getAdminPageUrl() . "&id={$codeItem['id']}&action=edit";
        $name = $codeItem['name'] ? $codeItem['name'] : '(unamed)';
        echo '<p>';
        printf("%s Plugin: Error in user-provided code item named \"%s\". ",
                $this->plugin->getPluginDisplayName(),
                $name);
        echo '<br/>';
        if ($msg) {
            echo $msg;
            echo '<br/>';
        }
        printf("<u><a href='%s' target='_blank'>%s</a></u>",
                $url,
                __('Fix the code here', 'add-actions-and-filters'));
        echo '</p>';
        echo '<p>&nbsp;</p>';
        echo '</div>';
    }

    /**
     * Is the running PHP Version 7.0 or later?
     * @return bool
     */
    public function isPhp7OrLater() {
        $phpVersion = phpversion();
        return version_compare($phpVersion, '7.0', '>=');
    }

}
