<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pages
 *
 * @author User
 */
class Page {
    private $page = 0;
    private $limit = 0;
    private $lenght = 0;
    
    public function __construct($limit, $lenght, $page=NULL)
    {
        if(isset($lenght) && isset($lenght))
        {
            $this->limit = $limit;
            $this->lenght = $lenght;
        }
        if(isset($page))
        {
            $this->setPage($limit, $lenght, $page);
        }
        else
        {
            if(isset($_SESSION['page']) && $_SESSION['page'] >= 0 && $_SESSION['page']*$this->limit < $this->lenght)
            {
                    $this->page = $_SESSION['page'];
            }
            else
            {
                $page = 0;
                $_SESSION['page'] = 0;
            }
        }
    }
    
    public function setPage($limit, $lenght, $page)
    {
        if(isset($page) && $page >= 0 && $page*$limit < $lenght)
        {
            $this->limit = $limit;
            $this->lenght = $lenght;
            $this->page = $page;
            $_SESSION['page'] = $page;
        }
        
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getOffset()
    {
        return $this->limit*$this->page;
    }
    
    public function getPage()
    {
        return $this->page;
    }
}
