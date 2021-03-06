<?php
/**
 * User: Sumit Prasad
 * Date: 2015/08/25
 * Time: 9:18
 */

namespace app\Libraries;
use App\Models\User;
use App\Models\UserGroup;

class RequiredFunctions {

    /**
     * @param $email
     * @return true if email is of domain : user.xx, teacher.xx, admin.xx, school.xx  [xx - any domain]
     * @return false if any other type of email domain
     */
    public static function checkIfTestEmail($email){

        $breakEmail = explode('@', $email);
        $testEmailArray = explode('.', $breakEmail[1]);

        if($testEmailArray[0] == 'user' || $testEmailArray[0] == 'admin' || $testEmailArray[0] == 'teacher' || $testEmailArray[0] == 'school'){
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @return 1 if User is Administrator
     * @return 2 if User is Student
     * @return 3 if User is Teacher
     */
    public static function checkUserTypeByEmail($email){

        $user = User::where('email', $email)->get()->first();
        $userGroup = UserGroup::where('user_id', $user->id)->get()->first();

        return $userGroup->group_id;
    }

    /**
     * @param $userId
     * @return 1 if User is Administrator
     * @return 2 if User is Student
     * @return 3 if User is Teacher
     */
    public static function checkUserTypeByUserId($userId){

        $userGroup = UserGroup::where('user_id', $userId)->get()->first();
        return $userGroup->group_id;
    }

}