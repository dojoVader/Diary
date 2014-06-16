<?php
namespace Plugin\Diary;
use Plugin\Diary\Forms\CommentForm;
use Plugin\Diary\CommentModel;
class SiteController extends \Ip\Controller

{

    public function read()
    {   ipAddCss("assets/css/comment.css");
    	ipAddJsContent("create","
            require(['Diary/CommentController'],function(comment){
                comment.init();

                });
                ");
		$article=new Model();
		$id=(int)ipRequest()->getQuery('post');
		$commentForm=Helper::getCommentForm();
        $commentForm->getField('post_id')->setValue($id);
		$data=$article->getArticleById($id);
		//Let's Fetch comment from the Database

		$data['form']=$commentForm;

        //Fetch Commentmodel
        $comment=new CommentModel();
        $paginator=$comment->getComments()->render(__DIR__."/view/frontend/comments.php");
       
        $data['comments']=$paginator;

		return ipView(__DIR__."/view/frontend/_article.php",$data)->render();


    }

    public function commentSave(){
    	//Comment Request
    	if (ipRequest ()->isPost ()) {
    		$form = Helper::getCommentForm();
    		$errors = $form->validate ( ipRequest ()->getPost () );
    		if ($errors) {
    			// error
    			$status = array (
    					'status' => 'error',
    					'errors' => $errors,
    					'message'=>"An Error occurred on the Server"
    			);
    		} else {
    			// success
    			$comment= new CommentModel();
    			$comment->email=ipRequest()->getPost("email");
    			$comment->url=ipRequest()->getPost("url");
    			$comment->author=ipRequest()->getPost("author");
    			$comment->content=ipRequest()->getPost("content");
                $comment->post_id=(int)ipRequest()->getPost('post_id');
    			try{
    				if ($id = $comment->save()) {
    					// Redirect to the Edit Page
    					//Success
    					$status=array(
    							"status"=>"success",
    							"message"=>"The Comment has been delivered,awaiting moderation"
    					);
    				}
    			}
    			catch(\Ip\Exception\Db $db){
    				/**
    				 *@todo Send Error to Admin or Show when on Developer Mode
    				 */
    				$status["status"]="error";
    				$status["message"]=$db->getMessage(). " at ".$db->getLine();
    			}
    			catch(\Exception $e){
    				$status["status"]="error";
    				$status["message"]=$e->getMessage(). " at ".$e->getLine();
    			}
    		}
    	}
    	return new \Ip\Response\Json($status);
    }









}
