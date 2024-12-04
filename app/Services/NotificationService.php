<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Exception;

class NotificationService {         
    private function generateContent($type, $notifier_name) {
        switch($type) {
            case 'like':
                return "{$notifier_name} curtiu sua publicação.";
                break;
            
            case 'share':
                return "{$notifier_name} compartilhou sua publicação.";
                break;

            case 'comment':
                return "{$notifier_name} comentou na sua publicação.";
                break;

            case 'follow':
                return "{$notifier_name} seguiu você.";
                break;
            
            default:
                return "{$notifier_name} notificou você.";
                break;
        }
    }
    
    public function getAllNotifications(array $params) {
        try {
            $query = Notification::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $notifications = $query->get();
            } else {
                $notifications = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $notifications]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getNotification(int $id) {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['status' => 'failed', 'response' => 'Notification not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $notification]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createNotification(array $request) {
        try {
            $notification = DB::transaction(function() use ($request) { 
                return Notification::create([
                    'user_id' => $request['user_id'],
                    'type' => $request['type'],
                    'is_read' => $request['is_read'] ?? false,
                    'content' => $request['content'] ?? $this->generateContent(
                        $request['type'],
                        $request['notifier_name']
                    ),
                ]);
            });

            return response()->json(['status' => 'success', 'response' => $notification]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    // public function updateNotification(array $request, int $id) {
    //     try {
    //         $notification = DB::transaction(function() use ($id, $request) {
    //             $notification = Notification::find($id);
                
    //             if (!$notification) {
    //                 throw new Exception("Notification not found");
    //             }

    //             $notification->fill([
    //                 'user_id' => $request['user_id'] ?? $notification->user_id,
    //                 'type' => $request['type'] ?? $notification->type,
    //                 'is_read' => $request['is_read'] ?? $notification->is_read,
    //                 'content' => $request['content'] ?? $notification->content,
    //             ])->save();

    //             return $notification;
    //         });

    //         return response()->json(['status' => 'success', 'response' => $notification]);
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
    //     }
    // }

    public function deleteNotification(int $id) {
        try {
            $notification = DB::transaction(function() use ($id) {
                $notification = Notification::find($id);

                if (!$notification) {
                    throw new Exception("Notification not found");
                }

                $notification->delete();

                return $notification;
            });

            return response()->json(['status' => 'success', 'response' => 'Notification deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

	public function readNotification(int $id){
		try {
			 $notification = DB::transaction(function () use ($id) {
		            $notification = Notification::find($id);

		            if (!$notification) {
		                throw new Exception("Notification not found");
		            }

		            $notification->is_read = true;
		            $notification->save();

		            return $notification;
		        });
		} catch (Exception $e) {
        	    return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        	}	
	}
}
