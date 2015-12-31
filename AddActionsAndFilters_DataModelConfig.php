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

class AddActionsAndFilters_DataModelConfig
{

    const PER_PAGE_OPTION = 'AddActionsAndFilters_codeitems_per_page';
    const PER_PAGE_DEFAULT = 10;

    /**
     * @var int
     */
    var $page;

    /**
     * @var string column name to sort on
     */
    var $orderby;

    /**
     * @var bool ascending or descending sort
     */
    var $asc;

    /**
     * @var int for pagination
     */
    var $numberPerPage;

    /**
     * @var string
     */
    var $search;

    /**
     * AddActionsAndFilters_DataModelConfig constructor.
     * @param string $page
     * @param string $orderby
     * @param bool|true $asc
     */
    public function __construct($page = '1', $orderby = 'id', $asc = true)
    {
        $this->page = $page;
        $this->orderby = $orderby;
        $this->asc = $asc;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return string
     */
    public function getOrderby()
    {
        return $this->orderby;
    }

    /**
     * @param string $orderby
     */
    public function setOrderby($orderby)
    {
        $this->orderby = $orderby;
    }

    /**
     * @return boolean
     */
    public function isAsc()
    {
        return $this->asc;
    }

    /**
     * @param boolean $asc
     */
    public function setAsc($asc)
    {
        $this->asc = $asc;
    }

    /**
     * @return int
     */
    public function getNumberPerPage()
    {
        return $this->numberPerPage;
    }

    /**
     * @param int $numberPerPage
     */
    public function setNumberPerPage($numberPerPage)
    {
        $this->numberPerPage = $numberPerPage;
    }

    /**
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

}