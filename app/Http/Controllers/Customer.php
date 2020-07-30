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
