<?php

namespace App\Http\Controllers\Admin;

use App\Actions\NewNotification;
use App\Actions\Push\FirebaseNotification;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminTask;
use App\Models\AssignTask;
use App\Models\Bonus;
use App\Models\DeclineVideo;
use App\Models\Notification;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserDevice;
use App\Models\UserTask;
use App\Models\UserVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Assign;

class AdminTaskController extends Controller
{


    public function listTask(Request $request)
    {
        $tasks = AdminTask::latest()->paginate(20);
        if ($request->ajax()) {
            $value = $request->input('query');

            $tasks  = AdminTask::query();
            if ($value) {
                $tasks = $tasks->where('title', 'like', '%' . $value . '%');
            }
            $tasks = $tasks->latest()->Paginate(20);
            return view('task.list-ajax', compact('tasks'));
        }
        
        return view('task.listtask', compact('tasks'));
    }

    public function create()
    {
        $questions = Question::all();
        return view('task.addtask', compact('questions'));
    }


    public function addTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required',
            'video' => 'required',
            'description' => 'required',
            'guideline' => 'required',
            'point' => 'required|numeric',
            'question' => 'required',
            'deadline' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $task = new AdminTask();




        $selectedQuestionIds = implode(',', $request->question);

        $task->title = $request->title;
        $task->point = $request->point;
        $task->description = $request->description;
        $task->guideline = $request->guideline;

        $task->deadline = $request->deadline;
        $task->format = $request->video_format;
        $task->question = $selectedQuestionIds;


        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('Admin/Task/image'), $filename);



        $uploadVideo = $request->file('video');
        $videoFile = time() . '_' . $uploadVideo->getClientOriginalName();
        $uploadVideo->move(public_path('Admin/Task/video'), $videoFile);


        $task->image = $filename;
        $task->video = $videoFile;


        $task->save();
        return redirect()->back()->with('success', 'Task uploaded Successfully');
    }

    public function editTask($id)
    {
        $questions = Question::all();
        $task = AdminTask::find($id);
        if ($task) {
            return view('task.edittask', compact('task', 'questions'));
        } else {
            return redirect()->back();
        }
    }

    public function taskDelete($id){
        $find = AdminTask::find($id);

        AssignTask::where('admin_task_id',$id)->delete();
        UserAnswer::where('task_id',$id)->delete();
        UserTask::where('task_id',$id)->delete();
        UserVideo::where('task_id',$id)->delete();
        $find->delete();
        return redirect()->back();
    }

    public function updateTask(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'guideline' => 'required',
            'point' => 'required|numeric',
            'question' => 'required',
            'deadline' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $task = AdminTask::find($id);




        $selectedQuestionIds = implode(',', $request->question);

        $task->title = $request->title;
        $task->point = $request->point;
        $task->description = $request->description;
        $task->guideline = $request->guideline;

        $task->deadline = $request->deadline;
        $task->format = $request->video_format;
        $task->question = $selectedQuestionIds;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Admin/Task/image'), $filename);
            $task->image = $filename;
        }


        if ($request->hasFile('video')) {
            $uploadVideo = $request->file('video');
            $videoFile = time() . '_' . $uploadVideo->getClientOriginalName();
            $uploadVideo->move(public_path('Admin/Task/video'), $videoFile);
            $task->video = $videoFile;
        }





        $task->save();
        return redirect()->back()->with('success', 'Task upldated Successfully');
    }

    public function changeStatus($id)
    {
        $task = AdminTask::find($id);
        if ($task) {
            if ($task->status == 1) {
                $task->status = 0;
                $task->save();
                return redirect()->back()->with('delete', 'Task deactive');
            } else {
                $task->status = 1;
                $task->save();
                return redirect()->back()->with('success', 'Task active');
            }
        } else {
            return redirect()->back();
        }
    }



    public function assignUser($user_id, $task_id)
    {

        $user = AssignTask::where('user_id', $user_id)->where('admin_task_id', $task_id)->where('is_accept', 0)->first();
        if ($user) {
            $user->delete();
            return redirect()->back()->with('delete', 'Task Un Assign');
        }
        $task = new AssignTask();
        $task->user_id = $user_id;
        $task->admin_task_id = $task_id;
        $task->save();

        $adminTask = AdminTask::find($task_id);

        NewNotification::handle($user_id, $task_id, 'Admin has Assign you a task on topic ' . $adminTask->title, 'assign');
        $tokens = UserDevice::where('user_id', $user_id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
        FirebaseNotification::handle($tokens, 'SnapShot', 'Admin has Assign you a task on topic ' . $adminTask->title, ['data_id' => $task_id, 'type' => 'assign']);



        return redirect()->back()->with('success', 'Task Assign');
    }

    public function users(Request $request, $task_id)
    {
        $user_ids = UserTask::where('task_id', $task_id)
            ->where(function ($query) {
                $query->where('status', '=', 'new')
                    ->orWhere('status', '=', 'pending');
            })
            ->pluck('user_id');

        if ($request->ajax()) {
            $value = $request->input('query');

            $users = User::whereNotIn('id', $user_ids);
            if ($value) {
                $users = $users->where('email', 'like', '%' . $value . '%')->orWhere('country', 'like', '%' . $value . '%');
            }
            $users = $users->paginate(20);
            foreach ($users as  $user) {
                $check = AssignTask::where('user_id', $user->id)->where('admin_task_id', $task_id)->where('is_accept', 0)->latest()->first();
                if ($check) {
                    $user->assign = true;
                } else {
                    $user->assign = false;
                }
            }

            return view('task.user-ajax', compact('users', 'task_id'));
        }

        $users = User::whereNotIn('id', $user_ids)->paginate(20);

        foreach ($users as  $user) {
            $check = AssignTask::where('user_id', $user->id)->where('admin_task_id', $task_id)->where('is_accept', 0)->latest()->first();
            if ($check) {
                $user->assign = true;
            } else {
                $user->assign = false;
            }
        }

        return view('task.users', compact('users', 'task_id'));
    }

    public function userVideo($id)
    {

        $userVideos = UserVideo::where('task_id', $id)->latest()->paginate(20);
        foreach ($userVideos as $userVideo) {
            $user = User::find($userVideo->user_id);
            $task = AdminTask::find($userVideo->task_id);
            $videoQuestions = UserAnswer::where('task_id', $userVideo->task_id)->where('user_id', $userVideo->user_id)->get();
            foreach ($videoQuestions as $videoQuestion) {
                $question = Question::find($videoQuestion->question_id);
                $videoQuestion->questions = $question;
            }
            $userVideo->question = $videoQuestions;
            $userVideo->user = $user;
            $userVideo->task = $task;
        }
        return view('task.uservideo', compact('userVideos'));
    }

    public function approveVideo($id)
    {
        $userVideo = UserVideo::find($id);
        if ($userVideo) {
            $userVideo->is_approved = 1;
            $userVideo->save();
            $usertask = UserTask::where('user_id', $userVideo->user_id)->where('task_id', $userVideo->task_id)->first();
            $usertask->status = 'cancel';
            $usertask->save();

            $adminTask = AdminTask::find($userVideo->task_id);


            NewNotification::handle($$userVideo->user_id, $id, 'Admin has approved your video for the topic ' . $adminTask->title, 'approved');
            $tokens = UserDevice::where('user_id', $user_id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
            FirebaseNotification::handle($tokens, 'SnapShot', 'Admin has approved your video for the topic ' . $adminTask->title, ['data_id' => $id, 'type' => 'approved']);

            return redirect()->back()->with('success', 'Video approved');
        } else {
            return redirect()->back();
        }
    }

    public function declineVideo(Request $request, $id)
    {

        $userVideo = UserVideo::find($id);
        if ($userVideo) {
            $userVideo->is_approved = 2;
            $userVideo->save();
            $declined = new DeclineVideo();
            $declined->user_video_id = $request->video_id;
            $declined->reason = $request->reason;
            $declined->save();

            $adminTask = AdminTask::find($userVideo->task_id);

            NewNotification::handle($userVideo->user_id, $id, 'Your video on the topic' . $adminTask->title . 'has been declined by admin. Better try next time!', 'declined');

            $tokens = UserDevice::where('user_id', $userVideo->user_id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
            FirebaseNotification::handle($tokens, 'SnapShot', 'Your video on the topic' . $adminTask->title . 'has been declined by admin. Better try next time!', ['data_id' => $id, 'type' => 'declined']);



            return redirect()->back()->with('delete', 'Video declined');
        } else {
            return redirect()->back();
        }
    }


    public function reward($id, $task_id)
    {


        $video = UserVideo::find($id);
        if ($video) {
            $find_task = AdminTask::find($task_id);
            if ($find_task) {
                $video->point = $find_task->point;
                $video->is_approved = 1;

                $video->save();

                $usertask = UserTask::where('user_id', $video->user_id)->where('task_id', $video->task_id)->first();
                $usertask->status = 'cashed';
                $usertask->save();


                NewNotification::handle($video->user_id, $id, 'You have received 200 points for the video' . $find_task->title, 'approved');
                $tokens = UserDevice::where('user_id', $video->user_id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
                FirebaseNotification::handle($tokens, 'SnapShot', 'Admin has approved your video for the topic ' . $find_task->title, ['data_id' => $id, 'type' => 'approved']);

                // $notification = new Notification();
                // $notification->user_id = $video->user_id;
                // $notification->video_id = 0;
                // $notification->body = 'You have received 200 points for the video' . $find_task->title;
                // $notification->save();

                return redirect()->back()->with('success', 'Points Rewarded');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function deleteVideo($id)
    {
        $video = UserVideo::find($id);
        if ($video) {

            $filePath = public_path('User/video/') . $video->video;

            // Check if the file exists and delete it
            if (file_exists($filePath) && is_file($filePath)) {
                unlink($filePath); // This will delete the file from the public path
            }
            $video->delete();
            return redirect()->back()->with('delete', 'Video Deleted');
        }
        return redirect()->back();
    }
}
