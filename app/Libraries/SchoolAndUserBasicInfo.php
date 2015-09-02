<?php
/**
 * Created by PhpStorm.
 * User: 1084760
 * Date: 2015/09/02
 * Time: 12:39
 */

namespace app\Libraries;
use App\Models\User;
use App\Models\UsersRegisteredToSchool;
use Auth;


class SchoolAndUserBasicInfo
{
    protected $user;

    protected $userId;

    protected $schoolId;

    /**
     * SchoolAndUserBasicInfo constructor.
     * @param $user
     * @param $userId
     * @param $schoolId
     */
    public function __construct()
    {
        $userId = Auth::user()->id;

        $this->userId = $userId;

        $this->user = User::find($userId);

        $this->schoolId = UsersRegisteredToSchool::where('user_id', $userId)->get()->first()->school_id;
    }

    /**
     * @return UserId of the LoggedIn User
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return User Model for the LoggedIn User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return SchoolId of the LoggedIn User
     */
    public function getSchoolId()
    {
        return $this->schoolId;
    }
}