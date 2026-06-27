<?php

namespace App\Http\Controllers;

use App\Helpers\API;
use App\Helpers\SIAP;
use Session;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        if (!empty(session('userdata'))) return redirect('/');
        $data['sso_url'] = env('AUTH_URL', 'https://account.kemnaker.go.id/') . 'auth?response_type=code&client_id=' . env("CLIENT_ID", '2f5da25d-0044-4371-9267-2fa693ead36b') . '&redirect_uri=' . url('/sso-callback') . '&scope=basic email';
        return view('auth.login', $data);
    }
    public function ssoLogin()
    {
        $auth_code = @request()->code;
        $res = API::get('auth/login-pegawai?redirect=0&code=' . $auth_code);
        if (@$res->getStatusCode() == '200') {
            $body = json_decode($res->getBody()->getContents());
            $token = $body->data->token;
            $tokenParts = explode(".", $body->data->token);
            $tokenHeader = base64_decode($tokenParts[0]);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtHeader = json_decode($tokenHeader);
            $jwtPayload = json_decode($tokenPayload);
            if ($jwtPayload->user->role == 'pegawai') {
                return redirect('/login')->with('error-login', 'Anda tidak memiliki akses untuk melakukan login!');
            }
            $session['token'] = $body->data->token;
            $session['userdata'] = $jwtPayload->user;
            session($session);
            return redirect('/');
        } else {
            return redirect()->back()->with('error', @$res->getStatusCode() . ' - ' . @$res->getReasonPhrase());
        }
    }
    public function login(Request $request)
{
    if (
        $request->email == 'reza290405@gmail.com' &&
        $request->password == '123456'
    ) {

        session([
            'userdata' => (object)[
                'id_user' => 1,
                'id_pegawai' => 1,
                'name' => 'Muhammad Reza',
                'email' => 'reza290405@gmail.com',
                'role' => 'admin',
                'satkerid' => '010103'
            ],
            'token' => 'dummy-token'
        ]);

        return redirect('/');
    }

    return redirect()->back()->with(
        'error',
        'Email atau Password salah'
    );
}

    public function profil()
    {
        $user = session('userdata');
        if ($user->role != 'admin') {
            $body['pegawaiid'] = $user->id_pegawai;
            $res = SIAP::post('ws_naker_data/data_pns', $body);
            $body = $res->getBody()->getContents();
            if ($res->getStatusCode() == 200) {
                $content = json_decode($body);
                $data['pegawai'] = @$content->data[0];
                return view('pegawai.profil', $data);
            }
        } else {
            return view('pegawai.profil');
        }
    }

    public function logout(Request $request)
    {
        $user = session('userdata');
        $role = $user->role;
        $pesan = "";
        if (Session::get('error')) {
            $pesan = Session::get("error");
            $err = 1;
        } else {
            $err = 0;
        }

        $request->session()->flush();
        $request->session()->regenerate();
        if ($role == 'tu') return redirect(env('AUTH_URL', 'https://account.kemnaker.go.id/'));
        if ($err == 1) {
            return redirect('login')->with('error', $pesan);
        } else {
            return redirect('login');
        }
    }

    public function changePassword(Request $request)
    {
        $messages = [
            'password_baru.same' => 'Password confirmation does not match!',
            'password_baru.regex' => 'Your password must be more than 8 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.!',
        ];
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'password_baru' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()
            ]);
        }
        $res = API::post('change-password', [
            'id_user' => $request->id_user,
            'password' => $request->password,
            'password_baru' => $request->password_baru,
        ]);
        $body = json_decode($res->getBody()->getContents());
        if (@$res->getStatusCode() == '200') {
            return response()->json([
                'success' => true,
                'message' => $body->message
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $body->message
            ]);
        }
    }
}
