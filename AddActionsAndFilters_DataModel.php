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

require_once('AddActionsAndFilters_DataModelConfig.php');

class AddActionsAndFilters_DataModel
{
    /**
     * @var AddActionsAndFilters_DataModelConfig
     */
    var $config;

    /**
     * @var array
     */
    var $example_data; // todo: delete!

    public function __construct($config)
    {
        $this->config = $config;

        // todo: delete!
        $i = 1;
        $text = 'ssdfasfsfa lkjs dflkjasfkj aslkfjdalsjflkasjflkas jdflkasjfklasjflkasjflkasjdf';
        $this->example_data = array(
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 1, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '1', 'name' => 'Code' . $i++, 'description' => $text),
            array('enabled' => 0, 'id' => $i, 'shortcode' => '0', 'name' => 'Code' . $i++, 'description' => $text),
        );

    }

    /**
     * @param $ids String|array of ids
     */
    public function activate($ids) {
        if (is_array($ids)) {
            // todo
        }
        // todo: replace with DB query
    }

    /**
     * @param $ids String|array of ids
     */
    public function deactivate($ids) {
        if (is_array($ids)) {
            // todo
        }
        // todo: replace with DB query
    }

    /**
     * @param $ids String|array of ids
     */
    public function delete($ids) {
        if (is_array($ids)) {
            // todo
        }
        // todo: replace with DB query
    }

    /**
     * @param $ids String|array of ids
     */
    public function export($ids) {
        if (is_array($ids)) {
            // todo
        }
        // todo: replace with DB query
    }

    /**
     * @return array
     */
    public function getDataItemList()
    {
        // todo: replace with DB query
        // Requires PHP 5.3
        $sorter = new AddActionsAndFilters_Sorter($this->config->getOrderby(), $this->config->isAsc());
        $sorter->sort($this->example_data);

        $numPerPage = $this->config->getNumberPerPage();
        $page = $this->config->getPage();

        $dataSlice = array_slice($this->example_data, (($page-1)*$numPerPage), $numPerPage);

        return $dataSlice;
    }

    /**
     * @return int
     */
    public function getNumberDataItems() {
        return count($this->example_data);
    }

    /**
     * @param $id int
     * @return array
     */
    public function getDataItem($id) {
        foreach ($this->example_data as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return array();
    }
}

class AddActionsAndFilters_Sorter
{

    var $orderby = 'id';
    var $asc = true;

    public function __construct($orderby, $asc)
    {
        $this->orderby = $orderby;
        $this->asc = $asc;
    }

    public function sort(&$array)
    {
        usort($array, function ($a, $b) {
            if ($this->asc) {
                return strnatcmp($a[$this->orderby], $b[$this->orderby]);
            } else {
                return strnatcmp($b[$this->orderby], $a[$this->orderby]);
            }
        });
    }
}

