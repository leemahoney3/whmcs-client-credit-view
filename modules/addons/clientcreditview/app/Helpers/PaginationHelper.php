<?php

namespace LMTech\Addons\ClientCreditView\Helpers;

/**
 * WHMCS Client Credit View
 *
 * Allows clients to view their credit balance as well as previous
 * credit transactions on their account.
 *
 * @package    WHMCS
 * @author     Lee Mahoney <lee@leemahoney.dev>
 * @copyright  Copyright (c) Lee Mahoney 2022
 * @license    MIT License
 * @version    1.0.0
 * @link       https://leemahoney.dev
 */

if (!defined("WHMCS")) {
    exit("This file cannot be accessed directly");
}

class PaginationHelper {

    private $pageName;
    private $limit;
    private $model;

    private $where;
    private $joins;

    private $recordCount;
    private $pages;

    private $page;

    private $offset;
    private $orderBy;
    private $select;

    public function __construct($pageName = 'p', $where = [], $limit = 10, $model, $orderBy = [], $joins = [], $select = '') {

        $this->pageName     = $pageName;
        $this->where        = $where;
        $this->limit        = $limit;
        $this->model        = $model;
        
        $this->joins        = $joins;

        $this->recordCount  = $this->getRecordCount();
        
        $this->pages        = $this->recordCount / $this->limit;
        $this->page         = (int) ($_GET[$this->pageName] != null) ? $_GET[$this->pageName] : 1;

        $this->offset       = ($this->page - 1) * $this->limit;

        $this->orderBy      = $orderBy;
        $this->select       = $select;

    }

    public function data() {

        $result = $this->model::offset($this->offset)->limit($this->limit);

        if (!empty($this->where)) {
            $result->where($this->where);
        }

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $result->join($join[0], $join[1], $join[2], $join[3]);
            }
        }

        if (!empty($this->select)) {
            $result->select($this->select);
        }

        if ($this->orderBy[1] == 'asc' || $this->orderBy[1] == 'ASC') {

            return $result->orderBy($this->orderBy[0], 'asc')->get();

        } else if ($this->orderBy[1] == 'desc' || $this->orderBy[1] == 'DESC') {

            return $result->orderBy($this->orderBy[0], 'desc')->get();

        } else {

            return $result->get();

        }

    }

    public function links() {

        $html = '';

        if ($this->recordCount < ($this->limit + 1)) {
            $html .= '<div class="clearfix">';
            $html .= '<div class="text-sm-left float-left pull-left">Showing <b>' . $this->recordCount . '</b> out of <b>' . $this->recordCount . '</b> records</div>';
            $html .= '</div>'; 
        }

        if ($this->recordCount > $this->limit) {
   
            $html .= '
                <div class="clearfix">
                    <div class="text-sm-left float-left pull-left">Showing <b>' . $this->offset . '</b> to <b>' . ($this->page * $this->limit) . '</b> out of <b>' . $this->recordCount . '</b> records</div>
                    <ul style="margin: 0px 0px" class="pagination float-right pull-right">
            ';

            if($this->page == 1) {
                $html .= '<li class="page-item disabled"><a href="#" class="page-link">&laquo;</a></li>';
            } else {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, 1) . '" class="page-link">&laquo;</a></li>';
            }
            
            if ($this->page < 2) {
                $html .= '<li class="page-item disabled"><a href="#" class="page-link">Previous</a></li>';
            } else {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page - 1)) . '" class="page-link">Previous</a></li>';
            }

            if ($this->page - 2 > 0) {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page - 2)) . '" class="page-link">' . ($this->page - 2) . '</a></li>';
            }

            if ($this->page - 1 > 0) {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page - 1)) . '" class="page-link">' . ($this->page - 1) . '</a></li>';
            }

            $html .= '<li class="page-item active"><a href="' . $this->parseURL($this->pageName, $this->page) . '" class="page-link">' . $this->page . '</a></li>';

            if (($this->page + 1) < ($this->pages + 1)) {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page + 1)) . '" class="page-link">' . ($this->page + 1) . '</a></li>';
            }

            if (($this->page + 2) < ($this->pages + 1)) {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page + 2)) . '" class="page-link">' . ($this->page + 2) . '</a></li>';
            }

            if (($this->page + 1) < ($this->pages + 1)) {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, ($this->page + 1)) . '" class="page-link">Next</a></li>';
            } else {
                $html .= '<li class="page-item disabled"><a href="#" class="page-link">Next</a></li>';
            }

            if($this->page == round($this->pages)) {
                $html .= '<li class="page-item disabled"><a href="#" class="page-link">&raquo;</a></li>';
            } else {
                $html .= '<li class="page-item"><a href="' . $this->parseURL($this->pageName, round($this->pages)) . '" class="page-link">&raquo;</a></li>';
            }

            $html .= '
                    </ul>
                </div>    
            ';

        }

        return $html;


    }

    private function parseURL($parameter, $value) { 
        
        $params     = []; 
        $output     = '?';
        $firstRun   = true; 
        
        foreach($_GET as $key => $val) { 
            
            if($key != $parameter) { 
                
                if(!$firstRun) { 
                    $output .= '&'; 
                } else { 
                    $firstRun = false; 
                }

                $output .= $key . '=' . urlencode($val);

             }

        } 
    
        if(!$firstRun) {
            $output .= '&'; 
        }
            
        $output .= $parameter . '=' . urlencode($value); 

        return htmlentities($output); 
    
    }

    private function getRecordCount() {
       
        $result = $this->model::offset($this->offset)->limit($this->limit);

        if (!empty($this->where)) {
            $result->where($this->where);
        }

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $result->join($join[0], $join[1], $join[2], $join[3]);
            }
        }

        if (!empty($this->select)) {
            $result->select($this->select);
        }

        if ($this->orderBy[1] == 'asc' || $this->orderBy[1] == 'ASC') {

            return $result->orderBy($this->orderBy[0], 'asc')->count();

        } else if ($this->orderBy[1] == 'desc' || $this->orderBy[1] == 'DESC') {

            return $result->orderBy($this->orderBy[0], 'desc')->count();

        } else {

            return $result->count();
            
        }

    }

}