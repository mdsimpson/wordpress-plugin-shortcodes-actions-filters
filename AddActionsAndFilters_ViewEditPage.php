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

class AddActionsAndFilters_ViewEditPage {
    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    public function __construct(&$plugin) {
        $this->plugin = $plugin;
    }

    public function display($item) {
        $this->outputCodeMirrorScriptsAndCss();
        $this->outputHeader();
        $this->outputCodeEditor($item);
    }


    /**
     * Add CodeMirror scripts for the code editor
     */
    public function outputCodeMirrorScriptsAndCss() {
        $libs = array(
                'lib/codemirror.js',
                'lib/codemirror.css',
                'addon/edit/matchbrackets.js',
                'mode/htmlmixed/htmlmixed.js',
                'mode/xml/xml.js',
                'mode/javascript/javascript.js',
                'mode/css/css.js',
                'mode/clike/clike.js',
                'mode/php/php.js',
        );
        $baseUrl = $this->plugin->getPluginFileUrl('codemirror-5.9');
        foreach ($libs as $lib) {
            if (substr($lib, -3) == '.js') {
                ?>
                <script src="<?php echo "$baseUrl/$lib" ?>"></script>
                <?php
            } else if (substr($lib, -4) == '.css') {
                ?>
                <link rel="stylesheet" href="<?php echo "$baseUrl/$lib" ?>">
                <?php
            }
        }
    }

    /**
     * Add top header table
     */
    public function outputHeader() {
        ?>
        <style type="text/css">
            .asaf_ltr {
                direction: ltr;
            }

            .asaf_hide {
                display: none;
            }
        </style>
        <div id="asaf_header" class="wrap asaf_ltr">
            <table width="100%">
                <tbody>
                <tr>
                    <td align="left"><h2><?php _e('Code Editor', 'add-actions-and-filters'); ?></h2></td>
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

    /**
     * Output the main contents of the page including the code editor and metadata fields
     * @param $item array created by AddActionsAndFilters_DataModel
     */
    public function outputCodeEditor($item) {
        ?>
        <div id="asaf_editor" class="wrap asaf_ltr">

            <table width="100%">
                <tbody>
                <tr>
                    <td valign="top">
                        <label for="name"><?php _e('Name') ?></label>
                    </td>
                    <td valign="top" nowrap>
                        <span id="sc_info_open" class="asaf_hide">[</span>
                        <input id="name" type="text" value="<?php echo isset($item['name']) ? $item['name'] : '' ?>"
                               size="25"/>
                        <span id="sc_info_close" class="asaf_hide">]</span>
                    </td>
                    <td valign="top">
                        <label for="description"><?php _e('Description') ?></label>
                    </td>
                    <td valign="top">
                        <textarea title="description" id="description"
                                  cols="80"><?php echo isset($item['description']) ? $item['description'] : '' ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="2">
                        <input type="checkbox" id="activated" name="activated"
                               value="true" <?php if (isset($item['enabled']) && $item['enabled']) echo 'checked' ?>>
                        <label for="activated"><?php _e('Activated') ?></label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="shortcode" name="shortcode"
                               value="true" <?php if (isset($item['shortcode']) && $item['shortcode']) echo 'checked' ?>>
                        <label for="shortcode"><?php _e('Shortcode') ?></label>
                    </td>
                    <td valign="top">
                        <label for="capability"><?php _e('Execute only for', 'add-actions-and-filters') ?></label>
                    </td>
                    <td valign="top">
                        <select id="capability">
                            <option value=""></option>
                            <?php
                            $cap_found = false;
                            foreach ($this->getCapabilityToRolesList() as $cap => $roles) {
                                $roles = implode(', ', $roles);
                                $selected = '';
                                if (isset($item['capability']) && $cap == $item['capability']) {
                                    $selected = 'selected';
                                    $cap_found = true;
                                }
                                echo "<option value=\"$cap\" $selected>$cap ($roles)</option>";
                            }
                            if (isset($item['capability']) && !$cap_found) {
                                echo "<option value=\"{$item['capability']}\" selected>{$item['capability']}</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                    </td>
                    <td>
                        <div id="af_info_inadmin" class="asaf_hide">
                            <input type="checkbox" id="inadmin" name="inadmin"
                                   value="true" <?php if (isset($item['inadmin']) && $item['inadmin']) echo 'checked' ?>>
                            <label for="inadmin"><?php _e('Execute also on Dashboard Pages', 'add-actions-and-filters') ?></label>
                        </div>
                        <div id="sc_info_buffer" class="asaf_hide">
                            <input type="checkbox" id="buffer" name="buffer"
                                   value="true" <?php if (!isset($item['buffer']) || (isset($item['buffer']) && $item['buffer'])) echo 'checked' ?>>
                            <label for="buffer"><?php _e('Code echoes output') ?></label>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>


            <div id="sc_info_instructions_open" class="asaf_hide">
                <code>function handle_shortcode ( $atts, $content = null ) {</code><br/>
            </div>
            <textarea title="code"
                      id="code"><?php echo isset($item['code']) ? htmlentities($item['code']) : '' ?></textarea>
            <div id="sc_info_instructions_close" class="asaf_hide">
                <code>}</code>
            </div>

            <script>
                var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "text/x-php",
                    indentUnit: 4,
                    indentWithTabs: true
                });
            </script>


            <div id="codesavestatus">&nbsp;</div>
            <?php submit_button('Save', 'primary', 'savecode'); ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery('#savecode').click(function () {
                        var item = {
                            <?php
                            if (isset($item['id'])) {
                                echo '"id": ' . $item['id'] . ',';
                            } ?>
                            "name": jQuery('#name').val(),
                            "description": jQuery('#description').val(),
                            "enabled": jQuery('#activated').is(':checked'),
                            "shortcode": jQuery('#shortcode').is(':checked'),
                            "buffer": jQuery('#buffer').is(':checked'),
                            "inadmin": jQuery('#inadmin').is(':checked'),
                            "capability": jQuery('#capability').val(),
                            "code": editor.getValue()
                        };
                        jQuery.ajax(
                                {
                                    "url": "<?php echo admin_url('admin-ajax.php') ?>?action=addactionsandfilters_save",
                                    "type": "POST",
                                    "data": item,
                                    "success": function (data, textStatus) {
                                        window.location.replace('<?php echo $this->plugin->getAdminPageUrl() ?>&id=' + data + '&action=edit');
                                    },
                                    "error": function (textStatus, errorThrown) {
                                        jQuery("#codesavestatus").html(textStatus.statusText);
                                        console.log(textStatus);
                                        console.log(errorThrown);
                                    },
                                    "beforeSend": function () {
                                        jQuery("#codesavestatus").html('<img src="<?php echo plugins_url('img/load.gif', __FILE__); ?>">');
                                    }
                                }
                        );
                    })
                });
            </script>

            <div id="sc_info_shortcode_instructions" class="asaf_hide">
                <table width="350px">
                    <tbody>
                    <tr>
                        <td><code>[shortcode arg="value"]</code></td>
                        <td><code>$atts['arg']</code></td>
                    </tr>
                    <tr>
                        <td><code>[shortcode]content[/shortcode]</code></td>
                        <td><code>$content</code></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="af_info_instructions" class="asaf_ltr asaf_hide">

            <?php
            $action_example = 'function email_friends( $post_ID ) {
    $friends = \'bob@example.org, susie@example.org\';
    wp_mail( $friends, "sally\'s blog updated", \'I just put something on my blog: http://blog.example.com\' );
    return $post_ID;
}
add_action( \'publish_post\', \'email_friends\' );';

            $filter_example = 'function prefix_title( $title, $id = null ) {
    return "The title is: $title";
}
add_filter( \'the_title\', \'prefix_title\', 10, 2 );';
            ?>
            <table>
                <tbody>
                <tr>
                    <td><h3 style="margin:0">Add Action</h3></td>
                    <td><a href="https://codex.wordpress.org/Function_Reference/add_action" target="_blank">add_action(
                            $hook, $function_to_add, $priority, $accepted_args );</a></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <pre><code><?php echo $action_example ?></code></pre>
                    </td>
                </tr>
                <tr>
                    <td><h3 style="margin:0">Add Filter</h3></td>
                    <td><a href="https://codex.wordpress.org/Function_Reference/add_filter" target="_blank">add_filter(
                            $tag, $function_to_add, $priority, $accepted_args );</a></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <pre><code><?php echo $filter_example ?></code></pre>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>

        <script>
            function displayShortCodeToggle() {
                if (jQuery('#shortcode').is(':checked')) {
                    jQuery('[id^=sc_info]').show();
                    jQuery('[id^=af_info]').hide();
                } else {
                    jQuery('[id^=sc_info]').hide();
                    jQuery('[id^=af_info]').show();
                }
            }
            displayShortCodeToggle();

            jQuery('#shortcode').click(function () {
                displayShortCodeToggle();
            });
            jQuery('#name').keyup(function () {
                displayShortCodeToggle();
            });
        </script>

        <?php
    }

    /**
     * @return array of capability => array[roles that have it]
     */
    public function getCapabilityToRolesList() {
        global $wp_roles;
        $capToRoles = array();
        foreach (array_reverse(array_keys($wp_roles->roles)) as $role) {
            foreach (array_keys($wp_roles->roles[$role]['capabilities']) as $cap) {
                if (strpos($cap, 'level_') === 0) {
                    continue; // skip deprecated roles "level_*"
                }
                if (!isset($capToRoles[$cap])) {
                    $capToRoles[$cap] = array();
                }
                $capToRoles[$cap][] = $role;
            }
        }
        return $capToRoles;
    }
}