<?php

namespace App\Http\Controllers;

use App\Http\Requests\KaryawanEditRequest;
use App\Http\Requests\KaryawanRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Karyawan';
        $keyword = $request->keyword ?? null;
        $role = $request->role ?? null; // tambahkan variabel role

        $data = User::query();

        if ($keyword) {
            $data = $data->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%');
            });
        }

        if ($role) {
            $data = $data->where('role', $role);
        }

        $data = $data->orderBy('created_at', 'desc')->paginate(5)->withQueryString();

        return view('pages.karyawan', [
            'title' => $title,
            'datas' => $data,
            'keyword' => $keyword,
            'role' => $role, // kirim ke view agar select tetap
        ]);
    }

    public function create()
    {
        $title = 'Tambah Karyawan';

        return view('pages.karyawan-create', [
            'title' => $title,
        ]);
    }

    public function store(KaryawanRequest $request)
    {
        $lock = Cache::lock('karyawan-create-'.Auth::user()->id, 10);

        if (! $lock->get()) {
            return redirect()->back()->withErrors('Sedang ada proses lain, silahkan coba lagi nanti');
        }

        try {
            $data = new User;
            $data->username = $request->username;
            $data->password = bcrypt($request->password);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->role = $request->role;
            $data->profile_picture = null;
            $data->save();
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } finally {
            $lock->release();

            Cache::flush();

            return redirect()->route('karyawan')->with('success', 'Berhasil menambahkan karyawan');
        }
    }

    public function edit(User $user)
    {
        $title = 'Edit Karyawan';

        return view('pages.karyawan-edit', [
            'title' => $title,
            'user' => $user,
        ]);
    }

    public function update(KaryawanEditRequest $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->save();

            return redirect()->route('karyawan')->with('success', 'Update karyawan berhasil');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function reset_password(User $user)
    {
        $title = 'Reset Password Karyawan';

        return view('pages.karyawan-reset-password', [
            'title' => $title,
            'user' => $user,
        ]);
    }

    public function reset_password_process(Request $request, User $user)
    {
        try {
            $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect()->route('karyawan')->with('success', 'Reset password karyawan berhasil');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus karyawan',
            ]);
        } catch (Exception $e) {
            return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            ], 500);
        }
    }
}