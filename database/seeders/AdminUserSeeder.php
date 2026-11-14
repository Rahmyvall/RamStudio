<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
  /**
   * Create the default administrator account.
   *
   * The credentials can be overridden through the environment so the
   * production password never has to live in source control.
   */
  public function run(): void
  {
    User::query()->updateOrCreate(
      ['email' => env('ADMIN_EMAIL', 'admin@ramstudio.test')],
      [
        'name' => env('ADMIN_NAME', 'RAMStudio Administrator'),
        'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
        'role' => User::ROLE_SUPERADMIN,
      ],
    );
  }
}
