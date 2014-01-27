<?php

/*
 * This file is part of the Moon Framework.
 *
 * (c) 2013 Lambdaweb - www.lambdaweb.fr
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Will defines filters to apply on a query, such as LIMIT, OFFSET, ORDER etc...
 * @author lambda2
 */
class QueryFilter {

    /* ----- constraints ------ */

    protected $limit=null;
    protected $offset=null;
    protected $orderColumn=null;
    protected $orderSort=null;
    protected $distinct=false;
    protected $distinctColumns=null;

    
    public function __construct()
    {
        
    }

    /**
     * Defines a limit of items for the result set.
     * @param $limit integer the limit to set
     * @return $this Entities self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    public function getLimit()
    {
    	return ($this->limit);
    }

    /**
     * Defines an offset to begin for the result set.
     * @param $offset integer the offset to set
     * @return $this Entities self
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
    
    public function getOffset()
    {
    	return ($this->offset);
    }

    /**
     * Defines an order to sort the result set.
     * @param $columns String the column(s) to sort by
     * @param $sort String the direction of sort (ASC or DESC)
     * @return $this Entities self
     * @see Entitites#setOrderSort
     */
    public function setOrder($columns,$sort=null)
    {
        $this->orderColumn = $columns;
        $this->orderSort = $sort;
        return $this;
    }

    public function getOrderColumn()
    {
    	return ($this->orderColumn);
    }

    /**
     * Defines a direction to sort the result set.
     * @param $sort String the direction of sort (ASC or DESC)
     * @return $this Entities self
     * @see Entitites#setOrder
     */
    public function setOrderSort($sort)
    {
        $this->orderSort = $sort;
        return $this;
    }

    public function getOrderSort()
    {
    	return ($this->orderSort);
    }


    /**
     * All the results will be distinct on the given column name.
     * @param $column String the columns who have to be distinct.
     * @return $this Entities self
     */
    public function distinct($column=null)
    {
        $this->distinct = true;
        $this->distinctColumns = $column;
        return $this;
    }

    public function getDistinct()
    {
    	return ($this->distinct);
    }

    public function getDistinctColumns()
    {
    	return ($this->distinctColumns);
    }


}
?>
