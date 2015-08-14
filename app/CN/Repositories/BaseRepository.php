<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 13-08-2015
 * Time: 21:49
 */

namespace App\CN\Repositories;


use App\CN\Interfaces\CustomModel;
use Illuminate\Support\Facades\Input;
use App\CN\CNAccessTokens\AccessToken;
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
use Mockery\Exception;
use Tymon\JWTAuth\JWTAuth;


abstract class BaseRepository {

    /**
     * Method for creating genric record for registration in DB
     * @param CustomModel $model
     * @return mixed
     */
    protected function createGenericRecord(CustomModel $model){

        if (empty(Input::get('password')) && Input::get('roleId') == 2) {

            $passwd = $this->generatePassword();

            $model->password = bcrypt($passwd);
        }

        $model->firstName = Input::get('firstName');

        $model->lastName = Input::get('lastName');

        $model->email = Input::get('email');

        $model->roleId = Input::get('roleId');

        $model->collegeId = Input::get('collegeId');

        $model->deptId = Input::get('deptId');

        if ( Input::hasFile('avatar')) {

            $file = Input::file('avatar');
            $name = time().'-'.$file->getClientOriginalName();
            $file = $file->move('uploads/', $name);
            $model->avatarUrl = $name;
            //dd( $model->avatarUrl);
        }

        /*$user->fill(Input::all());*/
        try {
            $model->save();

            event(new UserRegistered($model, $passwd));

        } catch (Exception $e) {

            return response()->json(['error' => 'User already exists.'], HttpResponse::HTTP_CONFLICT);

        }

        $token = $this->auth->fromUser($model);

        $idealExpirationTime = $this->auth->getPayload($token)->get('exp');

        return response()->json(array(['token' => $token, 'isActive' => '1' ,'idealTimeExpirationDuration'=>$idealExpirationTime]));


    }

    /**
     *generate six word random password
     */
    private function generatePassword($l = 6)
    {

        return substr(md5(uniqid(mt_rand(), true)), 0, $l);

    }


}