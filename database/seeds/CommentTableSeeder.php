<?php

use Illuminate\Database\Seeder;
use App\Comment;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->truncate();
		
		Comment::create(array(
			'author' => 'Zelmira',
			'title'	=> 'Laravel',
			'text' => 'I know laravel.'
		));
    
		Comment::create(array(
			'author' => 'Zelmira2',
			'title'	=> 'Angular',
			'text' => 'agnular is cool.'
		));
    }
}
