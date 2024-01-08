<?php

namespace  App\Services\Auth;

use App\Mail\EmailSend;
use App\Models\Level;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserInfo;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{



    public function login($request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $userdevice = new UserDevice();
                $userdevice->user_id = $user->id;
                $userdevice->device_name = $request->device_name ?? 'No name';
                $userdevice->device_id = $request->device_id ?? 'No ID';
                $userdevice->timezone = $request->timezone ?? 'No Time';
                $userdevice->fcm_token = $request->fcm_token ?? 'No tocken';
                $userdevice->save();

                // if ($user->image) {
                //     $user->image = asset('profile/' . $user->image);
                // }


                return response()->json([
                    'status' => true,
                    'action' => "Login successfully",
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Please Enter Correct Password',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }

    public function userVerify($request)
    {
        // $phone = $request->country_code . $request->phone;
        $user = User::where('country_code', $request->country_code)->where('phone', $request->phone)->first();
        if ($user) {
            return response()->json([
                'status' => false,
                'action' => 'The phone has already been taken'
            ]);
        } else {
            return response()->json([
                'status' => true,
                'action' => 'User Verify'
            ]);
        }
    }


    public function level()
    {
        $levels = Level::all();

        return response()->json([
            'status' => true,
            'action' => "levels list",
            'data' => $levels
        ]);
    }

    public function register($request)
    {

        // $phone = $request->country_code . $request->phone;
        $user = User::where('country_code', $request->country_code)->where('phone', $request->phone)->first();
        if ($user) {
            return response()->json([
                'status' => false,
                'action' => 'The phone has already been taken'
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->country_code = $request->country_code;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->password = Hash::make($request->password);
        $user->city = $request->city;
        $user->reason = $request->reason;
        $user->level_id = $request->level_id;
        $user->information = $request->information;
        $user->save();




        $userdevice = new UserDevice();
        $userdevice->user_id = $user->id;
        $userdevice->device_name = $request->device_name ?? 'No name';
        $userdevice->device_id = $request->device_id ?? 'No ID';
        $userdevice->timezone = $request->timezone ?? 'No Time';
        $userdevice->fcm_token = $request->fcm_token ?? 'No tocken';
        $userdevice->save();
        return response()->json([
            'status' => true,
            'action' => 'User Register Successfully',
            'data' => $user
        ]);
    }


    public function recover($request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $otp = random_int(100000, 999999);
            $user = User::where('email', $request->email)->update([
                'otp' => $otp,
                'otp_time' => now()
            ]);

            $mail_details = [
                'body' => $otp,
            ];

            Mail::to($request->email)->send(new EmailSend($mail_details));

            return response()->json([
                'status' => true,
                'action' => 'Otp send',
                'data' => $otp
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }


    public function recoverVerify($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            if ($user->otp == $request->otp) {
                User::where('email', '=', $request->email)->update([
                    'otp' => '',
                    'otp_time' => ''
                ]);

                return response()->json([
                    'status' => true,
                    'action' => 'Otp verified'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Please enter correct OTP'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }


    public function newPassword($request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'action' => "New password is same as Old password",
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                return response()->json([
                    'status' => true,
                    'action' => "New password set",
                ]);
            }
            // $user->update([
            //     'password' => Hash::make($request->password)
            // ]);
            return response()->json([
                'status' => true,
                'action' => "New password set"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }


    public function editProfile($request, $user_id)
    {
        $user = User::find($user_id);
        // $phone = $request->country_code . $request->phone;
        if ($user) {
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('profession')) {
                $user->profession = $request->profession;
            }

            if ($request->has('country')) {
                $user->country = $request->country;
            }
            if ($request->has('city')) {
                $user->city = $request->city;
            }


            if ($request->has('email')) {
                if (User::where('email', $request->email)->where('id', '!=', $user_id)->exists()) {
                    return response()->json([
                        'status' => false,
                        'action' => 'Email already taken'
                    ]);
                } else {
                    $user->email = $request->email;
                }
            }

            if ($request->has('phone') || $request->has('country_code')) {

                if (User::where('country_code', $request->country_code)->where('phone', $request->phone)->where('id', '!=', $user_id)->exists()) {
                    return response()->json([
                        'status' => false,
                        'action' => 'Phone already taken'
                    ]);
                } else {

                    $user->country_code = $request->country_code;
                    $user->phone = $request->phone;
                }
            }


            if ($request->has('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('profile'), $filename);

                if ($user->image) {
                    $previousImagePath = public_path('profile') . '/' . $user->image;
                    if (file_exists($previousImagePath)) {
                        unlink($previousImagePath);
                    }
                }

                $user->image = $filename;
            }

            $user->save();

            return response()->json([
                'status' => true,
                'action' => "Profile edit",
                'data' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found"
            ]);
        }
    }

    public function changePassword($request, $user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->new_password, $user->password)) {

                    return response()->json([
                        'status' => false,
                        'action' => "New password is same as old password",
                    ]);
                } else {
                    $user->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                    return response()->json([
                        'status' => true,
                        'action' => "Password  change",
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'action' => "Old password is wrong",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }
}
