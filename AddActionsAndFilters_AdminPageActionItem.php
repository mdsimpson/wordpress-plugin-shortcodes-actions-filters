<?php

/*
    "WordPress Plugin Template" Copyright (C) 2013-2016 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

class AddActionsAndFilters_AdminPageActionItem {
    /**
     * @var string
     */
    var $key;

    /**
     * @var string
     */
    var $display;

    /**
     * AddActionsAndFilters_AdminPageActionItem constructor.
     * @param string $key
     * @param string $display
     */
    public function __construct($key, $display)
    {
        $this->key = $key;
        $this->display = $display;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }


}
