<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="col-md-5 mx-auto">
            <div class="myform form ">
                <div class="logo mb-3">
                    <div class="col-md-12 text-center">
                        <h1 style="font-family: serif;">Đăng nhập</h1>
                    </div>
                </div>
                <form method="post" name="login">
                   
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1">Địa chỉ email</label>
                        <input type="email" name="email" required class="form-control" aria-describedby="emailHelp"
                               placeholder="Địa chỉ email">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1">Mật khẩu</label>
                        <input type="password" name="password" required class="form-control"
                               aria-describedby="emailHelp" placeholder="Mật khẩu">
                    </div>
                    <div class="col-md-12 text-center ">
                        <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Đăng nhập</button>
                    </div>
                    <div class="form-group col-md-12" style="padding-top:5px;">
                        <p class="text-center">Quên mật khẩu? Nhấn vào<a href="#"
                                                                         data-dismiss="modal" data-toggle="modal"
                                                                         data-target="#forgotModalCenter"
                                                                         style="color:dodgerblue;"> đây</a>
                        </p>
                    </div>
                    <div class="col-md-12 ">
                        <div class="login-or">
                            <hr class="hr-or">
                            <span class="span-or">Hoặc</span>
                        </div>
                    </div>
                    <div class="com-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="text-center">
                                    <a href="#" style="background-color:red;color:white;"
                                       class="google btn mybtn"><i class="fa fa-google">
                                        </i>oogle
                                    </a>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-center">
                                    <a href="#" style="background-color:#0069d9;color:white;"
                                       class="google btn mybtn"><i class="fa fa-facebook">
                                        </i>acebook
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12" style="padding-top:5px;">
                        <p class="text-center">Nếu bạn chưa có tài khoản? <a href="#" id="signup"
                                                                             style="color:dodgerblue;">Đăng ký tài
                                khoản</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
