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

require_once('AddActionsAndFilters_DataModelConfig.php');

class AddActionsAndFilters_DataModel
{

    /**
     * @var AddActionsAndFilters_Plugin
     */
    var $plugin;

    /**
     * @var AddActionsAndFilters_DataModelConfig
     */
    var $config;


    public function __construct($plugin, $config)
    {
        $this->plugin = $plugin;
        $this->config = $config;
    }

    /**
     * @param $ids String|array of ids
     * @param $bool boolean activate (true) or deactivate (false)
     * @return int|false number of rows affected or false
     */
    public function activate($ids, $bool)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $activate = $bool ? 'true' : 'false';
        if (is_array($ids)) {
            $count = count($ids);
            $placeholders = array_fill(0, $count, '%d');
            $placeholders = implode(', ', $placeholders);
            $sql = "update $table set enabled = $activate where id in ( $placeholders )";
            $sql = $wpdb->prepare($sql, $ids);
        } else {
            $sql = "update $table set enabled = $activate where id = %d";
            $sql = $wpdb->prepare($sql, $ids);
        }
        return $wpdb->query($sql);
    }

    /**
     * @param $ids String|array of ids
     * @return int|false number of rows affected or false
     */
    public function delete($ids)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        if (is_array($ids)) {
            $count = count($ids);
            $placeholders = array_fill(0, $count, '%d');
            $placeholders = implode(', ', $placeholders);
            $sql = "delete from $table where id in ( $placeholders )";
            $sql = $wpdb->prepare($sql, $ids);
        } else {
            $sql = "delete from $table where id = %d";
            $sql = $wpdb->prepare($sql, $ids);
        }
        return $wpdb->query($sql);
    }

    /**
     * @param null|false|string $search
     * @return array|null|object
     */
    public function getDataItemList($search = null)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $orderby = $this->config->getOrderby();
        $asc = $this->config->isAsc() ? 'asc' : 'desc';
        $numPerPage = $this->config->getNumberPerPage();
        $limit = sprintf(
            '%d,%d',
            ($this->config->getPage() - 1) * $numPerPage,
            $numPerPage
        );
        $sql = "select id, enabled, shortcode, inadmin, name, capability, description from $table";
        if ($search) {
            $param = "%$search%";
            $sql .= $wpdb->prepare(' where name like %s or description like %s', $param, $param);
        }
        $sql .= " order by $orderby $asc limit $limit";
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * @return int
     */
    public function getNumberOfDataItems()
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        return $wpdb->get_var("select count(id) from $table");
    }

    /**
     * @param $id int
     * @return array
     */
    public function getDataItem($id)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = $wpdb->prepare("select * from $table where id = %d", $id);
        return $wpdb->get_row($sql, ARRAY_A);
    }

    /**
     * @param $ids array|null
     * @return array|null|object
     */
    public function getDataItems($ids)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = "select * from $table ";
        if (is_array($ids) && !empty($ids)) {
            $sql .= ' where id in ( %d';
            $sql .= str_repeat(', %d', count($ids) - 1);
            $sql .= ' )';
            $sql = $wpdb->prepare($sql, $ids);
        }
        $sql .= ' order by id asc';
        return $wpdb->get_results($sql, ARRAY_A);
    }

    /**
     * @param $item array
     * @return int|false
     */
    public function saveItem($item)
    {
        if (isset($item['id'])) {
            return $this->updateItem($item);

        } else {
            return $this->insertItem($item);
        }
    }

    public function insertItem($item)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $data = array(
            'name' => stripslashes($item['name']),
            'description' => stripslashes($item['description']),
            'enabled' => $this->convertBooleanValue($item['enabled']),
            'shortcode' => $this->convertBooleanValue($item['shortcode']),
            'inadmin' => $this->convertBooleanValue($item['inadmin']),
            'capability' => stripslashes($item['capability']),
            'code' => stripslashes($item['code']),
        );
        $format = array('%s', '%s', '%d', '%d', '%s', '%s');
        $wpdb->insert($table, $data, $format);
        return $wpdb->insert_id;

    }

    protected function convertBooleanValue($value) {
        if (is_numeric($value)) {
            return $value;
        }
        if (is_bool($value)) {
            return $value ? 1 : 0;
        }
        return  $value === 'true' ? 1 : 0;
    }

    public function updateItem($item)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = $wpdb->prepare(
            "update $table set name = %s, description = %s, capability = %s, enabled = %d, shortcode = %d, inadmin = %d, code = %s where id = %d",
            stripslashes($item['name']),
            stripslashes($item['description']),
            stripslashes($item['capability']),
            $this->convertBooleanValue($item['enabled']),
                $this->convertBooleanValue($item['shortcode']),
                    $this->convertBooleanValue($item['inadmin']),
            stripslashes($item['code']),
            $item['id']);
        $wpdb->query($sql);
        return $item['id'];
    }
}
