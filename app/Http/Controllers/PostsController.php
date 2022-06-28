<?php

namespace App\Http\Controllers;

use App\Posts;
use App\Post;
use Corcel\Model\Taxonomy;
use Corcel\Tests\Unit\Model\Category;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function index()
    {
	    $posts = Posts::published()->newest()->paginate(5);
        // If we want to use the same method for API
	    //$posts = $this->getAllPost(Request::create('string', 'GET', ['per_page' => 2]));
        return view('posts.index',compact('posts'));
    }

    public function welcome()
    {
	    $request = new Request();
	    $request->per_page = 6;
	    $latest = $this->getAllPost($request);

	    return view('posts.welcome',compact('latest'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $post = new \Corcel\Model\Post($request->all());
        $post->save();
        return $post;
    }

    public function show($id)
    {
	    //$post = $this->getSinglePost($id);

	    $key = 'post-'.$id;
	    $cachedFile = "data-216c912en9qw12/".$key.'.post';
	    $cacheLife = 10; // minutes

	    if (file_exists($cachedFile)) {
		    if (filemtime($cachedFile) > (time() - 60 * $cacheLife)) {
			    $post = unserialize(file_get_contents($cachedFile));
		    } else {
			    unlink($cachedFile);
			    $post = Post::published()->find($id);

			    $jsonResult = serialize($post);
			    file_put_contents($cachedFile, $jsonResult);
		    }
	    } else {
		    $post = Post::published()->find($id);

		    $jsonResult = serialize($post);
		    file_put_contents($cachedFile, $jsonResult);
	    }

	    if (!$post) {
		    abort(404);
	    }
	    $categorySlug = @array_key_first($post->terms['category']);
	    if($categorySlug) {
	    	$request = new Request();
	    	$request->per_page = 4;
	    	$request->category = $categorySlug;
	    	$related = $this->getAllPost($request);
	    }

	    $meta = $post->meta;
	    $youtube_video_id = null;
	    if($meta) {
		    foreach ($meta as $item) {
			    if($item->meta_key === 'youtube_video_id') {
			    	$youtube_video_id = $item->meta_value;
			    }
	    	}
	    }

	    $request = new Request();
	    $request->per_page = 6;
	    $latest = $this->getAllPost($request);

	    return view('posts.view',compact('post','related', 'categorySlug', 'latest', 'meta', 'youtube_video_id'));
    }

    public function edit(Posts $posts)
    {
        //
    }

    public function update(Request $request, Posts $posts)
    {
        //
    }

    public function destroy(Posts $posts)
    {
        //
    }

	public function getAllPost(Request $request){
		if ($request->per_page) {
			$per_page = $request->per_page;
		} else {
			$per_page = 10;
		}

		$year = $month = $day = $exclude = $category = $tag = $metaKey = $metaValue = $hasMeta = false;
		$requestParams = json_encode($request->all());
		$key = preg_replace("/[^A-Za-z0-9\-]/", "", json_encode(['per+page' => $request->per_page, 'category' => $request->category]));
		$cachedFile = "data-216c912en9qw12/".$key.'.txt';
		$cacheLife = 10; // minutes

		if ($request->title) {
			return Posts::where('post_title', 'like', '%' . $request->title. '%')->published()->newest()->paginate($per_page);
		}

		if (!empty($request->category)) {
			$category = $request->category;
		}

		if (!empty($request->tag)) {
			$tag = $request->tag;
		}

		if($request->date) {
			$dateArray = date_parse_from_format('Y-m-d', $request->date);

			if (!$dateArray['warning_count']) {
				$year = $dateArray['year'];
				$month = $dateArray['month'];
				$day = $dateArray['day'];
			}
		}
		if (!empty($request->exclude)) {
			if (is_array($request->exclude)) {
				$exclude = array_filter($request->exclude, 'ctype_digit');
			} else {
				$exclude = preg_replace("/[^0-9,]/", "", $request->exclude);
				$exclude = explode(',', $exclude);
				$exclude = array_filter($exclude);
			}
		}

		// filter by meta keys
		if ($request->meta_key && $request->meta_value) {

			if (file_exists($cachedFile)) {
				if (filemtime($cachedFile) > (time() - 60 * $cacheLife)) {
					$result = json_decode(file_get_contents($cachedFile));
				} else {
					unlink($cachedFile);
					$result = Posts::hasMeta($request->meta_key, $request->meta_value)->published()->newest()->paginate($per_page);

					$jsonResult = json_encode($result, JSON_PRETTY_PRINT);
					file_put_contents($cachedFile, $jsonResult);
				}
			} else {
				$result = Posts::hasMeta($request->meta_key, $request->meta_value)->published()->newest()->paginate($per_page);

				$jsonResult = json_encode($result, JSON_PRETTY_PRINT);
				file_put_contents($cachedFile, $jsonResult);
			}
			return $result;
		}

		if (file_exists($cachedFile)) {
			if (filemtime($cachedFile) > (time() - 60 * $cacheLife)) {
				$result = unserialize(file_get_contents($cachedFile));
			} else {
				unlink($cachedFile);
				$result = Posts::published()
					->when($category, function ($query, $category) {
						return $query->taxonomy('category', $category);
					})
					->when($tag, function ($query, $tag) {
						return $query->taxonomy('post_tag', $tag);
					})
					->when($year, function ($query, $year) {
						return $query->whereYear('post_date', '=', $year);
					})
					->when($month, function ($query, $month) {
						return $query->whereMonth('post_date', '=', $month);
					})
					->when($day, function ($query, $day) {
						return $query->whereDay('post_date', '=', $day);
					})
					->when($exclude, function ($query, $exclude) {
						return $query->whereNotIn('ID', $exclude);
					})
					->newest()
					->paginate($per_page);

				$jsonResult = serialize($result);
				file_put_contents($cachedFile, $jsonResult);
			}
		} else {
			$result = Posts::published()
				->when($category, function ($query, $category) {
					return $query->taxonomy('category', $category);
				})
				->when($tag, function ($query, $tag) {
					return $query->taxonomy('post_tag', $tag);
				})
				->when($year, function ($query, $year) {
					return $query->whereYear('post_date', '=', $year);
				})
				->when($month, function ($query, $month) {
					return $query->whereMonth('post_date', '=', $month);
				})
				->when($day, function ($query, $day) {
					return $query->whereDay('post_date', '=', $day);
				})
				->when($exclude, function ($query, $exclude) {
					return $query->whereNotIn('ID', $exclude);
				})
				->newest()
				->paginate($per_page);

			$jsonResult = serialize($result);
			file_put_contents($cachedFile, $jsonResult);
		}

		return $result;
	}

    public function getSinglePost(Request $request){
        return $post = Post::find($request->id);
    }

    public function getPostByCategory($category){
        return Taxonomy::category()->slug($category)->with('posts')->get();
    }

    public function postThumbnail($id){
        $post = Post::find($id);
        return $post->thumbnail;
    }
}
