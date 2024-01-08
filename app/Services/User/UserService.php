<?php

namespace  App\Services\User;

use App\Models\AdminTask;
use App\Models\AssignTask;
use App\Models\Bonus;
use App\Models\DeclineVideo;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Question;
use App\Models\SnapShotPoint;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserTask;
use App\Models\UserVideo;
use App\Models\UseTask;
use App\Models\withdraw;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Validator;
use stdClass;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use GuzzleHttp\Psr7\Request;
use Pusher\Pusher;

class USerService
{

    public function sendMessage($request)
    {
        $message = new Message();
        $message->user_id = $request->user_id;
        $message->type = $request->type;
        $message->message = $request->message;
        $message->sendBy = 'user';
        $message->time = strtotime(date('Y-m-d H:i:s'));
        $message->save();
        $user = User::find($request->user_id);
        // $pusher = new Pusher('97a86cd58ea79e2dcf60', 'b5483068e833f5277aaa', 1646483, [
        //     'cluster' => 'us3',
        //     'useTLS' => true,
        // ]);

        // $pusher->trigger($message->user_id, 'new-message', [
        //     'message' => $message,
        //     'sender' => $message->sendBy,
        //     'user'   => $user,
        // ]);
        return response()->json([
            'status' => true,
            'action' => "Message send",
        ]);
    }

    public function conversation($id)
    {
        $user = User::find($id);
        if ($user) {
            $conversation = Message::where('user_id', $id)->latest()->get();
            return response()->json([
                'status' => true,
                'action' => "Conversation",
                'data'  => $conversation
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function acceptTask($user_id, $task_id)
    {

        $user = User::find($user_id);
        if ($user) {
            $task = AdminTask::find($task_id);
            if ($task) {

                $acceptTask = new UserTask();
                $acceptTask->user_id = $user_id;
                $acceptTask->task_id = $task_id;
                $acceptTask->save();

                $assignTask = AssignTask::where('user_id', $user_id)->where('admin_task_id', $task_id)->latest()->first();
                $assignTask->is_accept = 1;
                $assignTask->save();

                return response()->json([
                    'status' => true,
                    'action' => "Task accepted",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "Task not found",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function listTask($id)
    {
        $user = User::find($id);
        if ($user) {
            $admin_task_id = AssignTask::where('user_id', $id)->where('is_accept',0)->latest()->pluck('admin_task_id');
            $task = AdminTask::select('id', 'title', 'description', 'point')->where('status', 1)->whereIn('id', $admin_task_id)->get();


            return response()->json([
                'status' => true,
                'action' => "Task list",
                'data' => $task
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "User not found",
        ]);
    }

    public function detailTask($id)
    {
        $task = AdminTask::find($id);
        if ($task) {

            return response()->json([
                'status' => true,
                'action' => "Detail task",
                'data' => $task
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "Task not found",
        ]);
    }

    public function dashboard($id)
    {
        $user = User::find($id);
        if ($user) {
            $newpoints = UserTask::where('user_id', $id)->where('status', 'new')->get();
            $pendingPoints = UserTask::where('user_id', $id)->where('status', 'pending')->get();
            $cashedPoints = UserTask::where('user_id', $id)->where('status', 'cashed')->get();
            $withdraws = withdraw::where('user_id', $id)->get();

            $bonuses = Bonus::where('user_id', $id)->where('is_read', 1)->get();



            $totalNewPoints = 0;
            $totalPedningPoints = 0;
            $totalCashedPoints = 0;
            $bonusPoints = 0;
            $withdrawPoint = 0;

            foreach ($newpoints as $newpoint) {
                $task = AdminTask::find($newpoint->task_id);
                $totalNewPoints += $task->point;
            }

            foreach ($pendingPoints as $pendingPoint) {
                $task = AdminTask::find($pendingPoint->task_id);
                $totalPedningPoints += $task->point;
            }


            foreach ($cashedPoints as $cashedPoint) {
                $task = AdminTask::find($cashedPoint->task_id);
                $totalCashedPoints += $task->point;
            }

            foreach ($bonuses as $bonus) {
                $bonusPoints += $bonus->point;
            }

            foreach ($withdraws as $withdraw) {
                $withdrawPoint += $withdraw->point;
            }



            $newCashedPoints = $bonusPoints + $totalCashedPoints;

            // $newCashedPoints = $points - $withdrawPoint;
            $obj = new StdClass();
            $obj->new_points = $totalNewPoints;
            $obj->pending_points = $totalPedningPoints;
            $obj->point_cashed = $newCashedPoints;
            $obj->user = $user;
            return response()->json([
                'status' => true,
                'action' => "Points",
                'data' => $obj
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function userTaskNew($id)
    {
        $user = User::find($id);
        if ($user) {
            $usertasks = UserTask::where('user_id', $id)->where('status', 'new')->get();
            if ($usertasks->count() > 0) {
                $tasks = [];
                $totalPoints = 0;
                foreach ($usertasks as $usertask) {
                    $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $usertask->task_id)->first();

                    // $task = AdminTask::find($usertask->task_id);
                    $task->video_id = 0;

                    $task->status = 'Not Accepted';

                    $tasks[] = $task;
                    $totalPoints += $task->point;
                }


                return response()->json([
                    'status' => true,
                    'action' => "Task List",
                    'total_points' => $totalPoints,
                    'data' => $tasks,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'action' => "No task accepted yet",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function userTaskApprove($id)
    {
        $user = User::find($id);
        if ($user) {
            $usertasks = UserTask::where('user_id', $id)->where('status', 'pending')->get();
            if ($usertasks->count() > 0) {
                $tasks = [];
                $totalPoints = 0;
                foreach ($usertasks as $usertask) {

                    $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $usertask->task_id)->first();
                    $video = UserVideo::where('task_id', $task->id)->where('user_id', $id)->first();
                    $task->video_id = $video->id;
                    $task->video_media = $video->video;
                    if ($video->is_approved == 0) {
                        $task->status = 'Awaiting Approval';
                    }

                    if ($video->is_approved == 2) {
                        $task->status = 'Rejected';
                    }
                    // $task = AdminTask::find($usertask->task_id);
                    $tasks[] = $task;
                    $totalPoints += $task->point;
                }

                return response()->json([
                    'status' => true,
                    'action' => "Task List",
                    'total_points' => $totalPoints,
                    'data' => $tasks,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'action' => "No task accepted yet",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }


    public function userCashedTask($id)
    {
        $user = User::find($id);
        if ($user) {
            $usertasks = UserTask::where('user_id', $id)->where('status', 'cashed')->get();
            $tasks = [];

            $totalPoints = 0;
            $bonusPoints = 0;
            $withdrawPoint = 0;
            $withdraws = withdraw::where('user_id', $id)->get();


            $userbonus = Bonus::where('user_id', $id)->where('is_read', 1)->get();

            foreach ($usertasks as $usertask) {
                $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $usertask->task_id)->first();
                $video = UserVideo::where('task_id', $task->id)->where('user_id', $id)->first();
                $task->video_id = $video->id;
                $task->video_media = $video->video;
                $task->status = 'To Be Cashed';
                $tasks[] = $task;
                $totalPoints += $task->point;
            }


            foreach ($userbonus as $item) {
                $bonusTask = new stdClass();
                $bonusTask->id = $item->id;
                $bonusTask->title = "Bonus";
                $bonusTask->description = '';
                $bonusTask->deadline = '';
                $bonusTask->point = $item->point;
                $bonusTask->video_id = 0;
                $bonusTask->video_media = 0;
                $bonusTask->status = 'Gifted';
                $tasks[] = $bonusTask;
                $bonusPoints += $item->point;
            }



            foreach ($withdraws as $withdraw) {
                $withdrawPoint += $withdraw->point;
            }

            $newCashedPoints = $bonusPoints + $totalPoints;
            // $newCashedPoints = $points - $withdrawPoint;


            return response()->json([
                'status' => true,
                'action' => "Task List",
                'total_points' => $newCashedPoints,
                'data' => $tasks,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }




    public function addVideo($request, $id)
    {
        $user = User::find($id);
        if ($user) {

            $video = new UserVideo();
            $uploadVideo = $request->file('video');
            $videoFile = time() . '_' . $uploadVideo->getClientOriginalName();
            $uploadVideo->move(public_path('User/video'), $videoFile);

            // $thumbnail = $request->file('thumbnail');
            // $thumbnailPath = time() . '_' . $thumbnail->getClientOriginalName();
            // $thumbnail->move(public_path('User/video/thumbnails/'), $thumbnailPath);
            // $thumbnailPath = $this->getVideoThumbnail($videoFile);
            $thumbnailPath = '';

            $link = $request->link;
            if ($link == null) {
                $link = '';
            }

            $video->user_id = $id;
            $video->task_id = $request->task_id;

            $video->title = $request->title;
            $video->video = $videoFile;
            $video->link = $link;
            $video->thumbnail = $thumbnailPath;
            $video->about = $request->about;
            $video->time = strtotime(date('Y-m-d H:i:s'));

            // if (count($questionIds) === count($items)) {
            //     for ($i = 0; $i < count($questionIds); $i++) {
            //         $question = new UserAnswer();
            //         $question->user_id = $id;
            //         $question->task_id = $request->task_id;
            //         $question->question_id = $questionIds[$i];
            //         // $question->item = json_encode($items[$i]);

            //         // Check if the current item is an array
            //         if (is_array($items[$i])) {
            //             // Join the items in the array with a delimiter (e.g., ', ') and store it
            //             $question->item = implode(', ', $items[$i]);
            //         } else {
            //             // If it's not an array, just store it as is
            //             $question->item = $items[$i];
            //         }

            //         $question->save();
            //     }
            // } else {
            //     return response()->json([
            //         'status' => false,
            //         'action' => "Question is not equal to item",
            //     ]);
            // }




            $video->save();

            $userTask = UserTask::where('user_id', $id)->where('task_id', $request->task_id)->first();
            $userTask->status = 'pending';
            $userTask->save();
            // $notification = new Notification();
            // $notification->user_id = $id;
            // $notification->video_id = $video->id;
            // $notification->body = 'Your video has been submitted please wait for admin approval';
            // $notification->save();
            return response()->json([
                'status' => true,
                'action' => "Your video is uploaded",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function addQuestion($request)
    {
        $questions = $request->json('questions');
        foreach ($questions as $questionData) {
            $question = new UserAnswer();
            $question->user_id = $request->json('user_id');
            $question->task_id = $request->json('task_id');
            $question->question_id = $questionData['question_id'];

            // Check if the "items" field is a comma-separated string
            if (is_string($questionData['items'])) {
                // Split the string by commas and store as an array
                $items = explode(', ', $questionData['items']);
            } else if (is_array($questionData['items'])) {
                // Use the "items" array as is
                $items = $questionData['items'];
            } else {
                // Handle any other cases as needed
                $items = [$questionData['items']];
            }

            // Convert the items to a string with a delimiter (e.g., ', ')
            $question->item = implode(', ', $items);

            $question->save();
        }

        return response()->json([
            'status' => true,
            'action' => "Question Added",
        ]);
        // $questionIds = $request->json('questionIds');
        // $items = $request->json('items');

        // if (count($questionIds) === count($items)) {
        //     for ($i = 0; $i < count($questionIds); $i++) {
        //         $question = new UserAnswer();
        //         $question->user_id = $request->json('user_id');
        //         $question->task_id = $request->json('task_id');
        //         $question->question_id = $questionIds[$i];

        //         // Check if the current item is an array
        //         if (is_array($items[$i])) {
        //             // Join the items in the array with a delimiter (e.g., ', ') and store it
        //             $question->item = implode(', ', $items[$i]);
        //         } else {
        //             // If it's not an array, just store it as is
        //             $question->item = $items[$i];
        //         }

        //         $question->save();
        //     }
        // } else {
        //     return response()->json([
        //         'status' => false,
        //         'action' => "Question count is not equal to item count",
        //     ]);
        // }

        // return response()->json([
        //     'status' => true,
        //     'action' => "Question Added",
        // ]);
    }

    public function getVideoThumbnail($videoFile)
    {

        $ffmpeg = FFMpeg::create(
            array(
                'ffmpeg.binaries'  => "C:/ffmpeg/bin/ffmpeg.exe",
                'ffprobe.binaries' => "C:/ffmpeg/bin/ffprobe.exe",
            )
        );

        $video = $ffmpeg->open(public_path('User/video/' . $videoFile));


        $thumbnailPath = 'User/thumbnails/' . time() . '_thumbnail.jpg'; // Adjust the path

        // Capture the thumbnail
        $video->frame(TimeCode::fromSeconds(2))
            ->save(public_path($thumbnailPath));

        return $thumbnailPath;
    }

    public function myVideo($id)
    {
        $user  = User::find($id);
        if ($user) {

            $videos = UserVideo::where('user_id', $id)->get();

            if ($videos->count() > 0) {
                return response()->json([
                    'status' => true,
                    'action' => "Videos",
                    'data' => $videos
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "No Vedio found",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function videoStatus($id, $status)
    {
        $user  = User::find($id);
        if ($user) {

            $videos = UserVideo::where('user_id', $id)->where('is_approved', $status)->get();

            if ($videos->count() > 0) {
                return response()->json([
                    'status' => true,
                    'action' => "Videos",
                    'data' => $videos
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "No Vedio found",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function declinedVideo($id)
    {
        $user = User::find($id);
        if ($user) {
            $videos = UserVideo::where('is_approved', 2)->where('user_id', $id)->get();
            if ($videos->count() > 0) {
                return response()->json([
                    'status' => true,
                    'action' => "Videos",
                    'data' => $videos
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "No video found",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function videoDetail($id)
    {
        $videoDetail = UserVideo::select('video', 'title', 'link', 'created_at', 'task_id')->where('id', $id)->first();
        if ($videoDetail) {
            $tasktitle = AdminTask::select('title')->where('id', $videoDetail->task_id)->first();
            $videoDetail->task_title = $tasktitle->title;

            $reject = DeclineVideo::where('user_video_id', $id)->first();
            if ($reject) {
                $videoDetail->reason = $reject->reason;
            } else {
                $videoDetail->reason = '';
            }
            return response()->json([
                'status' => true,
                'action' => "Video",
                'data' => $videoDetail
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "No video found",
            ]);
        }
    }

    public function question($id)
    {
        $taskQuestion = AdminTask::select('question')->where('id', $id)->first();

        if ($taskQuestion) {
            $questionArray = explode(',', $taskQuestion->question);

            $questions = Question::whereIn('id', $questionArray)->get();
            foreach ($questions as $question) {
                $questionItem = explode(',', $question->item);
                $question->item = $questionItem;
            }

            return response()->json([
                'status' => true,
                'action' => "List of Questions",
                'data' => $questions,
            ]);
        } else {

            return response()->json([
                'status' => false,
                'action' => "No question found",
            ]);
        }
    }

    public function notification($id)
    {
        $user = User::find($id);
        if ($user) {
            $notifications = Notification::where('user_id', $id)->latest()->paginate(12);
            Notification::where('user_id', $id)->where('is_read', 0)->update(['is_read' => 1]);
            return response()->json([
                'status' => true,
                'action' => "Notifications",
                'data' => $notifications
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }


    public function bonusRead($id)
    {
        $user = User::find($id);
        if ($user) {
            Bonus::where('user_id', $id)->where('is_read', 0)->update(['is_read' => 1]);

            return response()->json([
                'status' => true,
                'action' => "Bonus read",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function withdraw($request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $cashedPoints = UserTask::where('user_id', $id)->where('status', 'cashed')->get();
            $bonuses = Bonus::where('user_id', $id)->where('is_read', 1)->get();
            $withdraws = withdraw::where('user_id', $id)->get();

            $totalCashedPoints = 0;
            $bonusPoints = 0;
            $withdrawPoint = 0;
            foreach ($cashedPoints as $cashedPoint) {
                $task = AdminTask::find($cashedPoint->task_id);
                $totalCashedPoints += $task->point;
            }

            foreach ($bonuses as $bonus) {
                $bonusPoints += $bonus->point;
            }



            foreach ($withdraws as $withdraw) {
                $withdrawPoint += $withdraw->point;
            }

            $newCashedPoints = $bonusPoints + $totalCashedPoints;
            // $newCashedPoints = $points - $withdrawPoint;
            $poitnvalue = SnapShotPoint::latest()->first();

            if ($newCashedPoints >= $request->point) {
                $withdraw = new withdraw();
                $withdraw->user_id = $id;
                $withdraw->paypal = $request->paypal;
                $withdraw->point = $request->point;
                $withdraw->save();

                $dollar = $withdraw->point * $poitnvalue->value;

                $obj = new stdClass();
                $obj->dollar = $dollar;
                $obj->name = $user->name;
                $obj->paypal_email = $withdraw->paypal;
                $obj->time_Date = $withdraw->created_at;
                $obj->conversion = $withdraw->point;

                UserTask::where('user_id', $id)->where('status', 'cashed')->update(['status' => 'withdraw']);
                Bonus::where('user_id', $id)->where('is_read', 1)->update(['is_read' => 2]);

                return response()->json([
                    'status' => true,
                    'action' => "Withdraw successfuly",
                    'data' => $obj
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "Plese enter valid point",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function withdrawHistory($id)
    {
        $user = User::find($id);
        $poitnvalue = SnapShotPoint::latest()->first();

        if ($user) {
            $withdraws = withdraw::where('user_id', $id)->get();

            foreach ($withdraws as $withdraw) {
                $dollar = $withdraw->point * $poitnvalue->value;
                $withdraw->dollar = $dollar;
            }
            return response()->json([
                'status' => true,
                'action' => "Withdraw history",
                'data' => $withdraws
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found",
            ]);
        }
    }

    public function unreadNotification($id)
    {
        $user = User::find($id);
        if ($user) {
            $count = Notification::where('user_id', $id)->where('is_read', 0)->count();
            return response()->json([
                'status' => true,
                'action' => "Notification count",
                'data' => $count
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "User not found",
        ]);
    }
}
