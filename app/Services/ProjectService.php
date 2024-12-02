<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Feed;
use Illuminate\Support\Facades\DB;
use App\Traits\S3Operations;
use Exception;

class ProjectService {         
    use S3Operations;
    public function getAllProjects(array $params) {
        try {
            $query = Project::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $projects = $query->get();
            } else {
                $projects = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $projects]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getProject(int $id) {
        try {
            $project = Project::with([
                'owner',
                'participants',
            ])->find($id);

            if (!$project) {
                return response()->json(['status' => 'failed', 'response' => 'Project not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $project]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createProject(array $request) {
        try {
            $project = DB::transaction(function() use ($request) {
                if($request['media_path'] ?? false) {
                    $request['media_path'] = $this->storeProjectMedia($request['media_path']);
                } else {
                    $request['media_path'] = null;
                }

                if($request['cover_path'] ?? false) {
                    $request['cover_path'] = $this->storeCover($request['cover_path']);
                } else {
                    $request['cover_path'] = null;
                }

                return Project::create([
                    'name' => $request['name'],
                    'content' => $request['content'],
                    'owner_id' => $request['owner_id'],
                    'cover_path' => $request['cover_path'],
                    'media_type' => $request['media_type'] ?? null,
                    'media_path' => $request['media_path'],
                ]);
            });

            return response()->json(['status' => 'success', 'response' => $project]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateProject(array $request, int $id) {
        try {
            $project = DB::transaction(function() use ($id, $request) {
                $project = Project::find($id);
                
                if (!$project) {
                    throw new Exception("Project not found");
                }

                $old_media = $post->media_path;
                $old_cover = $post->media_path;

                $project->fill([
                    'name' => $request['name'] ?? $project->name,
                    'content' => $request['content'] ?? $project->content,
                    'owner_id' => $request['owner_id'] ?? $project->owner_id,
                    'cover_path' => $this->updateCover($request['cover_path'], $old_cover),
                    'media_type' => $request['media_type'] ?? $project->media_type,
                    'media_path' => $this->updatePostMedia($request['media_path'], $old_media),
                ])->save();

                return $project;
            });

            return response()->json(['status' => 'success', 'response' => $project]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteProject(int $id) {
        try {
            $project = DB::transaction(function() use ($id) {
                $userAuth = Auth::guard('api')->user();
                $project = Project::find($id);

                if (!$project) {
                    throw new Exception("Project not found");
                }

                if($project->owner_id != $userAuth->id){
                    throw new Exception("Forbbiden, user doesnt have permissions.");
                }

                $project->delete();

                return $project;
            });

            return response()->json(['status' => 'success', 'response' => 'Project deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
