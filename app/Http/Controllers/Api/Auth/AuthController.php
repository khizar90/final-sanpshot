<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\HelpVideo;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\Auth\AuthService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    protected $authService;

    private $file = 'geo.json';


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function mb_search(string $haystack, string $needle)
    {
        return 1;
        setlocale(LC_ALL, 'en_US.UTF8');
        $haystack = preg_replace('/[\'^`~\"]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $haystack));
        $needle = preg_replace('/[\'^`~\"]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $needle));
        return mb_stripos($haystack, $needle);
    }

    // public function countries($search = NULL)
    // {
    //     $data = json_decode(File::get(public_path($this->file)), true);
    //     $countries = array_filter(
    //         array_keys(iterator_to_array($data, true)),
    //         fn (string $country) => !$search || $this->mb_search($country, $search) !== false
    //     );
    //     if (empty($search)) {
    //         sort($countries);
    //     } else {
    //         usort($countries, function (string $a, string $b) use ($search): int {
    //             if (strpos($a, $search) == strpos($b, $search)) {
    //                 return 0;
    //             }
    //             return (strpos($a, $search) < strpos($b, $search)) ? -1 : 1;
    //         });
    //     }
    //     return response(['status' => true, 'data' => $countries, 'action' => 'Countries']);
    // }

     public function countries($search = NULL)
    {
        $data = json_decode(File::get(public_path($this->file)), true);
        $countryNames = array_keys($data);
        $countries = array_filter(
            $countryNames,
            fn (string $country) => !$search || $this->mb_search($country, $search) !== false
        );

        if (empty($search)) {
            sort($countries);
        } else {
            usort($countries, function (string $a, string $b) use ($search): int {
                if (strpos($a, $search) == strpos($b, $search)) {
                    return 0;
                }
                return (strpos($a, $search) < strpos($b, $search)) ? -1 : 1;
            });
        }

        return response(['status' => true, 'data' => $countries, 'action' => 'Countries']);
    }


    public function getCities($countryName, $search = NULL)
    {
        $data = json_decode(File::get(public_path($this->file)), true);
        $cities = [];
        $found = false;
        foreach ($data as $key => $value) {
            if ($countryName == ucwords($key)) {
                $found = true;
                $cities = array_filter(
                    $value,
                    fn (string $city) => !$search || $this->mb_search($city, $search) !== false
                );
                if (empty($search)) {
                    sort($cities);
                } else {
                    usort($cities, function (string $a, string $b) use ($search): int {
                        if (strpos($a, $search) == strpos($b, $search)) {
                            return 0;
                        }
                        return (strpos($a, $search) < strpos($b, $search)) ? -1 : 1;
                    });
                }
                break;
            }
        }

        if (!$found)
            return ['status' => false, 'action' => 'Invalid country name: ' . $countryName];

        return ['status' => true, 'data' => $cities ,'action' => 'Cities'];
    }

   


    public function userVerify(VerifyRequest $request)
    {
        return $this->authService->userVerify($request);
    }

    public function level()
    {
        return $this->authService->level();
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function recover(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' => $errorMessage
            ]);
        }
        return $this->authService->recover($request);
    }

    public function recoverVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|max:6'
        ]);
        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' => $errorMessage
            ]);
        }
        return $this->authService->recoverVerify($request);
    }

    public function newPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' => $errorMessage
            ]);
        }
        return $this->authService->newPassword($request);
    }

    public function changePassword(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' => $errorMessage
            ]);
        }
        return $this->authService->changePassword($request, $user_id);
    }

    public function editProfile(Request $request, $user_id)
    {
        return $this->authService->editProfile($request, $user_id);
    }


    public function helpVedio()
    {
        $video = HelpVideo::all();
        return response()->json([
            'status' => true,
            'action' => 'Videos List',
            'data' => $video
        ]);
    }

    public function logout(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $validator = Validator::make($request->all(), [
            'device_id' => 'required'
        ]);

        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        } else {
            if ($user) {
                UserDevice::where('user_id', $user_id)->where('device_id', $request->device_id)->delete();
                return response()->json([
                    'status' => true,
                    'action' => 'User logout',

                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'User not found',
                ]);
            }
        }
    }
}
