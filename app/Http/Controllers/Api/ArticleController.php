<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use Validator;

class ArticleController extends Controller
{   
        /**
          * @OA\Post(
          * path="/api/article",
          * operationId="getARTICLE",
          * tags={"article"},
          * summary="get all article",
          * description="this route is responsible for fetching all artiles in apagination batch of 10 articles in each batch",
          * @OA\Property(property="request", type="object"),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully fetched articles",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not fetch any Article at this moment"),
          * )
          */
       
    function index(Request $request){
        $query = $request->all();
        if(array_key_exists('perpage', $query)){
            //check if perpage is in query string
            $perpage = $query["perpage"];
        }else {
            $perpage = 10;
        }

        if(array_key_exists('page', $query)){
            //check if page is in query string
            $page = $query["page"];
        }else {
            $page = 1;
        }
        $getAllArticles =  Article::with("comment","tag")->orderBy('id', 'DESC')->paginate($perpage)->toarray();
        if(!empty($getAllArticles['data'])){
            $totalpages = ceil($getAllArticles["total"]/$perpage);
            return response()->json(['status'=>'success', 'message'=>'Article fetched with pagination', 'data'=>$getAllArticles,  'totalpages'=>$totalpages, 'perpage'=>$perpage],200);
        }
        return response()->json(['status'=>'success', 'message'=>'Sorry we could not fetch any Article at this moment'  ],400);
        
    }

    /**
          * @OA\Get(
          * path="/api/article/{id}",
          * operationId="singleArticle",
          * tags={"singleArticle"},
          * summary="get all article",
          * description="This route is responsible  for the fetching of a single article inclusive with all associated tags and comments",
          * @OA\Property(property="id", type="integer"),
          *      @OA\Response(
          *          response=201,
          *          description="Successfully saved number of views and  fetched single articles",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=200, description="could not update number of views at this moment , but would still fetch article"),
          * )
          */
       
    function view($id){
        // when this endpoint is fetched the number of views is automatically increased
        $getASingleArticle =  Article::where('id',$id)->with("comment","tag")->first();
        $getASingleArticle->views += 1;
        
        if($getASingleArticle->save()){
            return response()->json(['status'=>'success', 'message'=>'Article fetched, and view counter updated ', 'data'=>$getASingleArticle],201);
        }else{
            return response()->json(['status'=>'success', 'message'=>'Article fetched but could not update views', 'data'=>$getASingleArticle],200);
        }
        
    }


     /**
          * @OA\Get(
          * path="/api/article/{id}/comment",
          * operationId="singleArticleComment",
          * tags={"singleArticleComment"},
          * summary="get article comment",
          * description="This route is responsible  for the fetching of a single article comments",
          * @OA\Property(property="id", type="integer"),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully fetched all comments",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not fetch comments"),
          * )
          */
   
    function comment($id){
        $getSpecificArticleComment = Comment::where("article_id",$id)->get();
        if(!empty($getSpecificArticleComment)){
            return response()->json(['status'=>'success', 'message'=>'Comment fetched', 'data'=>$getSpecificArticleComment],200);
        }
        return response()->json(['status'=>'success', 'message'=>'Could not fetch Comment please try again'],400);
        
    }

    /**
          * @OA\Get(
          * path="/api/article/{id}/like",
          * operationId="singleArticleLikes",
          * tags={"singleArticleCommentLikes"},
          * summary="get article likes",
          * description="This route is responsible for the fetching of a single article likes",
          * @OA\Property(property="id", type="integer"),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully fetched likes",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not fetch likes"),
          * )
          */
   
    function like($id){
        $getSpecificArticleComment = Article::where("id",$id)->select('like')->pluck('like')->first();
        if(!empty($getSpecificArticleComment)){
            return response()->json(['status'=>'success', 'message'=>'Likes fetched', 'data'=>$getSpecificArticleComment],200);
        }
        return response()->json(['status'=>'success', 'message'=>'Could not fetch likes'],400);
    }

    /**
          * @OA\Get(
          * path="/api/article/{id}/view",
          * operationId="singleArticleViews",
          * tags={"singleArticleCommentViews"},
          * summary="get article views",
          * description="This route is responsible for the fetching of a single article views",
          * @OA\Property(property="id", type="integer"),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully fetched views",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not fetch views"),
          * )
          */
    function totalviews($id){
        
        $getSpecificArticleComment = Article::where("id",$id)->select('views')->pluck('views')->first();
        if(!empty($getSpecificArticleComment)){
            return response()->json(['status'=>'success', 'message'=>'Views fetched', 'data'=>$getSpecificArticleComment],200);
        }
        return response()->json(['status'=>'success', 'message'=>'could not fetch Views'],400);
    }

      /**
          * @OA\Post(
          * path="/api/article/{id}/comment/create",
          * operationId="newComment",
          * tags={"newComment"},
          * summary="create a new comment",
          * description="This route is responsible for the creation of a new comment for a perticlar article.",
          * @OA\Property(property="id", type="integer"),
          * @OA\Property(property="request", type="object"),
          * @OA\Parameter(
          *      name="subject",
          *      in="query",
          *      required=true,
          *      @OA\Schema(
          *           type="string"
          *      )
          *   ),
          * @OA\Parameter(
          *      name="body",
          *      in="query",
          *      required=true,
          *      @OA\Schema(
          *           type="string"
          *      )
          *   ),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully updated likes",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not update likes"),
          * )
          */
   
   
    public function newComment($id,Request $request){
        $comment = new Comment();
        $subjectValidator = Validator::make($request->all(),[
            'subject' => 'required',
        ]);
        
        if($subjectValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'SUbject  is required' ],400);
        }

        $bodyValidator = Validator::make($request->all(),[
        'body' => 'required',
        ]);
      
        if($bodyValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'body  is required' ],400);
        }

        $comment->article_id = $id;
        $comment->subject = $request->subject;
        $comment->body = $request->body;
        if($comment->save()){
            return response()->json(['status'=>'success', 'message'=>'Your message has been successfully sent', 'data'=>$comment],200);
        }

        return response()->json(['status'=>'error', 'message'=>'Sorry we could not send your message'],400);
        
    }


     /**
          * @OA\Get(
          * path="/api/article/{id}/like/increment",
          * operationId="updateSingleArticleLikes",
          * tags={"updateSingleArticleLikes"},
          * summary="update the likes of a single article",
          * description="This route is responsible for the updating of a single article likes",
          * @OA\Property(property="id", type="integer"),
          *      @OA\Response(
          *          response=200,
          *          description="Successfully updated likes",
          *          @OA\JsonContent()
          *       ),
          *    
          *      @OA\Response(response=400, description="could not update likes"),
          * )
          */
    public function updateLikes($id){
        $updateLikes = Article::where("id",$id)->first();
        $updateLikes->like += 1;
        if($updateLikes->save()){
            return response()->json(['status'=>'success', 'message'=>'Like was saved.','data' => $updateLikes],200);
        }
        return response()->json(['status'=>'error', 'message'=>'An error occured we could not like the above article, please try again.'],400);
    }
}
