<?php
/**
 *
 * User: MVB
 * Date: 25-06-2015
 * Time: 16:12
 */

namespace App\CN\CNUsers;


use App\CN\CNAccessTokens\AccessToken;
use App\CN\CNDepartments\Department;
use App\CN\Repositories\BaseRepository;
use App\CN\Repositories\CustomModel;
use App\Events\UserRegistered;
use App\Http\Requests\Request;
use CN\Users\UserInterface;
use App\CN\CNUsers\User;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Mockery\Exception;
use Tymon\JWTAuth\JWTAuth;

class UsersRepository extends BaseRepository implements UserInterface
{
    /**
     *Method to retrieve cn user
     * @param JWTAuth $auth
     * @internal param $id
     */
    protected $auth, $mailer;//,$request;

    //protected $salt = "c2150$#@Mani";

    public function __construct(JWTAuth $auth)
    {

        $this->auth = $auth;
        // $this->request=$request;
    }


    public function getUserDetails($id)
    {

        return User::findorFail($id);
    }


    public function getAllUserDetails($token)
    {
        $ttoken = $this->retrieveTokenFromHeader($token);

        $id = $this->getUserIdFromToken($ttoken);

        $user = User::findorFail($id);

        $dept = $user->department();

        dd($id,Department::with('user'));
        return response()->json($user->toArray(),200);
    }


    /**
     * Create User
     */
    public function createUser()
    {

        /*$user = new User();

        if (empty(Input::get('password')) && Input::get('roleId') == 2) {

            $passwd = $this->generatePassword();

            $user->password = bcrypt($passwd);
        }

        $user->firstName = Input::get('firstName');

        $user->lastName = Input::get('lastName');

        $user->email = Input::get('email');

        $user->roleId = Input::get('roleId');

        $user->collegeId = Input::get('collegeId');

        $user->deptId = Input::get('deptId');


        /*$user->fill(Input::all());*/
       /* try {
            $user->save();

            event(new UserRegistered($user, $passwd));

        } catch (Exception $e) {

            return response()->json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);

        }

        $token = $this->auth->fromUser($user);

        return response()->json(compact('token'));*/
        //CustomModel $model = new User();
        return parent::createGenericRecord(new User());
    }

    /**
     * @param $credentials
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $auth
     * @internal param $request
     */
    public function  authenticate(array $credentials)
    {

        try {

            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']
                , 'isActive' => '1'])
            ) {

                $onlyCredentials = array('email' => $credentials['email'], 'password' => $credentials['password']);
                //dd($onlyCred);

                // verify the credentials and create a token for the user
                if (!$token = $this->auth->attempt($onlyCredentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);

                }
                // dd($this->auth->getPayload(true));

                $idealExpirationTime = $this->auth->getPayload($token)->get('exp');

                $accessToken = AccessToken::firstOrCreate(['accessToken' => $token, 'deviceType' => $credentials['Device'],
                    'mediaType' => $credentials['MediaType'], 'osName' => $credentials['OS'], 'userId' => "1"
                    ,'idealTimeExpirationDuration'=>$idealExpirationTime ]);
                return response()->json(array(['token' => $token, 'isActive' => '1' ,'idealTimeExpirationDuration'=>$idealExpirationTime]));
            }else{

                return response()->json(array(['message' => "User does not exist"]));

            }
        }catch(JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }


    }

    public function getAll()
    {
        return User::all();
    }

    /*public function findByUserNameOrCreate($userData)
    {
        if ($fromFB) {
            $users = $userData->user;
            return CNUsersModel::firstOrCreate([
                'first_name' => $users['first_name'],
                'last_name' => $users['last_name'],
                'email' => $users['email']
            ]);
        } else {

            $users = $userData->name;
            $emailarr =$userData->emails;
            $email =$emailarr->value;
            return SalonUsersModel::firstOrCreate([
                'first_name' => $users['familyName'],
                'last_name' => $users['givenName'],
                'email' => $email
            ]);
        }
    }*/

    /**
     *Generate access token
     * @return
     */
//    public function generateAccessToken(){
//
//        return bcrypt(str_random(60));
//
//    }

    /**
     * @return bool
     */
    public function logout($token){

        // dd(Request::header());
        $ttoken = retrieveTokenFromHeader($token);

        $id = $this->getUserIdFromToken($ttoken);

        $this->auth->invalidate($ttoken);

        $accessToken = AccessToken::where('userId', $id)->first();

        $accessToken->delete();

        return "logged out";
    }

    public function retrieveTokenFromHeader($token){

        $index = 1;

        $ttoken = explode(" ",$token);

        return $ttoken[$index];

    }

    public function getUserIdFromToken($ttoken){

        $id = $this->auth->getPayload($ttoken)->get('sub');

        return $id;

    }

}

