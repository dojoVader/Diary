<?php

namespace Plugin\Diary;
/**
 * This serves as a Base Class for all Model to inherit to avoid Code Duplication
 * @author x64
 * @version 1.0
 */
class BaseModel {
 /**
     * This returns the Paginator holding the data
     *
     * @param string name of the table
     * @param int CurrentPage
     * @param int PageSize the number of Items to fetch
     * @return IP\Pagination if result is found and FALSE if empty result
     */
    /**
     * Flags the Model that the Record is being Updated
     * */
    public $isNewRecord=false;
    public function getPaginator($table, $currentPageIdx,$pageSize) {
    	// let's fetch the total from the Database first
    	/**
		@todo hardcoded value, change later
    	 */
    	$pageSize=30;
    	$recordCount = (int)ipDb ()->fetchValue ( sprintf ( "SELECT COUNT(*) from %s", ipTable ($table) ) );
    	$totalPages =(int) ceil ( $recordCount / $pageSize);
    	$currentPage = $currentPageIdx;
    	if ($currentPage > $totalPages) {
    		$currentPage = $totalPages;
    	}
    	$from = (abs($currentPage - 1)) * $pageSize;

    	//Empty Result
    	$pagination = new \Ip\Pagination\Pagination ( array (
    			'data'=>$this->fetch($from, $pageSize),
    			'currentPage' => $currentPage,
    			'totalPages' => $totalPages,
    			'pagerSize' => $pageSize
    	) );
    	return $pagination;



    }

    private function fetch($from, $count, $where = 1) {

    	$sortField = 'id';


    	$sql = "
        SELECT
          *
        FROM
          " . ipTable($this->name) . "
        WHERE
          " . $where . "
        ORDER BY
            `" . $sortField . "`
                LIMIT
                $from, $count
                ";

                $result = ipDb ()->fetchAll ( $sql );


    		return $result;
    	}

    /**
     * This function checks if the Column exists in the Database
     * @param $col String Column of the Table
     * @param $key Value of the Column searching against
     * @return bool TRUE | FALSE
     */
    public function keyExists($col,$key){
        return (ipDb()->selectRow($this->name,$col,array('name'=>$key)) === null ) ? false : true;
    }
}

?>