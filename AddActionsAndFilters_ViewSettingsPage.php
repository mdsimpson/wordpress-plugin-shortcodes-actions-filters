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

require_once('AddActionsAndFilters_Plugin.php');

class AddActionsAndFilters_ViewSettingsPage
{
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    public function __construct(&$plugin)
    {
        $this->plugin = $plugin;
    }

    public function display()
    {

        $optionMetaData = $this->plugin->getOptionMetaData();

        // Save Posted Options
        if ($optionMetaData != null) {
            foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                if (isset($_POST[$aOptionKey])) {
                    $this->plugin->updateOption($aOptionKey, $_POST[$aOptionKey]);
                }
            }
        }

        // HTML for the page
        $settingsGroup = get_class($this->plugin) . '-settings-group';
        ?>
        <style type="text/css">
            table.asaf-options-table {
                width: 100%
            }

            table.asaf-options-table tr:nth-child(even) {
                background: #f9f9f9
            }

            table.asaf-options-table tr:nth-child(odd) {
                background: #FFF
            }

            table.asaf-options-table td {
                width: 350px
            }

            table.asaf-options-table td + td {
                width: auto
            }
        </style>
        <div class="wrap">
            <table width="100%">
                <tbody>
                <tr>
                    <td align="left"><h2><?php _e('System Settings', 'add-actions-and-filters'); ?></h2></td>
                    <td align="right">
                        <a href="<?php echo $this->plugin->getAdminPageUrl() ?>">
                        <img width="128" height="50"
                             src="<?php echo $this->plugin->getPluginFileUrl('img/icon-256x100.png') ?>">
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <table class="asaf-options-table">
                <tbody>
                <tr>
                    <td><?php _e('System', 'add-actions-and-filters'); ?></td>
                    <td><?php echo php_uname(); ?></td>
                </tr>
                <tr>
                    <td><?php _e('PHP Version', 'add-actions-and-filters'); ?></td>
                    <td><?php echo phpversion(); ?>
                        <?php
                        if (version_compare('5.2', phpversion()) > 0) {
                            echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                            _e('(WARNING: This plugin may not work properly with versions earlier than PHP 5.2)', 'add-actions-and-filters');
                            echo '</span>';
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><?php _e('MySQL Version', 'add-actions-and-filters'); ?></td>
                    <td><?php echo $this->getMySqlVersion() ?>
                        <?php
                        echo '&nbsp;&nbsp;&nbsp;<span style="background-color: #ffcc00;">';
                        if (version_compare('5.0', $this->getMySqlVersion()) > 0) {
                            _e('(WARNING: This plugin may not work properly with versions earlier than MySQL 5.0)', 'add-actions-and-filters');
                        }
                        echo '</span>';
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>

            <h2><?php echo $this->plugin->getPluginDisplayName();
                echo ' ';
                _e('Settings', 'add-actions-and-filters'); ?></h2>

            <form method="post" action="">
                <?php settings_fields($settingsGroup); ?>
                <table class="asaf-options-table">
                    <tbody>
                    <?php
                    if ($optionMetaData != null) {
                        foreach ($optionMetaData as $aOptionKey => $aOptionMeta) {
                            $displayText = is_array($aOptionMeta) ? $aOptionMeta[0] : $aOptionMeta;
                            ?>
                            <tr valign="top">
                                <th scope="row"><p><label
                                            for="<?php echo $aOptionKey ?>"><?php echo $displayText ?></label></p></th>
                                <td>
                                    <?php $this->createFormControl($aOptionKey, $aOptionMeta, $this->plugin->getOption($aOptionKey)); ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php _e('Save Settings', 'add-actions-and-filters') ?>"/>
                </p>
            </form>
        </div>
        <?php

    }


    /**
     * Helper-function outputs the correct form element (input tag, select tag) for the given item
     * @param  $aOptionKey string name of the option (un-prefixed)
     * @param  $aOptionMeta mixed meta-data for $aOptionKey (either a string display-name or an array(display-name, option1, option2, ...)
     * @param  $savedOptionValue string current value for $aOptionKey
     * @return void
     */
    protected function createFormControl($aOptionKey, $aOptionMeta, $savedOptionValue)
    {
        if (is_array($aOptionMeta) && count($aOptionMeta) >= 2) { // Drop-down list
            $choices = array_slice($aOptionMeta, 1);
            ?>
            <p><select name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>">
                    <?php
                    foreach ($choices as $aChoice) {
                        $selected = ($aChoice == $savedOptionValue) ? 'selected' : '';
                        ?>
                        <option
                            value="<?php echo $aChoice ?>" <?php echo $selected ?>><?php echo $this->plugin->getOptionValueI18nString($aChoice) ?></option>
                        <?php
                    }
                    ?>
                </select></p>
            <?php

        } else { // Simple input field
            ?>
            <p><input type="text" name="<?php echo $aOptionKey ?>" id="<?php echo $aOptionKey ?>"
                      value="<?php echo esc_attr($savedOptionValue) ?>" size="50"/></p>
            <?php

        }
    }

    /**
     * Query MySQL DB for its version
     * @return string|false
     */
    protected function getMySqlVersion()
    {
        global $wpdb;
        $rows = $wpdb->get_results('select version() as mysqlversion');
        if (!empty($rows)) {
            return $rows[0]->mysqlversion;
        }
        return false;
    }


}