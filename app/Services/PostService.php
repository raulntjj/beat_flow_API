<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Feed;
use Illuminate\Support\Facades\DB;
use App\Traits\S3Operations;
use Exception;

class PostService {         
    use S3Operations;
    public function getAllPosts(array $params) {
        try {
            $query = Post::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }

            // Se passar valor para sortByEngagements
            if ($params['sortByEngagements']) {
                // o valor deverá conter ['asc', 'desc']
                $query->orderBy('id', $params['sortByEngagements']);
            // Se passar valor para sortByDate
            } else if ($params['sortByDate']) {
                // o valor deverá conter ['asc', 'desc']
                $query->orderBy('id', $params['sortByDate']);
            }

            if ($params['getAllData']) {
                $posts = $query->get();
            } else {
                $posts = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $posts]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getPost(int $id) {
        try {
            $post = Post::with([
                'engagements',
                'user',
		'comments',
            ])->find($id);

            if (!$post) {
                return response()->json(['status' => 'failed', 'response' => 'Post not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createPost(array $request) {
        try {
            $post = DB::transaction(function() use ($request) {
                if($request['media_path'] ?? false) {
                    $request['media_path'] = $this->storePostMedia($request['media_path']);
                } else {
                    $request['media_path'] = null;
                }

                return Post::create([
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
                    'visibility' => $request['visibility'],
                    'media_type' => $request['media_type'] ?? null,
                    'media_path' => $request['media_path'],
                ]);
            });


            // Criando um feed para a postagem criada
            Feed::create([
                'post_id' => $post->id,
                'shared_post_id' => null,
            ]);

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updatePost(array $request, int $id) {
        try {
            $post = DB::transaction(function() use ($id, $request) {
                $post = Post::find($id);
                
                if (!$post) {
                    throw new Exception("Post not found");
                }

                $old_media = $post->media_path;

                $post->fill([
                    'user_id' => $request['user_id'] ?? $user_id->user_id,
                    'content' => $request['content'] ?? $post->content,
                    'visibility' => $request['visibility'] ?? $post->visibility,
                    'media_type' => $request['media_type'] ?? $post->media_type,
                    'media_path' => $this->updatePostMedia($request['media_path'], $old_media),
                ])->save();

                return $post;
            });

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deletePost(int $id) {
        try {
            $post = DB::transaction(function() use ($id) {
                $post = Post::find($id);

                if (!$post) {
                    throw new Exception("Post not found");
                }

                $post->delete();

                return $post;
            });

            return response()->json(['status' => 'success', 'response' => 'Post deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
