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
            $sql = "delete from $table where id in ( $placeholders )";
            $sql = $wpdb->prepare($sql, $ids);
        } else {
            $sql = "delete from $table where id = %d";
            $sql = $wpdb->prepare($sql, $ids);
        }
        return $wpdb->query($sql);
    }

    /**
     * @param $ids String|array of ids
     */
    public function export($ids)
    {
        if (is_array($ids)) {
            // todo
        }
        // todo: replace with DB query
    }

    /**
     * @return array associative
     */
    public function getDataItemList()
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = $wpdb->prepare("select id, enabled, shortcode, name, description from $table order by %s %s limit %d,%d",
            $this->config->getOrderby(),
            $this->config->isAsc() ? 'asc' : 'desc',
            $this->config->getPage() - 1,
            $this->config->getNumberPerPage()
        );
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
     * @param $item array
     * @return int|false
     */
    public function saveItem($item)
    {
        if (isset($item['id'])) {
//            return $this->updateItem($item);
            return $this->updateItem_viaSql($item);

        } else {
            return $this->insertItem($item);
        }
    }

    private function insertItem($item)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $data = array(
            'name' => stripslashes($item['name']),
            'description' => stripslashes($item['description']),
            'enabled' => $item['enabled'] === 'true' ? 1 : 0,
            'shortcode' => $item['shortcode'] === 'true' ? 1 : 0,
            'code' => stripslashes($item['code']),
        );
        $format = array('%s', '%s', '%d', '%d', '%s');
        $wpdb->insert($table, $data, $format);
        return $wpdb->insert_id;

    }

//    private function updateItem($item)
//    {
//        // todo: function doesn't work
//        global $wpdb;
//        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
//        $table = $this->plugin->getTableName();
//        $data = array(
//            'name' => stripslashes($item['name']),
//            'description' => stripslashes($item['description']),
//            'enabled' => $item['enabled'] === 'true' ? 1 : 0,
//            'shortcode' => $item['shortcode'] === 'true' ? 1 : 0,
//            'code' => stripslashes($item['code']),
//        );
//        $format = array('%s', '%s', '%d', '%d', '%s');
//        $where = array('id', $item['id']);
//        $where_format = array('%d');
//        $wpdb->update($table, $data, $where, $format, $where_format);
//        return $item['id'];
//    }


    private function updateItem_viaSql($item)
    {
        global $wpdb;
        $this->plugin->ensureDatabaseTableInstalled(); // ensure created in multisite
        $table = $this->plugin->getTableName();
        $sql = $wpdb->prepare("update $table set name = %s, description = %s, enabled = %d, shortcode = %d, code = %s where id = %d",
            stripslashes($item['name']),
            stripslashes($item['description']),
            $item['enabled'] === 'true' ? 1 : 0,
            $item['shortcode'] === 'true' ? 1 : 0,
            stripslashes($item['code']),
            $item['id']);
        $wpdb->query($sql);
        return $item['id'];
    }
}
