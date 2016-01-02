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

class AddActionsAndFilters_ViewImportExport
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
        $this->outputHeader();
        $this->outputExport();
        $this->outputImport();
        $this->outputPhpShortcodeExecListing();
    }

    public function outputHeader()
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
        </div>
        <?php
    }

    public function outputExport()
    {
        echo '<h3>';
        _e('Export All Code to a File', 'add-actions-and-filters');
        echo '</h3>';
        echo '<p>';
        _e('Export all code to a file. Use this to backup your code or transfer it to a different site.', 'add-actions-and-filters');
        echo '<br/>';
        _e('To export specific code items, go to the listing table, select those you want, and use the Export bulk action.', 'add-actions-and-filters');
        echo '</p>';
        ?>
        <?php submit_button(__('Export', 'add-actions-and-filters'), 'primary', 'exportcode'); ?>

        <script>
            jQuery(document).ready(function () {
                jQuery('#exportcode').click(function () {
                    window.location = "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_export";
                });
            });
        </script>
        <?php
    }

    public function outputBulkExport($ids)
    {
        ?>
        <script>
            jQuery(document).ready(function () {
                window.location = "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_export&ids=<?php
                    echo implode(',', $ids);
                    ?>";
            });
        </script>

        <?php
    }

    public function outputImport()
    {
        echo '<h3>';
        _e('Import Code from Export File', 'add-actions-and-filters');
        echo '</h3>';
        echo '<p>';
        _e('Import code from a file exported from this plugin.', 'add-actions-and-filters');
        echo '</p>';
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="importfile"/>
            <input type="file" name="importfile" id="importfile"/>
            <?php submit_button(__('Import', 'add-actions-and-filters')); ?>
        </form>
        <?php
    }


    public function outputPhpShortcodeExecListing()
    {
        $scep_names = get_option('scep_names');

        if (!is_array($scep_names)) {
            return;
        }
        if (!count($scep_names) > 0) {
            return;
        }
        ?>
        <h3><?php _e('Import from Shortcode Exec PHP Plugin', 'add-actions-and-filters'); ?></h3>
        <p><?php _e('The following shortcodes are found in "Shortcode Exec PHP" plugin:', 'add-actions-and-filters'); ?></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="import_scep"/>
            <?php
            foreach ($scep_names as $name) {
                ?>
                <input type="checkbox" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="true" checked>
                <label for="<?php echo $name; ?>"><?php echo $name; ?></label><br/>
                <?php
            }
            submit_button(__('Import Selected Shortcodes', 'add-actions-and-filters'), 'primary', 'submit');
            _e('After import, the shortcodes will be marked as disabled in Shortcode Exec PHP', 'add-actions-and-filters');
            ?>
        </form>
        <?php
    }


}