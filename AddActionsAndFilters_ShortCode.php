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

class AddActionsAndFilters_ShortCode
{
    /**
     * @var String
     */
    var $name;

    /**
     * @var String
     */
    var $code;

    /**
     * @var String
     */
    var $capability;

    /**
     * AddActionsAndFilters_ShortCode constructor.
     * @param String $name
     * @param String $code
     * @param String $capability
     */
    public function __construct($name, $code, $capability = null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->capability = $capability;
    }

    public function register_shortcode() {
        add_shortcode($this->name, array($this, 'handle_shortcode'));
    }

    public function handle_shortcode($atts, $content = null)
    {
        if ($this->capability && !current_user_can($this->capability)) {
            // if a capability is requried and the user doesn't have it,
            // then let the short code do nothing.
            return;
        }
        eval($this->code); // May raise FATAL error
    }
}