<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddVideoRequest;
use App\Http\Requests\User\MessageRequest;
use App\Http\Requests\User\WithdrawRequest;
use App\Models\Message;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function sendMessage(MessageRequest $request)
    {
        return $this->userService->sendMessage($request);
    }


    public function conversation($id){
        return $this->userService->conversation($id);
    }

    public function acceptTask($user_id,$task_id){
        return $this->userService->acceptTask($user_id,$task_id);

    }

    public function listTask($id){
        return $this->userService->listTask($id);
        
    }

    public function detailTask($id){
        return $this->userService->detailTask($id);
        
    }

    public function dashboard($id){
        return $this->userService->dashboard($id);

    }

    public function userTaskNew($id){
        return $this->userService->userTaskNew($id);

    }
    public function userTaskApprove($id){
        return $this->userService->userTaskApprove($id);

    }
    public function userCashedTask($id){
        return $this->userService->userCashedTask($id);

    }

    public function addVideo(AddVideoRequest $request,$id){
        return $this->userService->addVideo($request,$id);

    }

    public function addQuestion(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:admin_tasks,id',
            
        ]);


        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }
        return $this->userService->addQuestion($request);

    }
    public function videoStatus($id,$status){
        return $this->userService->videoStatus($id , $status);

    }

    public function myVideo($id){
        return $this->userService->myVideo($id);
    }

    public function declinedVideo($id){
        return $this->userService->declinedVideo($id);

    }

    public function videoDetail($id){
        return $this->userService->videoDetail($id);

    }
    public function question($id){
        return $this->userService->question($id);

    }

    public function notification($id){
        return $this->userService->notification($id);

    }

    
    public function bonusRead($id){
        return $this->userService->bonusRead($id);

    }


    public function withdraw(WithdrawRequest $request, $id){
        return $this->userService->withdraw($request,$id);
    }

    public function withdrawHistory($id){
        return $this->userService->withdrawHistory($id);
    }

    public function unreadNotification($id){
        return $this->userService->unreadNotification($id);

    }
   
}
