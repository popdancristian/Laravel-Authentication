<?php

namespace App\Http\Controllers;

use App\Rules\IsValidPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserLogin;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\App;

class UserAuthController extends Controller
{
    function login()
    {
        // var_dump($_ENV);die();
        return view('auth.login');
    }
    function register()
    {
        return view('auth.register');
    }

    function create(Request $request)
    {
        //return $request->input();

        //var_dump($request->has('terms'));die();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:user_logins',
            'password' => [
                'required',
                new isValidPassword(),
            ],
            'password_confirmation' => 'required|same:password',
            'terms' => 'accepted',
            'phone_number' => 'required|min:10'
        ]);

        // at least 10 characters , allow numbers only


        // $user = new UserLogin;
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = Hash::make($request->password);
        // $query = $user->save();

        $verification_code = sha1(time());
        $query = DB::table('user_logins')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'verification_code' => $verification_code,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        if ($query) {
            MailController::sendEmail($request->name, $request->email, $verification_code);
            return back()->with('success', 'You have succesfully register. Please verify your email for activation link!');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                new isValidPassword(),
            ],
        ]);

        //$user = UserLogin::where('email','=', $request->email)->first();
        $user = DB::table('user_logins')
            ->where('email', $request->email)
            ->first();

        //print_r($user->is_verified);
        //die();
        if ($user) {
            if ($user->is_verified == 0) {
                //    var_dump(  route('resendEmail/id/1', ['projects' => $user->id]));die();
                $url = url('resendEmail/id') . '/' . $user->id;
                return back()->with(
                    'fail',
                    "Your account is not yet active. <a href= $url>Click here</a> to resend the activation email."
                )->withInput();
            }


            if ($user->fail_login_number > 2) {
                 //Log warning
                 Log::warning($request->ip());

                $diff = strtotime(now()) - strtotime($user->updated_at);
                if ($diff < 30) {
                    $block = 30 - $diff;
                    return back()->with('fail', 'The user is blocked for ' . $block . ' seconds!')->withInput();
                } else {
                    $query = DB::table('user_logins')
                        ->where('id', $user->id)
                        ->update([
                            'fail_login_number' => 0,
                            'updated_at' => now()
                        ]);
                }
            }

            $user = DB::table('user_logins')
                ->where('email', $request->email)
                ->first();
            if (Hash::check($request->password, $user->password)) {
                $request->session()->put('LoggedUser', $user->id);
                // var_dump(session());die();
                return redirect('profile');
            } else {
                // if ($user->fail_login_number > 2) {
                //     return back()->with('fail', 'Invalid Pass. The user is blocked for 30 seconds!')->withInput();
                // }
                //$request->ip()
                $query = DB::table('user_logins')
                    ->where('id', $user->id)
                    ->update([
                        'fail_login_number' => $user->fail_login_number + 1,
                        'updated_at' => now()
                    ]);


                return back()->with('fail', 'Invalid Pass')->withInput();
            }
        } else {
            return back()->with('fail', 'No account found for this email')->withInput();
        }
    }

    function verify(Request $request)
    {
        $verification_code = $request->input('code');
        //$user = UserLogin::where('verification_code','=', $verification_code)->first();
        $user = DB::table('user_logins')
            ->where('verification_code', $verification_code)
            ->first();

        if ($user) {
            // $user->is_verified = 1;
            // $user->save();
            $query = DB::table('user_logins')
                ->where('id', $user->id)
                ->update([
                    'is_verified' => 1,
                    'updated_at' => now()
                ]);

            if ($query) {
                return redirect('login')->with('success', 'Your account is verified, please login!');
            }
        }

        return redirect('login')->with('fail', 'Invalid verification code!');
        //var_dump($verification_code);die();
    }

    function profile()
    {
        if (session()->has('LoggedUser')) {
            // $user = UserLogin::where('id','=', session('LoggedUser'))->first();
            $user = DB::table('user_logins')
                ->where('id', session('LoggedUser'))
                ->first();
            // echo '<pre>';var_dump(session());//die();
            $data = [
                'LoggedUserInfo' => $user

            ];
        }
        $users = UserLogin::latest()->paginate(5);

        return view('admin.profile', $data, compact('users'))->with(request()->input('page'));
    }

    function logout()
    {
        if (session()->has('LoggedUser')) {
            session()->pull('LoggedUser');
            return redirect('login');
        }
    }

    function resendEmail(Request $request)
    {
        $query = DB::table('user_logins')
            ->where('id', $request->id)
            ->first();

        if ($query) {
            // var_dump($query);die();
            MailController::sendEmail($query->name, $query->email, $query->verification_code);
            return redirect('login')->with('success', 'Please verify your email for activation link!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserLogin $user)
    {
        $user->delete();

        return $this->profile();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(UserLogin $user)
    {
        return view('admin.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserLogin $user)
    {
        $request->validate([
            'email' => 'required',
            'phone_number' => 'required',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->profile();
    }

    function unverify(Request $request, UserLogin $user)
    {
        $user = UserLogin::find($request->id);
        $user->is_verified = 0;

        $user->update($request->all());

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = UserLogin::find($id);
        // var_dump($user);die();
        $user->delete();

        return $this->profile();
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $data = UserLogin::where('email', 'LIKE', $request->words . '%')
                ->orWhere('name', 'like', '%' . $request->words . '%')
                ->orWhere('phone_number', 'like', '%' . $request->words . '%')
                ->get();

            $output ='<thead>
            <th>ID</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th width="350px">Action</th>
            </thead>';

            if (count($data) > 0) {
                foreach ($data as $user) {

                    $output .= "<tr id= '$user->id'>
                        <td> $user->id </td>
                        <td>$user->email </td>
                        <td>$user->phone_number</td>
                        <td>
                            <form action=" . route('users.destroy', $user->id) . " method='POST'>
                                <a class='btn btn-primary' href=" . route('users.edit', $user->id) . ">Edit</a>

                                <button type='button' id='button' data-id='$user->id'
                                    class='btn btn-danger'>Delete</button>";
                    if ($user->is_verified) {
                        $output .= "<a class='btn btn-warning'
                                        href=" . route('users.unverify', $user->id) . ">Un-verify</a>";
                    }


                    $output .=" </form>
                        </td>
                    </tr>";
                }


            } else {

                $output .= '<tr><td colspan="4">' . 'No results' . '</td></tr>';
            }

            return $output;
        }
    }
}
