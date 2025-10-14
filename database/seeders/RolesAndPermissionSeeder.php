<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\PermissionCategory;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //permission creation

        // Academic Term Management
        $this->createPermissionWithCategory('create new term', 'Academic Term Management', 'Create new academic terms and semesters');
        $this->createPermissionWithCategory('edit term', 'Academic Term Management', 'Edit existing academic terms');
        $this->createPermissionWithCategory('add enrollment period', 'Academic Term Management', 'Add enrollment periods to academic terms');
        $this->createPermissionWithCategory('pause enrollment period', 'Academic Term Management', 'Pause active enrollment periods');
        $this->createPermissionWithCategory('end enrollment period', 'Academic Term Management', 'End enrollment periods');

        // Applicant/Application Management
        $this->createPermissionWithCategory('submit form', 'Applicant/Application Management', 'Submit application forms');
        $this->createPermissionWithCategory('submit document', 'Applicant/Application Management', 'Submit required document');
        $this->createPermissionWithCategory('view applications', 'Applicant/Application Management', 'View all application');
        $this->createPermissionWithCategory('view pending form', 'Applicant/Application Management', 'View pending application');
        $this->createPermissionWithCategory('view accepted form', 'Applicant/Application Management', 'View accepted application');
        $this->createPermissionWithCategory('view pending-document form', 'Applicant/Application Management', 'View pending-document application');
        $this->createPermissionWithCategory('view rejected form', 'Applicant/Application Management', 'View rejected application');

        $this->createPermissionWithCategory('accept and schedule', 'Applicant/Application Management', 'Accept applications and schedule interviews');
        $this->createPermissionWithCategory('reject application', 'Applicant/Application Management', 'Reject applications');
        $this->createPermissionWithCategory('record result', 'Applicant/Application Management', 'Record admission exam results (passed or failed)');
        $this->createPermissionWithCategory('enroll student', 'Applicant/Application Management', 'Promote applicants to officially enrolled students');

        // Document Management
        $this->createPermissionWithCategory('create documents', 'Document Management', 'Create new documents');
        $this->createPermissionWithCategory('edit documents', 'Document Management', 'Edit existing documents');
        $this->createPermissionWithCategory('view documents', 'Document Management', 'View all documents');
        $this->createPermissionWithCategory('delete documents', 'Document Management', 'Delete documents');
        $this->createPermissionWithCategory('manage submitted documents', 'Document Management', 'Reject submitted documents');

        // Student Management
        $this->createPermissionWithCategory('view student', 'Student Management', 'View student information and records');
        $this->createPermissionWithCategory('import student', 'Student Management', 'Import students from external sources');
        $this->createPermissionWithCategory('withdraw enrollment', 'Student Management', 'Withdraw student enrollment');
        $this->createPermissionWithCategory('generate document', 'Student Management', 'Generate documents for students');
        $this->createPermissionWithCategory('edit student', 'Student Management', 'Edit student information');

        // Payment Management
        $this->createPermissionWithCategory('view school fees', 'Payment Management', 'View school fees information');
        $this->createPermissionWithCategory('create school fees', 'Payment Management', 'Create and manage school fees');
        $this->createPermissionWithCategory('delete school fees', 'Payment Management', 'Delete school fees');
        $this->createPermissionWithCategory('update school fees', 'Payment Management', 'Update school fees');

        // Invoice
        $this->createPermissionWithCategory('create invoice', 'Payment Management', 'Create invoices for students');
        $this->createPermissionWithCategory('view invoice records', 'Payment Management', 'View student invoices');
        $this->createPermissionWithCategory('view invoice', 'Payment Management', 'View student invoice');
        $this->createPermissionWithCategory('remove invoice item', 'Payment Management', 'Remove invoice items');

        // Invoice history
        $this->createPermissionWithCategory('view payment history', 'Payment Management', 'View payment history and records');
        $this->createPermissionWithCategory('record payment', 'Payment Management', 'Record student payments');

        // Track Management
        $this->createPermissionWithCategory('view track', 'Track Management', 'View all track');
        $this->createPermissionWithCategory('create track', 'Track Management', 'Create new track');
        $this->createPermissionWithCategory('edit track', 'Track Management', 'Edit existing track');
        $this->createPermissionWithCategory('delete track', 'Track Management', 'Delete track');

        // Strand Management
        $this->createPermissionWithCategory('view strand', 'Strand Management', 'View all strands');
        $this->createPermissionWithCategory('create strand', 'Strand Management', 'Create new strands');
        $this->createPermissionWithCategory('edit strand', 'Strand Management', 'Edit existing strands');
        $this->createPermissionWithCategory('delete strand', 'Strand Management', 'Delete strands');

        // Section Management
        $this->createPermissionWithCategory('view sections', 'Section Management', 'View all sections');
        $this->createPermissionWithCategory('view section', 'Section Management', 'View specific section');
        $this->createPermissionWithCategory('create section', 'Section Management', 'Create new sections');
        $this->createPermissionWithCategory('edit section', 'Section Management', 'Edit existing sections');
        $this->createPermissionWithCategory('delete section', 'Section Management', 'Delete sections');
        $this->createPermissionWithCategory('assign subject to a section', 'Section Management', 'Assign subjects to sections');
        $this->createPermissionWithCategory('edit subject assigned to a section', 'Section Management', 'Edit subjects assigned to sections');
        $this->createPermissionWithCategory('remove assigned subject to a section', 'Section Management', 'Remove subjects from sections');
        $this->createPermissionWithCategory('add student to a section', 'Section Management', 'Add students to sections');
        $this->createPermissionWithCategory('remove student to a section', 'Section Management', 'Remove students from sections');

        // Subject Management
        $this->createPermissionWithCategory('view subjects', 'Subject Management', 'View all subjects');
        $this->createPermissionWithCategory('create subject', 'Subject Management', 'Create new subjects');
        $this->createPermissionWithCategory('edit subject', 'Subject Management', 'Edit existing subjects');
        $this->createPermissionWithCategory('delete subject', 'Subject Management', 'Delete subjects');

        // User Management
        $this->createPermissionWithCategory('view all users', 'User Management', 'View all system users');
        $this->createPermissionWithCategory('create user', 'User Management', 'Create new users');
        $this->createPermissionWithCategory('edit user', 'User Management', 'Edit user information');
        $this->createPermissionWithCategory('delete user', 'User Management', 'Delete users');

        // Roles and Permission Management
        $this->createPermissionWithCategory('view roles', 'Roles and Permission Management', 'View all roles');
        $this->createPermissionWithCategory('create roles', 'Roles and Permission Management', 'Create new roles');
        $this->createPermissionWithCategory('edit roles', 'Roles and Permission Management', 'Edit existing roles');
        $this->createPermissionWithCategory('delete roles', 'Roles and Permission Management', 'Delete roles');

        // User Invitation
        $this->createPermissionWithCategory('invite user', 'User Invitation', 'Send invitations to new users');

        // System Settings Management
        $this->createPermissionWithCategory('view system settings', 'System Settings Management', 'View system settings (school name, address, contact, etc.)');
        $this->createPermissionWithCategory('edit system settings', 'System Settings Management', 'Edit system settings');

        // Dashboard viewing
        $this->createPermissionWithCategory('view enrollment dashboard', 'Page Viewing', 'View enrollment dashboard');
        $this->createPermissionWithCategory('view head teacher dashboard', 'Page Viewing', 'View head teacher dashboard');
        $this->createPermissionWithCategory('view teachers dashboard', 'Page Viewing', 'View teachers dashboard');

        // Site Management
        // Note: Contents are yet to be completed as per user's list


        //role creation

        $adminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole->givePermissionTo(Permission::all());

        $registrar = Role::firstOrCreate(['name' => 'registrar']);

        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $teacher->givePermissionTo([
            'view sections',
            'view subjects',
            'view student'
        ]);

        $headTeacher = Role::firstOrCreate(['name' => 'head_teacher']);
        $headTeacher->givePermissionTo([
            'view sections',
            'create section',
            'edit section',
            'delete section',
            'assign subject to a section',
            'edit subject assigned to a section',
            'remove assigned subject to a section',
            'add student to a section',
            'remove student to a section',
            'view subjects',
            'view student'
        ]);

        $applicant = Role::firstOrCreate(['name' => 'applicant']);
        $applicant->givePermissionTo([
            'submit form',
            'submit document'
        ]);

        $student = Role::firstOrCreate(['name' => 'student']);
        $student->givePermissionTo([
            'view student'
        ]);

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => bcrypt('password')
            ]
        );

        $user->assignRole('super_admin');
    }

    /**
     * Helper method to create permission with category
     */
    private function createPermissionWithCategory($permissionName, $categoryName, $description = null)
    {
        // Create or get the permission
        $permission = Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web'
        ]);

        // Create or get the category record
        PermissionCategory::firstOrCreate([
            'permission_id' => $permission->id,
            'category_name' => $categoryName,
        ], [
            'description' => $description
        ]);

        return $permission;
    }
}
