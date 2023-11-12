<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
class ShieldSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '
        [
            {
              "name": "admin",
              "guard_name": "web",
              "permissions": [
                "view_academic::year",
                "view_any_academic::year",
                "create_academic::year",
                "update_academic::year",
                "restore_academic::year",
                "restore_any_academic::year",
                "replicate_academic::year",
                "reorder_academic::year",
                "delete_academic::year",
                "delete_any_academic::year",
                "force_delete_academic::year",
                "force_delete_any_academic::year",
                "view_extracurricular",
                "view_any_extracurricular",
                "create_extracurricular",
                "update_extracurricular",
                "restore_extracurricular",
                "restore_any_extracurricular",
                "replicate_extracurricular",
                "reorder_extracurricular",
                "delete_extracurricular",
                "delete_any_extracurricular",
                "force_delete_extracurricular",
                "force_delete_any_extracurricular",
                "view_grade",
                "view_any_grade",
                "create_grade",
                "update_grade",
                "restore_grade",
                "restore_any_grade",
                "replicate_grade",
                "reorder_grade",
                "delete_grade",
                "delete_any_grade",
                "force_delete_grade",
                "force_delete_any_grade",
                "view_shield::role",
                "view_any_shield::role",
                "create_shield::role",
                "update_shield::role",
                "delete_shield::role",
                "delete_any_shield::role",
                "view_student",
                "view_any_student",
                "create_student",
                "update_student",
                "restore_student",
                "restore_any_student",
                "replicate_student",
                "reorder_student",
                "delete_student",
                "delete_any_student",
                "force_delete_student",
                "force_delete_any_student",
                "view_student::extracurricular",
                "view_any_student::extracurricular",
                "create_student::extracurricular",
                "update_student::extracurricular",
                "restore_student::extracurricular",
                "restore_any_student::extracurricular",
                "replicate_student::extracurricular",
                "reorder_student::extracurricular",
                "delete_student::extracurricular",
                "delete_any_student::extracurricular",
                "force_delete_student::extracurricular",
                "force_delete_any_student::extracurricular",
                "view_student::grade",
                "view_any_student::grade",
                "create_student::grade",
                "update_student::grade",
                "restore_student::grade",
                "restore_any_student::grade",
                "replicate_student::grade",
                "reorder_student::grade",
                "delete_student::grade",
                "delete_any_student::grade",
                "force_delete_student::grade",
                "force_delete_any_student::grade",
                "view_subject",
                "view_any_subject",
                "create_subject",
                "update_subject",
                "restore_subject",
                "restore_any_subject",
                "replicate_subject",
                "reorder_subject",
                "delete_subject",
                "delete_any_subject",
                "force_delete_subject",
                "force_delete_any_subject",
                "view_teacher",
                "view_any_teacher",
                "create_teacher",
                "update_teacher",
                "restore_teacher",
                "restore_any_teacher",
                "replicate_teacher",
                "reorder_teacher",
                "delete_teacher",
                "delete_any_teacher",
                "force_delete_teacher",
                "force_delete_any_teacher",
                "view_teacher::extracurricular",
                "view_any_teacher::extracurricular",
                "create_teacher::extracurricular",
                "update_teacher::extracurricular",
                "restore_teacher::extracurricular",
                "restore_any_teacher::extracurricular",
                "replicate_teacher::extracurricular",
                "reorder_teacher::extracurricular",
                "delete_teacher::extracurricular",
                "delete_any_teacher::extracurricular",
                "force_delete_teacher::extracurricular",
                "force_delete_any_teacher::extracurricular",
                "view_teacher::grade",
                "view_any_teacher::grade",
                "create_teacher::grade",
                "update_teacher::grade",
                "restore_teacher::grade",
                "restore_any_teacher::grade",
                "replicate_teacher::grade",
                "reorder_teacher::grade",
                "delete_teacher::grade",
                "delete_any_teacher::grade",
                "force_delete_teacher::grade",
                "force_delete_any_teacher::grade",
                "view_teacher::subject",
                "view_any_teacher::subject",
                "create_teacher::subject",
                "update_teacher::subject",
                "restore_teacher::subject",
                "restore_any_teacher::subject",
                "replicate_teacher::subject",
                "reorder_teacher::subject",
                "delete_teacher::subject",
                "delete_any_teacher::subject",
                "force_delete_teacher::subject",
                "force_delete_any_teacher::subject",
                "view_user",
                "view_any_user",
                "create_user",
                "update_user",
                "restore_user",
                "restore_any_user",
                "replicate_user",
                "reorder_user",
                "delete_user",
                "delete_any_user",
                "force_delete_user",
                "force_delete_any_user"
              ]
            },
            {
              "name": "teacher",
              "guard_name": "web",
              "permissions": [
                "view_competency",
                "view_any_competency",
                "create_competency",
                "update_competency",
                "restore_competency",
                "restore_any_competency",
                "replicate_competency",
                "reorder_competency",
                "delete_competency",
                "delete_any_competency",
                "force_delete_competency",
                "force_delete_any_competency",
                "view_exam",
                "view_any_exam",
                "create_exam",
                "update_exam",
                "restore_exam",
                "restore_any_exam",
                "replicate_exam",
                "reorder_exam",
                "delete_exam",
                "delete_any_exam",
                "force_delete_exam",
                "force_delete_any_exam",
                "view_student::competency",
                "view_any_student::competency",
                "create_student::competency",
                "update_student::competency",
                "restore_student::competency",
                "restore_any_student::competency",
                "replicate_student::competency",
                "reorder_student::competency",
                "delete_student::competency",
                "delete_any_student::competency",
                "force_delete_student::competency",
                "force_delete_any_student::competency"
              ]
            },
            {
              "name": "teacher_grade",
              "guard_name": "web",
              "permissions": [
                "view_attendance",
                "view_any_attendance",
                "create_attendance",
                "update_attendance",
                "restore_attendance",
                "restore_any_attendance",
                "replicate_attendance",
                "reorder_attendance",
                "delete_attendance",
                "delete_any_attendance",
                "force_delete_attendance",
                "force_delete_any_attendance",
                "page_MyGrade"
              ]
            }
          ]
        ';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
