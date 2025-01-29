<?php

class CoreFunction{
    public static function hashPassword($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function validatePassword($password){
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        return preg_match($regex, $password);
    }
}