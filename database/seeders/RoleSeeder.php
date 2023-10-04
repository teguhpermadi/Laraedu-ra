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
        $student = Role::create(['name' => 'student']);
        
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
        ]);

        $teacher->syncPermissions([
            'view-any Subject',
            'view Subject',
            // 'create Subject',
            // 'update Subject',
            // 'delete Subject',
            // 'restore Subject',
            // 'force-delete Subject',
            'view-any Grade',
            'view Grade',
            // 'create Grade',
            // 'update Grade',
            // 'delete Grade',
            // 'restore Grade',
            // 'force-delete Grade',
            'view-any Competency',
            'view Competency',
            'create Competency',
            'update Competency',
            'delete Competency',
            // 'restore Competency',
            // 'force-delete Competency',
        ]);

        $student->syncPermissions([
            'view-any Subject',
            'view Subject',
            // 'create Subject',
            // 'update Subject',
            // 'delete Subject',
            // 'restore Subject',
            // 'force-delete Subject',
            'view-any Grade',
            'view Grade',
            // 'create Grade',
            // 'update Grade',
            // 'delete Grade',
            // 'restore Grade',
            // 'force-delete Grade',
        ]);
    }
}