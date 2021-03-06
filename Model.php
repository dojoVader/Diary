<?php

namespace Plugin\Diary;

/**
 * This class is the Diary Database that will store and save information for the table Diary
 *
 * @author dojoVader
 *
 */
use Plugin\Diary\Helper as Helper;

class Model extends BaseModel {
	public $name="diary_blog";
	public $id;
	public $author, $date, $content, $title, $status;
	public $modified;
	public $comment;
	public $category_id;
	public $alias;
	public function save() {
		$this->beforeSave ();
		// Save Data
		$saveData = array (
				"author" => $this->author,
				"date" => $this->date,
				"content" => $this->content,
				"title" => $this->title,
				"status" => $this->status,
				"modified" => $this->modified,
				"comment" => $this->comment,
				"category_id" => $this->category_id,
				"alias"=>$this->alias

		);
		return ipDb ()->insert ( "diary_blog", $saveData );
	}
	public function getPaginator($table, $currentPageIdx,$pageSize,$front=false) {
    	// let's fetch the total from the Database first
    	/**
		@todo hardcoded value, change later
    	 */
    	$psize=$pageSize;
    	$recordCount = (int)ipDb ()->fetchValue ( sprintf ( "SELECT COUNT(*) from %s", ipTable ($table) ) );
    	$totalPages =(int) ceil ( $recordCount / $psize);
    	$currentPage = $currentPageIdx;
    	if ($currentPage > $totalPages) {
    		$currentPage = $totalPages;
    	}
    	$from = (abs($currentPage - 1)) * $psize;

    	//Empty Result
    	$pagination = new \Ip\Pagination\Pagination ( array (
    			'data'=>($front === true) ? $this->fetch($from, $psize,"status='1'") : $this->fetch($from, $psize),
    			'currentPage' => $currentPage,
    			'totalPages' => $totalPages,
    			'pagerSize' => $psize
    	) );
    	return $pagination;



    }

	private function fetch($from, $count, $where = 1) {

    	$sortField = 'date DESC';
    	// select ip_diary_blog.author,ip_diary_blog.date,ip_diary_blog.content,ip_diary_blog.id,title,ip_diary_blog.status,ip_diary_blog.category_id,dc.id as dcid ,dc.name 
    	// from ip_diary_blog INNER JOIN ip_diary_category dc ON ip_diary_blog.category_id=dc.id

    	$sql = "select ip_diary_blog.author,ip_diary_blog.date,ip_diary_blog.content,
    	ip_diary_blog.id,ip_diary_blog.modified,ip_diary_blog.alias,title,ip_diary_blog.status,ip_diary_blog.category_id,dc.id as dcid ,dc.name 
    	from ip_diary_blog INNER JOIN ip_diary_category dc ON ip_diary_blog.category_id=dc.id WHERE $where ORDER BY " . $sortField . "
                LIMIT
                $from, $count
                ";
      
                $result = ipDb ()->fetchAll ( $sql );




    		return $result;
    }





	public function update() {
		$this->isNewRecord=true;
		$this->beforeSave ();
		$updateData = array (
				"author" => $this->author,
				"content" => $this->content,
				"title" => $this->title,
				"status" => $this->status,
				"modified" => $this->modified,
				"comment" => $this->comment,
				"category_id" => $this->category_id,
				"alias"=>str_replace(" ","_",strip_tags($this->title))
		);
			$this->isNewRecord=false;
		return ipDb ()->update ( "diary_blog", $updateData, array (
				"id" => $this->id
		) );
	}

	/**
	 * Returns Articles from the Database
	 *
	 * @return multitype:
	 */
	public function getArticles() {
		return Helper::getList ( ipGetOption ( 'Diary.diaryPosts' ) );
	}
	public function getArticleById($id) {
		return Helper::getArticleById ( $id );
	}
	public function getArticleByAlias($id) {
		return Helper::getArticleByAlias( $id );
	}
	public function Delete($id) {
		return Helper::DeleteNote ( $id );
	}
	private function beforeSave() {
		if($this->isNewRecord):
		$this->modified = date('Y-m-d H:i:s',time());
		$this->author = Helper::getAuthor ();
		//Create alias for the page
		$this->alias=str_replace(" ","_",strip_tags($this->title));
		endif;
	}
}

?>