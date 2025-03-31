<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Service\User\UserService;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function login()
    {
        return view('pages.login');
    }

    public function auth_login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('Email', $email)->first();
        if ($user) {
            if (Hash::check($password, $user->PasswordHash)) {
                Auth::login($user);
                if ($user->Role === 'Customer') {
                    return redirect()->route('home');
                }
                if ($user->Role === 'Admin') {
                    return redirect()->route('home');
                }
            } else {
                return redirect()->back()->withErrors(['message' => 'Mật khẩu không đúng']);
            }
        }
        else {
            return redirect()->back()->withErrors(['message' => 'Không tìm thấy tài khoản với email này']);
        }
    }

    public function register(Request $request)
    {
        $this->userService->register($request);
        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function all_users()
    {
        $all_users = User::all();
        return view('admin.all_users', [
            'all_users' => $all_users,
        ]);
    }
    public function edit_user($email)
    {
        $user = User::where('Email', $email)->first();
        if (! $user) {
            return redirect()->back()->withErrors(['message' => 'Người dùng không tồn tại']);
        }
        return view('admin.edit_users', [
            'user' => $user,
        ]);
    }

    public function update_user(Request $request, $email)
    {
        $this->userService->update_user($request, $email);
        return redirect()->back()->with('message', 'Người dùng đã được chỉnh sửa thành công');
    }

    public function remove_user($email)
    {
        $user = User::where('Email', $email)->first();
        if (! $user) {
            return redirect()->back()->withErrors(['message' => 'Người dùng không tồn tại']);
        }
        $user->delete();
        return redirect()->route('admin.all_users')->with('message', 'Người dùng đã được xóa thành công');
    }
    public function add_user()
    {
        return view('admin.add_users');
    }

    public function save_add_user(Request $request)
    {
        $this->userService->save_add_user($request);
        return redirect()->route('admin.all_users')->with('message', 'Người dùng đã được thêm thành công');
    }

    public function info_user($id)
    {
        $user = User::find($id);
        $cart = Cart::where('UserId', Auth::id())->first();
        $cartItems = $cart ? $cart->cartItems()->with('product')->get() : collect([]);

        return view('pages.user_info', [
            'user' => $user,
            'cartItems' => $cartItems,
        ]
        );
    }

    public function save_user_edit(Request $request)
    {
        $this->userService->save_user_edit($request);
        return redirect()->back()->with('message', 'Cập nhật thông tin thành công');
    }
}
