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
		$comments = Comment::all();
		
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
		$comment->author = $request->input('author');
		$comment->title = $request->input('title');
		$comment->text = $request->input('text');
		
		if($request->input('parent_id'))
		{
			$comment->parent_id = $request->input('parent_id');
		}
		$comment->save();
		
		if(! User::where('author', $request->input('author'))->first())
		{
			$user = new User;
			$user->author = $request->input('author');
			$user->save();
		}
		
		return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
		$comment = Comment::find($id);
        
		Comment::destroy($id);
		
		if( Comment::where('author',$comment->author)->count() < 1 )
		{
			User::where('author',$comment->author)->delete();
		}
		return response()->json(['success' => true]);
    }
	
	public function postVoteUp(Request $request)
	{
		$user = User::where('author',$request->input('author'))->first();
		$user->vote = $user->vote + 1;
		$user->save();
	}
	
	public function postVoteDown(Request $request)
	{
		$user = User::where('author',$request->input('author'))->first();
		$user->vote = $user->vote - 1;
		$user->save();
	}
	
	public function getLeaderboard()
	{
		$users = User::orderBy('vote','desc')->get();
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
