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

class AddActionsAndFilters_ShortCode
{

    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    /**
     * @var array
     */
    var $codeItem;

    /**
     * AddActionsAndFilters_ShortCode constructor.
     * @param AddActionsAndFilters_Plugin $plugin
     * @param array $codeItem
     */
    public function __construct(AddActionsAndFilters_Plugin $plugin, array $codeItem)
    {
        $this->plugin = $plugin;
        $this->codeItem = $codeItem;
    }

    public function register_shortcode() {
        add_shortcode($this->codeItem['name'], array($this, 'handle_shortcode'));
    }

    public function handle_shortcode($atts, $content = null)
    {
        if (isset($this->codeItem['capability']) &&
            $this->codeItem['capability'] &&
            !current_user_can($this->codeItem['capability'])) {
            // if a capability is required and the user doesn't have it,
            // then let the short code do nothing.
            return;
        }
        $result = eval($this->codeItem['code']);
        if ($result === FALSE) {
            $url = $this->plugin->getAdminPageUrl() . "&id={$this->codeItem['id']}&action=edit";
            printf("<p>%s Plugin: Error in shortcode [<u><a href='%s' target='_blank'>%s</a></u>]</p>",
                $this->plugin->getPluginDisplayName(),
                $url,
                $this->codeItem['name']);
        }

    }
}
