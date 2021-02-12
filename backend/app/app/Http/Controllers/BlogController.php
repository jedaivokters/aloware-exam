<?php

namespace App\Http\Controllers;

use App\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller {

	private $blog;
	private $response;

	/**
	 * BlogController constructor.
	 * 
	 * @param Blog $blog
	 */
	public function __construct(Blog $blog) {
		$this->blog = $blog;
		$this->response = [
			'message' => '',
			'data' => '',
			'errors' => null
		];
		$this->dateNow = function () {
			return Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
		};
	}


	/**
	 * List down blog comments
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function comments(Request $request) {
		$blog = $this->blog->where('slug', $request->slug);

		$this->response['data'] = $blog->get();
		return response()->json($this->response);
	}

	public function commentStore(Request $request) {
		$dateNow = $this->dateNow; // Need to be in local variable to invoke closure

		$request->validate([
            'name' => 'required|max:100',
            'comment' => 'required'
        ], [
            "name.required" => __('common.name_required'),
            'comment.required' => __('common.comment_required')
		]);

		$path = '$';
		
		$newComment = [
			'name' => $request->name,
			'comment' => $request->comment,
			'date_time' => $dateNow(),
			'comments' => []
		];

		foreach($request->path as $p) {
			$path .= '.comments['.$p.']';
		};

		$cast = "CAST('".json_encode($newComment)."' AS JSON)";
		$jsonSet = DB::raw("JSON_ARRAY_INSERT(`comments`, '".$path."', ".$cast.")");

		// Add new comment
		$this->blog->where('slug', $request->slug)->update(['comments' => $jsonSet]);

		$this->response['data'] = $newComment;
		return response()->json($this->response);
	}


	public function blogs(Request $request) {
		$this->response['data'] = $this->blog->get(['id', 'title', 'slug']);
		
		return response()->json($this->response);
	}

	public function blogStore(Request $request) {
		$comments = [
			'comments' => []
		];

		$this->blog->insert([
			'title' => $request->title,
			'slug' => $request->slug,
			'comments' => json_encode($comments),
		]);

		return response()->json($this->response);
	}
}
