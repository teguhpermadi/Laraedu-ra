<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'super admin']);
        $admin = Role::create(['name' => 'admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $teacherGrade = Role::create(['name' => 'teacher grade']);
        
        // academic year
        Permission::create(['name' => 'activated AcademicYear']);
        // teacher & student
        Permission::create(['name' => 'userable Teacher']);
        Permission::create(['name' => 'userable Student']);

        // sinkronkan semua permission dengan policies
        Artisan::call('permissions:sync --yes-to-all');

        // sinkronkan semua role dengan permission
        $admin->syncPermissions([
            'view-any AcademicYear',
            'view AcademicYear',
            'create AcademicYear',
            'update AcademicYear',
            'delete AcademicYear',
            'activated AcademicYear',
            // 'restore AcademicYear',
            // 'force-delete AcademicYear',
            'view-any Teacher',
            'view Teacher',
            'create Teacher',
            'update Teacher',
            'delete Teacher',
            'userable Teacher',
            // 'restore Teacher',
            // 'force-delete Teacher',
            'view-any Student',
            'view Student',
            'create Student',
            'update Student',
            'delete Student',
            'userable Student',
            // 'restore Student',
            // 'force-delete Student',
            'view-any Subject',
            'view Subject',
            'create Subject',
            'update Subject',
            'delete Subject',
            // 'restore Subject',
            // 'force-delete Subject',
            'view-any Grade',
            'view Grade',
            'create Grade',
            'update Grade',
            'delete Grade',
            // 'restore Grade',
            // 'force-delete Grade',
            'view-any User',
            'view User',
            'create User',
            'update User',
            'delete User',
            // 'restore User',
            // 'force-delete User',
            'view-any TeacherSubject',
            'view TeacherSubject',
            'create TeacherSubject',
            'update TeacherSubject',
            'delete TeacherSubject',
            // 'restore TeacherSubject',
            // 'force-delete TeacherSubject',
            'view-any StudentGrade',
            'view StudentGrade',
            'create StudentGrade',
            'update StudentGrade',
            'delete StudentGrade',
            // 'restore StudentGrade',
            // 'force-delete StudentGrade',
            'view-any TeacherGrade',
            'view TeacherGrade',
            'create TeacherGrade',
            'update TeacherGrade',
            'delete TeacherGrade',
            // 'restore TeacherGrade',
            // 'force-delete TeacherGrade',
            'view-any Extracurricular',
            'view Extracurricular',
            'create Extracurricular',
            'update Extracurricular',
            'delete Extracurricular',
            // 'restore Extracurricular',
            // 'force-delete Extracurricular',
            'view-any TeacherExtracurricular',
            'view TeacherExtracurricular',
            'create TeacherExtracurricular',
            'update TeacherExtracurricular',
            'delete TeacherExtracurricular',
            // 'restore TeacherExtracurricular',
            // 'force-delete TeacherExtracurricular',
        ]);

        $teacher->syncPermissions([
            'view-any Competency',
            'view Competency',
            'create Competency',
            'update Competency',
            'delete Competency',
            // 'restore Competency',
            // 'force-delete Competency',
            'view-any StudentCompetency',
            'view StudentCompetency',
            'create StudentCompetency',
            'update StudentCompetency',
            'delete StudentCompetency',
            // 'restore StudentCompetency',
            // 'force-delete StudentCompetency',
            'view-any Exam',
            'view Exam',
            'create Exam',
            'update Exam',
            'delete Exam',
            // 'restore Exam',
            // 'force-delete Exam',
        ]);

        $teacherGrade->syncPermissions([
            'view-any Attendance',
            'view Attendance',
            'create Attendance',
            'update Attendance',
            'delete Attendance',
        ]);
    }
}
