<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //permission creation

        //application management
        Permission::firstOrCreate(['name' => 'view all applications']);
        Permission::firstOrCreate(['name' => 'view pending applications']);
        Permission::firstOrCreate(['name' => 'view selected applications']);
        Permission::firstOrCreate(['name' => 'view rejected applications']);
        Permission::firstOrCreate(['name' => 'view pending documents']);

        Permission::firstOrCreate(['name' => 'create applications']);
        Permission::firstOrCreate(['name' => 'delete applications']);
        Permission::firstOrCreate(['name' => 'accept applications']);
        Permission::firstOrCreate(['name' => 'reject applications']);

        //interview management
        Permission::firstOrCreate(['name' => 'view interview']);
        Permission::firstOrCreate(['name' => 'set interview result']);
        Permission::firstOrCreate(['name' => 'edit interview']);

        //document management
        Permission::firstOrCreate(['name' => 'view documents']);
        Permission::firstOrCreate(['name' => 'create documents']);
        Permission::firstOrCreate(['name' => 'edit documents']);
        Permission::firstOrCreate(['name' => 'delete documents']);

        //document submission 
        Permission::firstOrCreate(['name' => 'submit document']);
        Permission::firstOrCreate(['name' => 'view submitted document']);
        Permission::firstOrCreate(['name' => 'verify document']);
        Permission::firstOrCreate(['name' => 'reject document']);

        //enrolled student management
        Permission::firstOrCreate(['name' => 'view student']);
        Permission::firstOrCreate(['name' => 'create student']);
        Permission::firstOrCreate(['name' => 'edit student']);
        Permission::firstOrCreate(['name' => 'delete student']);


        //role creation

        $adminRole = Role::firstOrCreate(['name' => 'super admin']);
        $adminRole->givePermissionTo(Permission::all());

        $registrar = Role::firstOrCreate(['name' => 'registrar']);
        $registrar->givePermissionTo([
            'view all applications',
            'accept applications',
            'reject applications',
            'view student',
            'create student',
            'edit student',
            'view submitted document',
            'verify document',
            'reject document',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'view interview',
            'set interview result',
            'edit interview'
        ]);

        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'view selected applications',
            'view interview',
            'set interview result'
        ]);

        $applicant = Role::firstOrCreate(['name' => 'applicant']);
        $applicant->givePermissionTo([
            'create applications',
            'submit document'
        ]);

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->givePermissionTo([
            'create applications',
            'submit document'
        ]);

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => bcrypt('password')
            ]
        );

        $user->assignRole('super admin');
    }
}
