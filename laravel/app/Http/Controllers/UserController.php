<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

$var = 'const switchers = [...document.querySelectorAll(\'.switcher\')]';


class UserController extends Controller
{

    // NOTE: Both postSignUp and postSignIn will receive a request object.
    // NOTE: Because we click on our button and then a request is sent to our server and is handled by Laravel.

      /**
       * Both postSignUp and postSignIn will receive a request object.
       * Because we click on our button and then a request is sent to our server and is handled by Laravel.
       * And we will soon, in our routes.php file, hook up our functions, our methods that we are writing
       * at the moment to the to the responding request from our form.
       * As these are post requests, we will have our parameters in the request body. Therefore we need to access the request.
       * We access the request using Laravel's dependency injection which is very powerful.
       * We have to specify the object you want to inject. So the type object. In our case the object type will be of type Request.
       * And it lives in Illuminate\Http\Request namespace.
       */

    // NOTE: Executed when submit button is clicked in signup form
    // NOTE: Use dependency injection to inject object of type Request and create object variable $request
    public function postSignUp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|max:120',
            'password' => 'required|min:4'
        ]);

        // NOTE: We will have email in the request
        /**
         * The name of the input (from the welcome view) element will be passed in this request array,
         * and we can access this field by just using the name specified in the view.
         *
         * We access email by adding the request function.
         * It will be an array where we can just access our email field by the name given
         * in the form element in welcome view
         */
        $email = $request['email'];
        $first_name = $request['first_name'];
        // NOTE: Laravel helper function bcrypt is used to hash password
        $password = bcrypt($request['password']);

        // NOTE: create new variable named $user and set it equal to new instance of our user object
        // NOTE: Now we can, as a set, just access the fields in our table like properties of this model
        $user = new User();
        // NOTE: We'll just access user email like a property and set it equal to email
        $user->email = $email;
        // NOTE: Access user first_name and set it equal to first_name
        $user->first_name = $first_name;
        // NOTE: Access user password and set it equal to our encrypted password
        $user->password = $password;

        // NOTE: Write new user to database
        $user->save();

        Auth::login($user);

        // NOTE: Redirect to welcome view after signup
        return redirect()->route('dashboard');
    }

    // NOTE: Executed when submit button is clicked in signin form
    // NOTE: Use dependency injection to inject object of type Request and create object variable $request
    public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            return redirect()->route('dashboard');
        }
        return redirect()->back();
    }

    // NOTE: Executed when logout button is clicked
    public function getLogout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    // NOTE: Executed when account button is clicked from dashboard
    public function getAccount()
    {
        return view('account', ['user' => Auth::user()]);
    }

    // NOTE: Executed when submit button is clicked in signup form to save new account
    // NOTE: Use dependency injection to inject object of type Request and create object variable $request
    public function postSaveAccount(Request $request)
    {
        $this->validate($request, [
           'first_name' => 'required|max:120'
        ]);

        $user = Auth::user();
        $old_name = $user->first_name;
        $user->first_name = $request['first_name'];
        $user->update();
        $file = $request->file('image');
        $filename = $request['first_name'] . '-' . $user->id . '.jpg';
        $old_filename = $old_name . '-' . $user->id . '.jpg';
        $update = false;
        if (Storage::disk('local')->has($old_filename)) {
            $old_file = Storage::disk('local')->get($old_filename);
            Storage::disk('local')->put($filename, $old_file);
            $update = true;
        }
        if ($file) {
            Storage::disk('local')->put($filename, File::get($file));
        }
        if ($update && $old_filename !== $filename) {
            Storage::delete($old_filename);
        }
        return redirect()->route('account');
    }

    // NOTE: Executed when 'Choose File' button from accounts page, saves account icon to local storage
    public function getUserImage($filename)
    {
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }

    public function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
    ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }


    // NOTE: Switch forms
    protected $listeners = ['switchForm' => 'switchFormView'];

    // public function switchFormView( $var)
    // {
    //     switchers.forEach(item => {
    //         item.addEventListener(\'click\', function() {
    //             switchers.forEach(item => item.parentElement.classList.remove(\'is-active\'))
    //             this.parentElement.classList.add(\'is-active\')
    //         })
    //     })
    // }



}
