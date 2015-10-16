<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use App\User;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		//$comments = Comment::orderBy('created_at','desc')->get();
		$comments = Comment::with('user')->orderBy('created_at','desc')->get();
		$nested = $this->buildTree($comments->toArray());
		
		return response()->json($nested);
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
		$comment = new Comment;
		$comment->user_id = session('user_id');
		$comment->title = $request->input('title');
		$comment->text = $request->input('text');
		
		if($request->input('parent_id'))
		{
			$comment->parent_id = $request->input('parent_id');
		}
		
		if($comment->save())
		{
			return response()->json(['success' => true]);
		}
		else
		{
			return response()->json(['success' => false]);
		}
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
		// if has child comment, cannot delete
		if(Comment::where('parent_id', $id)->count() > 0)
		{
			return response()->json([
				'code' => 500,
				'status' => 'Error',
				'message' => 'Cannot delete the post.'
			]);
		}
		else
		{
			$comment = Comment::find($id);
			
			Comment::destroy($id);
			
			return response()->json([
				'code' => 200,
				'status' => 'Success'
			]);
		}
    }
	
	public function postVoteUp(Request $request)
	{
		if(\App\Vote::where('user_id', $request->input('user_id'))->where('comment_id', $request->input('comment_id'))->count() > 0)
		{
			return response()->json([
				'code' => 500,
				'status' => 'Error',
				'message' => 'You have voted this!'
			]);
		}
		else
		{
			$user = User::find($request->input('user_id'));
			$user->vote = $user->vote + 1;
			$user->save();
			
			;
			\App\Vote::create([
				'user_id' 		=> $request->input('user_id'),
				'comment_id'	=> $request->input('comment_id'),
			]);
			
			return response()->json([
				'code' => 200,
				'status' => 'Success',
				'message' => 'Thanks for voting up!'
			]);
		}
	}
	
	public function postVoteDown(Request $request)
	{
		if(\App\Vote::where('user_id', $request->input('user_id'))->where('comment_id', $request->input('comment_id'))->count() > 0)
		{
			return response()->json([
				'code' => 500,
				'status' => 'Error',
				'message' => 'You have voted this!'
			]);
		}
		else
		{
			$user = User::find($request->input('user_id'));
			$user->vote = $user->vote - 1;
			if($user->vote < 0)
			{
				$user->vote = 0;
			}
			$user->save();
			
			;
			\App\Vote::create([
				'user_id' 		=> $request->input('user_id'),
				'comment_id'	=> $request->input('comment_id'),
			]);
			
			return response()->json([
				'code' => 200,
				'status' => 'Success',
				'message' => 'Thanks for voting down!'
			]);
		}
		
	}
	
	public function getLeaderboard()
	{
		$users = User::orderBy('vote','desc')->orderBy('username')->get();
		return view('leaderboard', ['users' => $users]);
	}
	
	// this is callback function for API comment nested
	public function buildTree(array $elements, $parentId = 0)
	{
		$branch = array();

		foreach ($elements as $element) {
			if ($element['parent_id'] == $parentId) {
				$children = $this->buildTree($elements, $element['id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}

		return $branch;
	}
}
