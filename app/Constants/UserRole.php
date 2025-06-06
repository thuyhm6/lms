<?php

namespace App\Constants;

class UserRole
{
    const ADMIN = 'ADM';
    const TEACHER = 'TEACHER';
    const STUDENT = 'STUDENT';
    const PARENT = 'PARENT';
    // const STAFF = 'STF';
    // const MANAGER = 'MGR';
    
    public static function getAllRoles(): array
    {
        return [
            self::ADMIN => 'Quản trị viên',
            self::TEACHER => 'Giáo viên',
            self::STUDENT => 'Học sinh',
            self::PARENT => 'Phụ huynh',
            // self::STAFF => 'Nhân viên',
            // self::MANAGER => 'Quản lý'
        ];
    }
    
    public static function getAdminRoles(): array
    {
        return [self::ADMIN];
    }
    
    public static function getTeacherRoles(): array
    {
        return [self::TEACHER, self::ADMIN];
    }
    public static function getStudentRoles(): array
    {
        return [self::STUDENT];
    }

    public static function getParentRoles(): array
    {
        return [self::PARENT];
    }
}
