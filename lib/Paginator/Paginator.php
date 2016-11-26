<?php

namespace Lib\Paginator;

use Config\Application\Adapter as Adapter;

/**
 * Paginator class
 * 
 * @autor Niesuch
 */
class Paginator extends Adapter {

    /**
     * SQL text
     * @var type 
     */
    private $_sql;

    /**
     * Record limit on the page
     * @var type 
     */
    private $_limit;

    /**
     * Current page
     * @var type 
     */
    private $_page;

    /**
     * Number of records
     * @var type 
     */
    private $_total;

    /**
     * Sort column
     * @var type 
     */
    private $_sort_column;

    /**
     * Sort order
     * @var type 
     */
    private $_sort_order;

    /**
     * Class construct
     * @param type $data
     */
    public function __construct($data) {
        parent::__construct();

        $this->_db->db_select($data['base']);
        $this->_sql = $this->_db->get_select($data['table'], $data['data']);
        $result = $this->_db->db_query($this->_sql);
        $this->_total = $result->num_rows;
    }

    /**
     * Get data from database
     * @param type $settings
     * @return type
     */
    public function getData($settings) {
        $this->_limit = $settings['limit'];
        $this->_page = $settings['page'];
        $this->_sort_column = $settings['sort_column'];
        $this->_sort_order = $settings['sort_order'];

        if ($this->_sort_column) {
            $this->_sql .= " ORDER BY " . $this->_sort_column . " " . $this->_sort_order;
        }
        if ($this->_limit != 'all') {
            $this->_sql .= " LIMIT " . $this->_limit . " OFFSET " . ($this->_page - 1) * $this->_limit;
        }

        return $this->_db->db_array($this->_sql);
    }

    /**
     * Return HTML with pagination
     * @param type $links
     * @param type $list_class
     * @return string
     */
    public function pagination($links, $list_class) {
        if ($this->_limit == 'all' || $this->_total == NULL) {
            return '';
        }

        $last = ceil($this->_total / $this->_limit);

        $start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;
        $end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;

        $html = '<ul class="' . $list_class . '">';
        $class = ($this->_page == 1) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ($this->_page - 1) . '&sort=' . $this->_sort_column . '&order=' . $this->_sort_order . '">&laquo;</a></li>';

        if ($start > 1) {
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=1' . '&sort=' . $this->_sort_column . '&order=' . $this->_sort_order . '">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->_page == $i) ? "active" : "";
            $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '&sort=' . $this->_sort_column . '&order=' . $this->_sort_order . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '&sort=' . $this->_sort_column . '&order=' . $this->_sort_order . '">' . $last . '</a></li>';
        }

        $class = ($this->_page == $last) ? "disabled" : "";
        $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ($this->_page + 1) . '&sort=' . $this->_sort_column . '&order=' . $this->_sort_order . '">&raquo;</a></li>';
        $html .= '</ul>';

        return $html;
    }

}
