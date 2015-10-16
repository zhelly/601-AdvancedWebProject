<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		/* save user into database for first time successful login
		 * and create session for currently logged in user
		 *
		 * @param custom_canvas_user_id, lis_person_name_full, lis_person_contact_email_primary
		 */
		if ($request->isMethod('post')) {
			$user_id	 = $request->input('custom_canvas_user_id');
			$username	 =  $request->input('lis_person_name_full');
			$email 		 =  $request->input('lis_person_contact_email_primary');
			
            $user = User::find($user_id);
            if (!$user){
			$user = new User;
			$user->id			= $user_id;
			$user->username		= $username;
			$user->email 		= $email;
			$user->save();
            }
			
			session([
				'user_id'	 => $user_id,
				'username'	 => $username,
			]);
		}
		
		// get top voted user
		$data = [
			'top_user' => User::orderBy('vote', 'desc')->first(),
			'logged_user' => User::find(session('user_id'))
		];
		return view('index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
