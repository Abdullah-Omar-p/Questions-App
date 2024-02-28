<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       // \App\Models\User::factory(10)->create();

        $superAdminRole = Role::create(['name' => 'super-admin']);
        $userRole = Role::create(['name' => 'user']);
        $adminRole = Role::create(['name' => 'admin']);


        // Category Model
        $categoryPermissions = [
            $createCategory = Permission::create(['name' => 'category-create']),
            $updateCategory = Permission::create(['name' => 'category-update']),
            $deleteCategory = Permission::create(['name' => 'category-delete']),
            $readCategory = Permission::create(['name' => 'category-read']),
        ];

        // Question Model
        $questionPermissions = [
            $createQuestion = Permission::create(['name' => 'create-question']),
            $updateQuestion = Permission::create(['name' => 'update-question']),
            $deleteQuestion = Permission::create(['name' => 'delete-question']),
            $readQuestion = Permission::create(['name' => 'read-question']),
        ];

        // Notification Model
        $notificationPermissions = [
            $createNotification = Permission::create(['name' => 'create-notification']),
            $updateNotification = Permission::create(['name' => 'update-notification']),
            $deleteNotification = Permission::create(['name' => 'delete-notification']),
            $readNotification = Permission::create(['name' => 'read-notification']),
        ];

        // UserInfo Model
        $userInfoPermissions = [
            $createUserInfo = Permission::create(['name' => 'create-user-info']),
            $updateUserInfo = Permission::create(['name' => 'update-user-info']),
            $deleteUserInfo = Permission::create(['name' => 'delete-user-info']),
            $readUserInfo = Permission::create(['name' => 'read-user-info']),
        ];

        // User Model
        $userPermissions = [
            $createUser = Permission::create(['name' => 'create-user']),
            $updateUser = Permission::create(['name' => 'update-user']),
            $deleteUser = Permission::create(['name' => 'delete-user']),
            $readUser = Permission::create(['name' => 'read-user']),
        ];

        // QuestionReads Model
        $questionReadsPermissions =[
            $createQuestionReads = Permission::create(['name' => 'question-reads-create']),
            $updateQuestionReads = Permission::create(['name' => 'question-reads-update']),
            $readQuestionReads = Permission::create(['name' => 'question-reads-read']),
            $deleteQuestionReads = Permission::create(['name' => 'question-reads-delete']),
        ];

        // QuestionComment Model
        $questionCommentPermissions = [
            $createQuestionComments = Permission::create(['name' => 'create-question-comment']),
            $updateQuestionComments = Permission::create(['name' => 'update-question-comment']),
            $deleteQuestionComments = Permission::create(['name' => 'delete-question-comment']),
            $readQuestionComments = Permission::create(['name' => 'read-question-comment']),
        ];

        // QuestionFavourite Model
        $questionFavouritePermissions = [
            $createQuestionFavourite = Permission::create(['name' => 'create-question-favourite']),
            $updateQuestionFavourite = Permission::create(['name' => 'update-question-favourite']),
            $deleteQuestionFavourite = Permission::create(['name' => 'delete-question-favourite']),
            $readQuestionFavourite = Permission::create(['name' => 'read-question-favourite']),
        ];
        // UserNotification Model
        $userNotificationPermissions = [
            $createUserNotification = Permission::create(['name' => 'user-notification-create']),
            $updateUserNotification = Permission::create(['name' => 'user-notification-update']),
            $readUserNotification = Permission::create(['name' => 'user-notification-read']),
            $deleteUserNotification = Permission::create(['name' => 'user-notification-delete']),
        ];


        // Assign Permissions To Super Admin Role
        $superAdminRole->syncPermissions([
            $categoryPermissions,
            $notificationPermissions,
            $questionPermissions,
            $questionReadsPermissions,
            $questionCommentPermissions,
            $questionFavouritePermissions,
            $userInfoPermissions,
            $userPermissions,
            $userNotificationPermissions,
        ]);

        // Assign Permissions To Admin Role
        $adminRole->syncPermissions([
            $categoryPermissions[3], // can only see categories
            $notificationPermissions[0], // only create
            $questionPermissions,
            $questionReadsPermissions[0], // only create
            $questionCommentPermissions,
            $questionFavouritePermissions[0], // only create
            $questionFavouritePermissions[2], // only delete
            $userInfoPermissions,
            $userPermissions,
            $userNotificationPermissions[0], // only create
        ]);

        // Assign Permissions To User Role
        $userRole->syncPermissions([
            $questionPermissions,
            $categoryPermissions[3], // only read
            $notificationPermissions[2], // read
            $notificationPermissions[3], // delete
            $userInfoPermissions,
            $userPermissions,
            $userNotificationPermissions[2], // read
            $userNotificationPermissions[3], // delete
            $questionFavouritePermissions[0], // add
            $questionFavouritePermissions[2], // delete
            $questionCommentPermissions,
            $questionReadsPermissions[0],
        ]);

        $superAdmin = \App\Models\User::updateOrCreate([
             'email' => 'super_admin@test.com',
        ],[
            'name' => 'Test super admin',
            'password'=>bcrypt(123456)
        ]);
        $superAdmin->assignRole('super-admin');



        $admin = \App\Models\User::updateOrCreate([
            'email' => 'admin@test.com',
        ],[
            'name' => 'Test admin',
            'password'=>bcrypt(123456)
        ]);
        $admin->assignRole('admin');



        $user = \App\Models\User::updateOrCreate([
            'email' => 'user@test.com',
        ],[
            'name' => 'Test User',
            'password'=>bcrypt(123456)
        ]);
        $user->assignRole('user');

    }
}
