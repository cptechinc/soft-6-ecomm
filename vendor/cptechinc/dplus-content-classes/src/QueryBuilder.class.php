<?php
    class QueryBuilder extends atk4\dsql\Query {
        /**
         * $sqlkeywords is a list of SQL keywords that will be shown in uppercase when we debug the query
         */
        protected $sqlkeywords = array(
            'select',
            'from',
            'where',
            'update',
            'insert',
            'between',
            'and',
            'order',
            'cast',
            'as',
            'by',
            'or', 
            'asc',
            'desc',
            'limit',
            'values',
            'into',
            'set',
            'is', 
			'in'
        );
        
        /**
         * Updates the Query builder Query with the column conditionals
         * Optionally adds the ORDER BY clause
         * Optiionally adds the LIMIT clause
         * @param  array  $querylinks [description]
         * @param  boolean $orderby    String of the orderby e.g. columnname-ASC
         * @param  boolean $limit      How many records to return ** OPTIONAL
         * @param  boolean $page       What Page Number to start from
         */
        public function generate_query(array $querylinks, $orderby = false, $limit = false, $page = false) {
    		foreach ($querylinks as $column => $val) {
                if (!empty($val)) {
                    $whereinfo = $this->generate_where($val);
                    switch ($whereinfo['type']) {
                        case '=':
                            if (sizeof($whereinfo['values']) == 1) {
                                $this->where($column, $whereinfo['values'][0]);
                            } else {
                                $this->where($column, $whereinfo['values']);
                            }                            
                            break;
                        case '!=':
                            $this->where($column, '!=', $whereinfo['values']);
                            break;
                        case '()':
                            $this->where($column, $q->expr('between "[]" and "[]"', $whereinfo['values']));
                            break;
                    }
                }
    		}
            
    		if ($limit) {
                $this->limit($limit, $this->generate_offset($page, $limit));
            }
            
            if (!empty($orderby)) {
                $this->order($this->generate_orderby($orderby));
            }
    	}
        
        public function generate_filters($filters, $filtertypes) {
            foreach ($filters as $filter => $filtervalue) {
                switch ($filtertypes[$filter]['querytype']) {
                    case 'between':
						$filtervalue = array_values(array_filter($filtervalue, 'strlen'));
						
						if (sizeof($filtervalue) == 1) {
                            $this->where($filter, $filtervalue[0]);
                        } else {
                            if ($filtertypes[$filter]['datatype'] == 'date') {
                                $this->where($this->expr("STR_TO_DATE($filter, '%m/%d/%Y') between STR_TO_DATE([], '%m/%d/%Y') and STR_TO_DATE([], '%m/%d/%Y')", $filtervalue));
                            } else if ($filtertypes[$filter]['datatype'] == 'numeric') {
                                $this->where($this->expr("$filter between CAST([] as UNSIGNED) and CAST([] as UNSIGNED)", $filtervalue));
                            } else {    
                                $this->where($this->expr("$filter between [] and []", $filtervalue));
                            }
                        }
                        break;
                    case 'in':
                        $this->where($filter, $filtervalue);
                        break;
                }
            }
        }
        
        /**
         * Parses $value to determine the type of column conditional to use 
         * such as if this is a between or a != 
         * @param  string $value with the conditional type followed by | and then value
         * @return array        returns type and values in  array. Values is an ArrayAccess
         *
         * Example:
         * $value = =|11
         * $return = array('type' => '=', 'values' = array(11));
         */
        public function generate_where($value) {
           $filter = false;
           if (strpos($value, '|') !== false) {
               $filter = explode('|', $value);
           }
           
           if ($filter) {
               $value = explode(',', $filter[1]);
               return array (
                   'type' => $filter[0],
                   'values' => $value
               );
           } else {
               $value = explode(',', $value);
               return array (
                   'type' => '=',
                   'values' => $value
               );
           }
       }
       
       /**
        * Loops through the array of key values and 
        * uses the $this->set('') to set the column to the new value
        * @param  array $querylinks associative array with the new corresponding values 
        */
       public function generate_setvaluesquery($querylinks) {
           foreach ($querylinks as $column => $val) {
               if (!empty($val)) {
                   $this->set($column, $val);
               }
           }
       }
       
       /**
        * Loops through the $new associative array to determine
        * if values are different at each key
        * @param  array  $old original associative array with key -> value
        * @param  array  $new updated associative array with key -> value
        */
       public function generate_setdifferencesquery(array $old, array $new) {
           foreach ($new as $column => $val) {
               if ($val != $old[$column]) {
                   $this->set($column, $val);
               }
           }
       }
        
        /** 
         * Returns the page offset by multiplying $page and $limit subtracted by $limit
         * @param int $page page number
         * @param int $limit number of records per page
         */
        public function generate_offset($page, $limit) {
            return $page > 1 ? ($page * $limit) - $limit : 0;
        }
        
        /**
         * Returns the Order By string by parsing the string 
         * into the format needed : column (ASC|DESC) or blank
         * @param  string $orderby e.g. columnname-ASC
         * @return string          Blank or columnname ASC
         */
        public function generate_orderby($orderby) {
            if (!empty($orderby)) {
                return str_replace('-', ' ', $orderby);
            } else {
                return '';
            }
        }
        
        /**
         * Parses the Paramterized query provided by $this->render()
         * Returns it in a Easy to read format with SQL keywords in CAPS and spaces after commas
         * @return string SQL Query
         */
        public function generate_sqlquery() {
            $sql = $this->render();
            $sql = str_replace(',', ', ', $sql);
			$sql = str_replace('!=', ' != ', $sql);
            $sql = str_replace('`=``', '` = `', $sql);
       		foreach ($this->params as $param => $value) {
       			$sql = str_replace($param, "'".$value."'", $sql);
       		}
            
            foreach ($this->sqlkeywords as $keyword) {
                $sql = preg_replace('/\b'.$keyword.'\b/', strtoupper($keyword), $sql);
            }
       		return $sql;
       	}
		
		public static function generate_filterdescription($key, $val, $filtertypes) {
			switch ($filtertypes[$key]['querytype']) {
				case 'between':
					$val = array_values(array_filter($val, 'strlen'));
					if (sizeof($val) == 1) {
						return " ".$filtertypes[$key]['label'] ." = " . $val[0];
					} else {
						return " ".$filtertypes[$key]['label'] . " between " . $val[0] . " and " . $val[1];
					}
					break;
				case 'in':
					$values = implode(', ', $val);
					return " ".$filtertypes[$key]['label'] ." IN ($values)";
					break;
			}
		}
    }
