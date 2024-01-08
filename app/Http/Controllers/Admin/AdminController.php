<?php

namespace App\Http\Controllers\Admin;

use App\Actions\NewNotification;
use App\Actions\Push\FirebaseNotification;
use App\Actions\PushNotification;
use App\Actions\SendNotification;
use App\Http\Controllers\Controller;
use App\Mail\AccountVerify;
use App\Models\AdminTask;
use App\Models\AssignTask;
use App\Models\Bonus;
use App\Models\CookingLevel;
use App\Models\Faq;
use App\Models\HelpVideo;
use App\Models\Inspiration;
use App\Models\Level;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Restriction;
use App\Models\SnapShotPoint;
use App\Models\Theme;
use App\Models\TimeDuration;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserTask;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Assign;
use Pusher\Pusher;
use stdClass;

class AdminController extends Controller
{
    public function index()
    {
        $pending = User::where('status', 0)->count();

        $total = User::count();
        $todayActive = 0;

        $todayNew = User::whereDate('created_at', date('Y-m-d'))->count();
        $mainUsers = User::pluck('id');
        $loggedIn = UserDevice::whereIn('user_id', $mainUsers)->where('fcm_token', '!=', '')->distinct('user_id')->count();

        $iosTraffic = UserDevice::whereIn('user_id', $mainUsers)->where('device_name', 'ios')->count();
        $androidTraffic = UserDevice::whereIn('user_id', $mainUsers)->where('device_name', 'android')->count();

        $pendingPoints = UserTask::where('status', 'pending')->get();

        $totalPending = 0;
        foreach ($pendingPoints as $item) {
            $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')
                ->where('id', $item->task_id)
                ->first();

            $totalPending += $task->point;
        }

        $cashedPoint  = UserTask::where('status', 'cashed')->get();
        $totalCashed = 0;

        foreach ($cashedPoint as $item) {
            $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')
                ->where('id', $item->task_id)
                ->first();
            $totalCashed += $task->point;
        }

        $totalBonuspoint = Bonus::where('is_read', 1)->get();
        $totalbonus = 0;
        foreach ($totalBonuspoint as $item) {
            $totalbonus += $item->point;
        }


        $withdrawPoint  = UserTask::where('status', 'withdraw')->get();
        $totalWithdraw = 0;

        foreach ($withdrawPoint as $item) {
            $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')
                ->where('id', $item->task_id)
                ->first();
            $totalWithdraw += $task->point;
        }

        $withdrawBonuspoint = Bonus::where('is_read', 2)->get();
        $withdrawBonus = 0;
        foreach ($withdrawBonuspoint as $item) {
            $withdrawBonus += $item->point;
        }


        $allBonus = Bonus::all();
        $allbonusPoint = 0;
        foreach ($allBonus as $item) {
            $allbonusPoint += $item->point;
        }

        $newPoint  = UserTask::where('status', 'new')->get();
        $totalNew = 0;
        foreach ($newPoint as $item) {
            $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')
                ->where('id', $item->task_id)
                ->first();
            $totalNew += $task->point;
        }


        $totalWithdraw = $totalWithdraw + $withdrawBonus;

        $totalCashed = $totalCashed + $totalbonus;

        $value = SnapShotPoint::latest()->first();
        $totalPending = $totalPending * $value->value;
        $totalCashed = $totalCashed * $value->value;
        $totalWithdraw = $totalWithdraw * $value->value;
        $allbonusPoint = $allbonusPoint * $value->value;
        $totalNew = $totalNew * $value->value;




        return view('pages.index', compact('todayActive', 'pending', 'total', 'todayNew', 'mainUsers', 'loggedIn', 'iosTraffic', 'androidTraffic', 'totalPending', 'totalCashed', 'totalWithdraw', 'allbonusPoint', 'totalNew'));
    }

    public function users(Request $request)
    {
        $users = User::latest()->Paginate(20);

        if ($request->ajax()) {
            $value = $request->input('query');

            $users  = User::query();
            if ($value) {
                $users = $users->where('name', 'like', '%' . $value . '%')->orWhere('country', 'like', '%' . $value . '%');
            }
            $users = $users->latest()->Paginate(20);
            return view('user.user-ajax', compact('users'));
        }
        return view('user.index', compact('users'));
    }

    public function userVerify($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->status == 1) {
                $user->status = 0;
                $user->save();
            } else {
                $user->status = 1;
                $user->save();
                Mail::to($user->email)->send(new AccountVerify());
            }
        }

        return redirect()->back();
    }

    public function exportCSV(Request $request)
    {

        if ($request->action == 0) {
            $users = User::select('name', 'email', 'phone', 'status')->where('status', 0);
        }
        if ($request->action == 1) {
            $users = User::select('name', 'email', 'phone', 'status')->where('status', 1);
        }
        if ($request->action == 2) {
            $users = User::select('name', 'email', 'phone', 'status');
        }
        $columns = array('name', 'email', 'phone', 'status');
        $handle = fopen(storage_path('users.csv'), 'w');
        fputcsv($handle, $columns);
        $users = $users->chunk(2000, function ($users) use ($handle) {
            foreach ($users->toArray() as $user) {
                fputcsv($handle, $user);
            }
        });
        fclose($handle);
        return response()->download(storage_path('users.csv'));
    }


    public function userProfile(Request $request, $id)
    {
        $type = $request->input('type');

        $user = User::findOrFail($id);

        if ($user) {
            $level = Level::find($user->level_id);
            $user->level = $level;

            $userTask = AssignTask::where('user_id', $id)->where('is_accept', 0)->get();
            $tasks = [];
            $totalPoints = 0;
            $bonusPoints = 0;



            if ($request->ajax()) {

                $usertasks = UserTask::where('user_id', $id)->where('status', $type)->get();
                $userbonus = Bonus::where('user_id', $id)->where('is_read', 1)->get();
                $userTask = AssignTask::where('user_id', $id)->where('is_accept', 0)->get();



                foreach ($usertasks as $usertask) {
                    $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $usertask->task_id)->first();
                    $task->video_id = 0;
                    if ($type == 'new') {
                        $task->status = 'No Video Uploaded Yet';
                    }
                    if ($type == 'pending') {
                        $task->status = 'Awaiting Approval';
                    }
                    if ($type == 'cashed') {
                        $task->status = 'To Be Cashed';
                    }
                    $tasks[] = $task;
                    $totalPoints += $task->point;
                }
                if ($type == 'cashed') {
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

                    $totalPoints = $totalPoints + $bonusPoints;
                }

                if ($type == 'user_task') {
                    foreach ($userTask as $item) {
                        $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $item->admin_task_id)->first();
                        $task->video_id = 0;
                        $task->status = 'Assigned Task';
                        $tasks[] = $task;
                        $totalPoints += $task->point;
                    }

                }

                return view('user.task-ajax', compact('totalPoints', 'tasks', 'user', 'type'));
                // return response()->json(['type' => $type]);
            }
            $type = 'user_task';

            foreach ($userTask as $item) {
                $task = AdminTask::select('id', 'title', 'description', 'point', 'deadline')->where('id', $item->admin_task_id)->first();
                $task->video_id = 0;
                $task->status = 'Assigned Task';
                $tasks[] = $task;
                $totalPoints += $task->point;
            }


            return view('user.show', compact('user', 'totalPoints', 'tasks', 'type'));
        } else {
            return redirect()->back();
        }
    }

    public function deleteUser($id)
    {
        $find = User::find($id);
        if ($find) {
            $find->delete();
            return redirect()->back()->with('delete', 'User Deleted');
        }
        return redirect()->back();
    }



    public function videos()
    {
        $videos = HelpVideo::all();
        return view('pages.helpvideo', compact('videos'));
    }

    public function uploadVideo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required',
            'video' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $video = new HelpVideo();

        $video->title = $request->title;

        $cover = $request->file('image');
        $filename = time() . '.' . $cover->getClientOriginalExtension();
        $cover->move(public_path('Admin/cover'), $filename);

        $video->cover = $filename;


        $uploadVideo = $request->file('video');
        $videoFile = time() . '_' . $uploadVideo->getClientOriginalName();
        $uploadVideo->move(public_path('Admin/video'), $videoFile);


        $video->video = $videoFile;
        $video->save();
        return redirect()->back()->with('success', 'Video uploaded Successfully');
    }

    public function updateVideo(Request $request, $id)
    {
        $video = HelpVideo::find($id);
        $video->title = $request->title;


        if ($request->hasFile('video')) {
            $uploadVideo = $request->file('video');
            $videoFile = time() . '_' . $uploadVideo->getClientOriginalName();
            $uploadVideo->move(public_path('Admin/video'), $videoFile);
            $video->video = $videoFile;
            $video->save();
        }

        if ($request->hasFile('image')) {

            $cover = $request->file('image');
            $filename = time() . '.' . $cover->getClientOriginalExtension();
            $cover->move(public_path('Admin/cover'), $filename);
            $video->cover = $filename;

            $video->save();
        }

        $video->save();
        return redirect()->back()->with('success', 'Video edit successfully');
    }



    public function deleteVedio($id)
    {
        $video = HelpVideo::find($id);
        if ($video) {

            $videoFilePath = public_path('Admin/video') . '/' . $video->video; // Adjust the path as needed
            if (file_exists($videoFilePath)) {
                unlink($videoFilePath);
            }

            $imageFilePath = public_path('Admin/cover') . '/' . $video->cover; // Adjust the path as needed
            if (file_exists($imageFilePath)) {
                unlink($imageFilePath);
            }

            $video->delete();
            return redirect()->back()->with('delete', 'Video Deleted');
        } else {
            return redirect()->back();
        }
    }


    public function level()
    {
        $levels = Level::all();
        return view('pages.level', compact('levels'));
    }

    public function addLevel(Request $request)
    {
        $level = new Level();
        $level->name = $request->name;
        $level->description = $request->description;

        $level->save();
        return redirect()->back()->with('success', 'Level added successfuly');
    }

    public function editLevel(Request $request, $id)
    {
        $level = Level::find($id);
        if ($level) {
            $level->name = $request->name;
            $level->description = $request->description;

            $level->save();
            return redirect()->back()->with('success', 'Level edit successfuly');
        } else {
            return redirect()->back();
        }
    }

    public function deleteLevel($id)
    {
        $level = Level::find($id);

        if ($level) {
            $level->delete();
            return redirect()->back()->with('delete', 'Level delete successfuly');
        } else {
            return redirect()->back();
        }
    }



    public function chat()
    {
        $latestMessages = Message::select('user_id', DB::raw('MAX(id) as latest_message_id'))
            ->groupBy('user_id')
            ->get();

        $latestMessagesData = Message::whereIn('id', $latestMessages->pluck('latest_message_id'))
            ->orderBy('created_at', 'desc')->get();

        foreach ($latestMessagesData as $message) {
            $user = User::find($message->user_id);
            $message->user = $user; // Attach the user object to the message
        }


        return view('chat.chat', compact('latestMessagesData'));
    }

    public function conversation($id)
    {
        $messages = Message::where('user_id', $id)->get();
        $user = User::find($id);
        return view('chat.conversation', compact('messages', 'user'));
    }

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->user_id  = $request->user_id;
        $message->message = $request->message;
        $message->sendBy = 'admin';
        $message->type = 'text';
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
        return response()->json($message);
    }




    public function faqs()
    {
        $faqs = Faq::all();

        return view('pages.faq', compact('faqs'));
    }

    public function deleteFaq($id)
    {
        $faq  = Faq::find($id);
        $faq->delete();
        return redirect()->back()->with('delete', 'FAQ Deleted');
    }

    public function addFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return redirect()->back()->with('success', 'FAQ  Added Successfully');
    }


    public function question()
    {
        $questions = Question::all();
        return view('pages.question', compact('questions'));
    }

    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'item' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $question = new Question();
        $question->question = $request->question;
        $question->item = $request->item;
        $question->save();
        return redirect()->back()->with('success', 'Question  Added Successfully');
    }
    public function editQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        if ($question) {
            $question->question = $request->question;
            $question->item = $request->item;


            $question->save();
            return redirect()->back()->with('success', 'Question  Edit Successfully');
        } else {
            redirect()->back();
        }
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if ($question) {
            $question->delete();
            return redirect()->back()->with('delete', 'Question  deleted ');
        } else {
            redirect()->back();
        }
    }


    public function snapPoint()
    {
        $value = SnapShotPoint::latest()->first();
        return view('snapshotPoint', compact('value'));
    }

    public function snapPointadd(Request $request)
    {
        $value = SnapShotPoint::latest()->first();
        $value->value = $request->value;
        $value->save();
        return redirect()->back()->with('success', 'Value Added');
    }



    public function userBonus($id, Request $request)
    {
        $user = User::find($id);
        if ($user) {
            $validator = Validator::make($request->all(), [
                'point' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $point = new Bonus();
                $point->user_id = $id;
                $point->point = $request->point;
                $point->save();


                NewNotification::handle($id, $request->point, 'You have received ' . $request->point . ' bonus points', 'bonus');
                $tokens = UserDevice::where('user_id', $id)->where('fcm_token', '!=', '')->pluck('fcm_token')->toArray();
                FirebaseNotification::handle($tokens, 'SnapShot', 'You have received ' . $request->point . ' bonus points', ['data_id' => $request->point, 'type' => 'bonus']);
                return redirect()->back()->with('success', 'Points Rewarded');
            }
        } else {
            return redirect()->back();
        }
    }



    public function message(Request $request)
    {
        $message = new Message();
        $message->user_id  = $request->user_id;
        $message->message = $request->message;
        $message->sendBy = 'admin';
        $message->type = 'text';
        $message->time = strtotime(date('Y-m-d H:i:s'));

        $message->save();
        return redirect()->back()->with('success', 'Message Send');
    }
}
