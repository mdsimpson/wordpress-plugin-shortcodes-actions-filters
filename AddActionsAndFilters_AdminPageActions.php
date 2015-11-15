<?php

/*
    "WordPress Plugin Template" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

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

class AddActionsAndFilters_AdminPageActions
{

    /**
     * @return string
     */
    public function getActivateKey()
    {
        return 'activate';
    }

    /**
     * @return string
     */
    public function getActivateDisplayString()
    {
        return __('Activate', 'add-actions-and-filters');
    }

    /**
     * @return string
     */
    public function getDeactivateKey()
    {
        return 'deactivate';
    }

    /**
     * @return string
     */
    public function getDeactivateDisplayString()
    {
        return __('Deactivate', 'add-actions-and-filters');
    }

    /**
     * @return string
     */
    public function getEditKey()
    {
        return 'edit';
    }

    /**
     * @return string
     */
    public function getEditDisplayString()
    {
        return __('Edit', 'add-actions-and-filters');
    }

    /**
     * @return string
     */
    public function getDeleteKey()
    {
        return 'delete';
    }

    /**
     * @return string
     */
    public function getDeleteDisplayString()
    {
        return __('Delete', 'add-actions-and-filters');
    }

    /**
     * @return string
     */
    public function getExportKey()
    {
        return 'export';
    }

    /**
     * @return string
     */
    public function getExportDisplayString()
    {
        return __('Export', 'add-actions-and-filters');
    }

    /**
     * @return array
     */
    public function getBulkActionsKeyToDisplayStringArray()
    {
        return array(
            $this->getActivateKey() => $this->getActivateDisplayString(),
            $this->getDeactivateKey() => $this->getDeactivateDisplayString(),
            //$this->getEditKey() => $this->getEditDisplayString(),
            $this->getExportKey() => $this->getExportDisplayString(),
            $this->getDeleteKey() => $this->getDeleteDisplayString()
        );
    }

}