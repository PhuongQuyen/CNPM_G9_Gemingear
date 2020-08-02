<?php

namespace App\Http\Controllers;

use App\Banner_model;


class Customer extends Controller
{
    protected $banner_model;

    public function __construct()
    {
        $this->banner_model = new Banner_model();
    }

    public function index(Request $request)
    {
        $banner = $this->banner_model->getInfo();
        return view('customer.home', ['banner' => $banner]);
    }
	public function login(Request $request)
    {
        //Lấy ra thông tin trong form đăng nhập trừ _token (username,password)
        $data = $request->except('_token');
        // Sử dụng thư viện Auth của laravel để kiểm tra username password trong database
        if (Auth::attempt($data)) {  //Trả về true hoặc false
            $data = Auth::user();//Nếu đúng lấy ra thông tin user
            if ($data->active == 1) {//Kiểm tra user có active nếu active = 1 redirec về trang chủ
//                session flash duy nhta trong 1 route, load lai trang thi mat
                $request->session()->flash('login', 'Đăng nhập thành công');
                return redirect('/');
            } else {
                //User chưa active trả về lỗi
                Auth::logout();
                $request->session()->flash('fail', 'Đăng nhập thất bại');
                return redirect('/');
            }
        } else {
//            Auth::logout();
            $request->session()->flash('fail', 'Đăng nhập thất bại');
            return redirect('/');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    //Đăng ký
    public function signup(Request $res)
    {
    	$validator = Validator::make($res->all(), [
    			'email' => 'unique:users||required',
    			'last_name' => 'required',
    			'first_name' => 'required',
    			//            'password' => 'required|min:8',
    	//            're_password' => 'min:8|same:password'
    	],
    			[
    					'unique' => ':attribute đã tồn tại',
    					//                'require' => ':attribute d',
    			]);
    	if ($validator->fails()) {
    		return response()->json(['errors' => $validator->errors()->all()]);
    	}
    	$this->users->name = $res->input('last_name') . ' ' . $res->input('first_name');
    	$this->users->email = $res->input('email');
    	$this->users->password = bcrypt($res->input('password'));
    	$this->users->active = '0';
    	$this->users->role = 'user';
    	$message = array(
    			'name' => $res->input('last_name') . ' ' . $res->input('first_name'),
    			'link' => $res->root() . '/customer/update/' . $res->input('email'),
    			'email' => $res->input('email'),
    	);

    	if ($this->users->save()) {
    		Mail::to($res->input('email'))->send(new SendMail('Xác nhận thông tin địa chỉ email tại Gemingear.vn', $message));
    		return response()->json(['success' => 'Đăng ký thành công vui lòng kiểm tra email của bạn <a href="https://mail.google.com/mail/u/0/#inbox">Tại đây</a>']);
    	} else {
    		return response()->json(['success' => 'Đăng ký thất bại! Xin kiểm tra lại']);
    	}
    }
    public function sendMailForgotPass(Request $request)
    {
        // Lấy thông tin user từ email người dùng nhập vào
        $user = User::where('email', $request->email)->firstOrFail();

        //thêm data vào table passwordReset
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(60),
        ]);
        //Tạo data chứa token để về mail cho người dùng
        $message = array(
            'token' => $passwordReset->token,
            'email' => $request->input('email'),
        );
        //Nếu add data vào table thành công thì gửi mail
        if ($passwordReset) {
//            $user->notify(new Customer($passwordReset->token));
            Mail::to($request->input('email'))->send(new SendTokenMail('Xác nhận thông tin địa chỉ email tại Gemingear.vn', $message));
            return response()->json(['success' => 'Gửi mã xác nhận thành công vui lòng kiểm tra email của bạn']);

        }
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);

    }
//
    public function reset(Request $request)
    {
        //Lấy token người dùng nhập vào kiểm tra có tồn tại trong database
        $token = $request->token;
        $passwordReset = PasswordReset::where('token', $token)->firstOrFail();
        //Nếu thời gian lớn hơn 12h thì xóa data trong database và trả về lỗi time out
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();

            return response()->json([
                'message' => 'This password reset token time out.',
            ], 422);
        }
        // Change password
        $user = User::where('email', $passwordReset->email)->firstOrFail();
        $newPassword = Hash::make($request->password);
        $updatePasswordUser = $user->update(['password' => $newPassword]);
        $passwordReset->delete();

        return redirect('/');
    }
    //Xác thực email
    public function update($email)
    {
    	$where = array('email' => $email);
    	if ($this->users->updateInfo($where, array('active' => 1))) {
    		return redirect::to('http://127.0.0.1:8000/');
    	} else {
    		return redirect()->back();
    	}
    }
    //Kiểm tra password
    private function messages() {
    	return [
    			'password.required'     => 'Password chứa ít nhất 8 ký tự',
    			're_password.required' => 'Password không trùng khớp'
    	];
    }

}
