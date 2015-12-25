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

class AddActionsAndFilters_ViewImportExport
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    // todo: need item data model for saving & retrieving

    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function display()
    {
        ?>
        <div class="wrap">
        <table width="100%">
            <tbody>
            <tr>
                <td align="left"><h2><?php _e('Import / Export', 'add-actions-and-filters'); ?></h2></td>
                <td align="right">
                    <a href="<?php echo $this->plugin->getAdminPageUrl() ?>">
                        <img width="128" height="50"
                             src="<?php echo $this->plugin->getPluginFileUrl('img/icon-256x100.png') ?>">
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <?php


        // todo


        echo '</div>';
    }

}