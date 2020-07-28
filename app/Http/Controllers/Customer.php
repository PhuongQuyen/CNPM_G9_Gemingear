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
    		return response()->json(['success' => 'Đăng ký thành công vui lòng kiểm tra email của bạn']);
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
