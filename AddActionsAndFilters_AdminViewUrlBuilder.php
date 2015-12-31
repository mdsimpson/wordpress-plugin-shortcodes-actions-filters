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

class AddActionsAndFilters_AdminViewUrlBuilder
{

    var $params = array();

    /**
     * @param $key
     * @param $value
     */
    public function setParameter($key, $value)
    {
        $this->params[urlencode($key)] = urlencode($value);
    }

    /**
     * @return string url
     */
    public function buildUrl()
    {
        $url = get_admin_url() . 'admin.php?page=' . $_REQUEST['page'];

        // Parameters
        if (!empty($this->params)) {
            foreach ($this->params as $key => $value) {
                $url .= "&$key=$value";
            }
        }

        // Sorting
        if (isset($_REQUEST['orderby']) && $_REQUEST['orderby']) {
            $url .= '&orderby=' . $_REQUEST['orderby'];
        }
        if (isset($_REQUEST['order']) && $_REQUEST['order']) {
            $url .= '&order=' . $_REQUEST['order'];
        }

        // Pagination
        if (isset($_REQUEST['paged']) && $_REQUEST['paged'] > 1) {
            $url .= '&paged=' . $_REQUEST['paged'];
        }

        return $url;
    }

}