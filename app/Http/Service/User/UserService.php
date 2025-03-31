<?php
namespace App\Http\Service\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,Email',
            'password' => 'required|min:6|confirmed',
            'fullname' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        User::create([
            'Username' => $request->input('email'),
            'Email' => $request->input('email'),
            'PasswordHash' => Hash::make($request->input('password')),
            'FullName' => $request->input('fullname'),
        ]);
        return true;
    }
    public function update_user(Request $request, $email)
    {
        $user = User::where('Email', $email)->first();
        if (! $user) {
            return redirect()->back()->withErrors(['message' => 'Người dùng không tồn tại']);
        }
        $user->Username = $request->input('username');
        $user->Email = $request->input('email');
        $user->FullName = $request->input('name');
        $user->PhoneNumber = $request->input('phoneNumber');
        $user->Address = $request->input('address');
        $user->Role = $request->input('role');
        $newPassword = $request->input('password');
        if ($newPassword && ! Hash::check($newPassword, $user->PasswordHash)) {
            $user->PasswordHash = Hash::make($newPassword);
        }
        if($user->save()) {
            return true;
        }
        return redirect()->back()->with('error', 'Người dùng đã được chỉnh sửa không thành công');
    }
    public function save_add_user(Request $request)
    {
        if (User::where('Email', $request->input('email'))->exists()) {
            return redirect()->back()->withErrors(['email' => 'Email này đã tồn tại.']);
        }
        $user = new User;
        $user->Username = $request->input('username');
        $user->Email = $request->input('email');
        $user->PasswordHash = Hash::make($request->input('password'));
        $user->FullName = $request->input('name');
        $user->Address = $request->input('address');
        $user->PhoneNumber = $request->input('phoneNumber');
        $user->Role = $request->input('role');
        if ($user->save()) {
            return true;
        }
        return redirect()->back()->with('error', 'Người dùng đã được thêm không thành công');
    }
    public function save_user_edit(Request $request)
    {
        $user = User::where('Email', $request->email)->first();
        if ($user) {
            $user->Username = $request->name;
            $user->PhoneNumber = $request->phone;
            $user->Address = $request->address;
            if($user->save()) {
                return true;
            } else {
                return redirect()->back()->with('error', 'Cập nhật không thành công');
            }
        } else {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng');
        }
    }

}
