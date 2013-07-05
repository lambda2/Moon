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
 * A query class, for management of SQL queries.
 * @author lambda2
 */
class Query {


    /** @var $wcontraints contains all the [where] contraints */
    protected $wconstraints = array();

    /** @var $fcontraints contains all the [from] contraints */
    protected $fconstraints = array();

    /** @var $ficontraints contains all the [select] contraints */
    protected $ficonstraints = array();

    /** @var $endconstraints contains all the ending contraints like [LIMIT] */
    protected $endconstraints = array();

    /** @var $inconstraints contains all the contraints like [IN or NOT IN] */
    protected $inconstraints = array();

    /** @var $subQueries contain subqueries to be executed */
    protected $subQueries = array();


    /* ----------------- Common methods ------------------- */

    public function __construct() {

    }

    public function getLastTable()
    {
        if(count($this->fconstraints))
            return $this->fconstraints[count($this->fconstraints)-1];
        else
            return null;
    }

    public function clearQuery()
    {
        $this->wconstraints = array();
        $this->fconstraints = array();
        $this->ficonstraints = array();
        $this->endconstraints = array();
        $this->inconstraints = array();
        $this->subQueries = array();
    }

    public function cleanQ($field)
    {
        $pdo = Core::getBdd()->getDb();
        if(is_array($field))
        {
            $newArr = array();
            foreach($field as $k => $f)
            {
                $newArr[$k] = $pdo->quote($f);
            }
            return $newArr;
        }
        else {
            return $pdo->quote($field);
        }
    }

    /* ---------------- Query selectors ------------------ */


    /**
     * Add a [select] constraint to the current query.
     * @param $fields the fields to select
     * @return $this the current instance.
     * @since 0.0.4
     */
    public function select($fields)
    {
        $fields = explode(',',$fields);
        foreach ($fields as $f)
        {

            if(!in_array($f,$this->ficonstraints))
                $this->ficonstraints[] = $f;
        }
        return $this;
    }

    /**
     * Add a [where] constraint to the current query.
     * @param $field the field to be constrained
     * @param $value the value to contraint
     * @param $arg the contraint operator. default: [=]
     * @param $assoc the logical operand
     * @return $this the current instance.
     * @since 0.0.4
     * @TODO set a betted system to manipulate logical operands.
     * Currently, we can't.
     */
    public function where($field, $value, $arg='=', $assoc='AND',$escape=true)
    {
        if($escape)
            $value = $this->cleanQ($value);
        $elt = $this->tableQuote($field).' '.$arg.' '.$value.'::'.$assoc;
        if(!in_array($elt,$this->wconstraints))
            $this->wconstraints[] = $elt;
        return $this;
    }

    protected function tableQuote($field)
    {
        return '`'.implode('`.`',explode('.',$field)).'`';
    }

    /**
     * Add a [from] constraint to the current query.
     * @param $table the table to search
     * @return $this the current instance.
     * @since 0.0.4
     */
    public function from($table)
    {
        if(!in_array($table,$this->fconstraints))
            $this->fconstraints[] = $table;
        return $this;
    }

    public function in($field,$query, $clause = 'IN')
    {
        $this->inconstraints[] = array(
                'field' => $field,
                'query' => $query,
                'clause' => $clause
                );
    }

    /**
     * Add a [limit] constraint to the current query.
     * @param $limit the number of tuples to limit
     * @return $this the current instance
     * @since 0.0.4
     */
    public function limit($limit)
    {
        $this->endconstraints['limit'] = $limit;
        return $this;
    }

    /**
     * Add a [order by] constraint to the current query.
     * @param $columns the columns to order to
     * @param $order the order of sorting (asc or desc)
     * @return $this the current instance
     * @since 0.0.4
     */
    public function orderBy($columns,$order = 'asc')
    {
        $this->endconstraints['orderBy'] = array();
        $this->endconstraints['orderBy']['columns'] = $columns;
        $this->endconstraints['orderBy']['order'] = $order;
        return $this;
    }


    /* -------------- protected methods for query selectors ---------------- */

    public function getQuerySql()
    {
        if(!$this->checkContraintsForQuery())
            return false;

        $sql = $this->getSelectSql();
        $sql .= $this->getFromSql();
        $sql .= $this->getWhereSql();
        $sql .= $this->getEndingSql();
        return $sql;
    }

    /**
     * @return the [select] part of the defined sql request
     */
    protected function getSelectSql()
    {
        return ' SELECT '.implode(', ',$this->ficonstraints);
    }

    /**
     * @return the [from] part of the defined sql request
     */
    protected function getFromSql()
    {
        return ' FROM '.implode(', ',$this->fconstraints);
    }

    /**
     * @return the [where] part of the defined sql request
     */
    protected function getWhereSql()
    {
        if(count($this->wconstraints) == 0 and count($this->inconstraints) == 0)
            return '';
        $req = ' WHERE ';
        $first = True;
        foreach($this->wconstraints as $w)
        {
            $assoc = explode('::',$w);
            if($first)
            {
                $req .= $assoc[0];
                $first = false;
            }
            else
            {
                $req .= ' '.$assoc[1].' '.$assoc[0];
            }
        }
        foreach($this->inconstraints as $inConst)
        {
            if(!$first)
            {
                $req .= ' AND ';
            }
            else
                $first = false;
            $req .= $inConst['field'].' '.$inConst['clause'].' ('.$inConst['query']->getQuerySql().' )';
        }
        return $req;
    }

    protected function getEndingSql()
    {
        $rending = '';
        if(array_key_exists('orderBy',$this->endconstraints))
        {
            $rending .= ' ORDER BY '.$this->endconstraints['orderBy']['columns'];
            $rending .= ' '.$this->endconstraints['orderBy']['order'];
        }

        if(array_key_exists('limit',$this->endconstraints))
        {
            $rending .= ' LIMIT '.$this->endconstraints['limit'];
        }
        return $rending;
    }

    protected function decodeString($str)
    {
        $escapes = array('.','[',']');
        $repl = array('#dot#','#obra#','#ebra');
        $string = str_replace($repl,$escapes,$str);
        return $string;
    }

    public function convertConstraint($constraint)
    {
        $table = preg_replace(Entities::getFilter(),'',$constraint);
        $res = array();
        $isConstr = preg_match_all(Entities::getFilter(),$constraint,$res,PREG_SET_ORDER);
        if($isConstr > 0)
        {
            foreach($res as $aConstraint)
            {
                if(array_key_exists('expr',$aConstraint) and $this->decodeString($aConstraint['expr']) != '')
                {
                    $this->where(
                        $table.'.'.$aConstraint['attribute'],
                        $this->decodeString($aConstraint['expr']),
                        $aConstraint['operator'],
                        'AND',
                        false);
                }
                else
                {
                    $txt = $this->decodeString($aConstraint['num']);
                    if($txt == '')
                    {
                        $txt = $this->decodeString($aConstraint['text']); 
                    }
                    $this->where($table.'.'.$aConstraint['attribute'],$txt,$aConstraint['operator']);
                }
            }
        }
        return $this;
    }

    /**
     * Will check if all the constraints are defined 
     * in order to execute a query. For example, if
     * no table has been defined with [from()] method,
     * will return an horrible exception who will change
     * the little world where you're living now.
     * @return Boolean False if all constraints aren't set, true otherwise.
     * @throw OrmException if all constraints aren't set.
     */
    protected function checkContraintsForQuery()
    {
        return true;
        $valide = True;
        if(count($this->ficonstraints) == 0)
        {
            $valide = False;
            throw new OrmException("At least one field must be selected to perform the request");
        }
        else if(count($this->fconstraints) == 0)
        {
            $valide = False;
            throw new OrmException("At least one table must be defined in order to execute the query");
        }
        return $valide;
    }
}

?>
