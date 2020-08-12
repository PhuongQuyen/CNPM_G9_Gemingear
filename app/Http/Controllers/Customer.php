<?php

namespace App\Http\Controllers;

use App\Banner_model;


class Customer extends Controller
{
// khai báo thuộc tính user, banner, và reset_Pass
    protected $users, $banner_model, $reset_Pass;
//  khởi tạo các giá trị cho các thuộc tính
    public function __construct()
    {
        $this->users = new Users();
        $this->banner_model = new Banner_model();
        $this->reset_Pass = new PasswordReset();
    }
// trả về trang chủ
    public function index(Request $request)
    {
        $banner = $this->banner_model->getInfo();
        return view('customer.home', ['banner' => $banner]);
    }

    public function login(Request $request)
    {
        // 4. Lấy ra thông tin email, và mật khẩu, trừ _token
        $data = $request->except('_token');
        // 5. Sử dụng thư viện Auth của laravel để kiểm tra email và password trong database
        if (Auth::attempt($data)) {  //Trả về true hoặc false
//            tìm thấy user có trong database
            $data = Auth::user();//Nếu true lấy ra thông tin user
            if ($data->active == 1) {//Kiểm tra user có active nếu active = 1 redirec về trang chủ
//                session flash duy nhta trong 1 route, load lai trang thi mat
//                6. Thông báo đăng nhập thành công và trả về trang chủ
                $request->session()->flash('login', 'Đăng nhập thành công');
                return redirect('/');
            } else {
                //User chưa active trả về lỗi
                Auth::logout();
//                thông báo đăng nhập thất bại và trả về trang chủ
                $request->session()->flash('fail', 'Đăng nhập thất bại');
                return redirect('/');
            }
        }
        else {
// Không tìm thấy user thì thông báo lỗi và trả về trang chủ
            Auth::logout();
            $request->session()->flash('fail', 'Đăng nhập thất bại');
            return redirect('/');
        }
    }
// logout ra khỏi phiên đăng nhập
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    //Đăng ký
       public function signup(Request $res)
    {
        //Kiểm tra xem email đã được đăng ký hay chưa
        $validator = Validator::make($res->all(), [
            'email' => 'unique:users',
        ],
            [
                'unique' => ':attribute đã tồn tại',
            ]);
        //Email tồn tại, hiển thị thông báo
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }
        //Lấy thông tin input
        $this->users->name = $res->input('last_name') . ' ' . $res->input('first_name');
        $this->users->email = $res->input('email');
        $this->users->password = bcrypt($res->input('password'));
        $this->users->active = '0'; //email chưa xác thực mặc định active=0, khi đã xác thực được email thì cập nhật active=1
        $this->users->role = 'user'; //quyền mặc định là user
        //Đóng gói những thành phần chuẩn bị cho gửi mail xác thực
        $message = array(
            'name' => $res->input('last_name') . ' ' . $res->input('first_name'),
            'link' => $res->root() . '/customer/update/' . $res->input('email'),
            'email' => $res->input('email'),
        );
        //Lưu thông tin tạm thời mà hiện thị thông tin yêu cầu xác thực email
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
}
