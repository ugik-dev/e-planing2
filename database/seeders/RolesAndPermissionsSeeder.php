<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesAndPermissions = [
            'PPK' => [
                'view sbm&sbi',
                'view pelaporan',
                'view pembayaran',
                'view penganggaran',
                'approval pembayaran',
                'approval penganggaran',
                'rekap penganggaran',
            ],
            'SPI' => [
                'view sbmsbi',
                'view pelaporan',
                'view pembayaran',
                'view penganggaran',
                'view perencanaan',
                'approval pembayaran',
                'approval penganggaran',
                'approval and comment perencanaan',
                'rekap penganggaran',
            ],
            'KEPALA SPI' => [
                'view sbmsbi',
                'view pelaporan',
                'view pembayaran',
                'view penganggaran',
                'view perencanaan',
                'approval pembayaran',
                'approval penganggaran',
                'approval and comment perencanaan',
                'rekap penganggaran',
            ],
            'STAF PPK' => [
                'view sbmsbi',
                'view pelaporan',
                'view pembayaran',
                'approval pelaporan',
                'approval pembayaran',
            ],
            'SUPER ADMIN PERENCANAAN' => [],
            'ADMIN FAKULTAS/UNIT' => [
                'view sbmsbi',
                'view pelaporan',
                'view pembayaran',
                'input pembayaran',
                'edit pembayaran',
                'view penganggaran',
                'input penganggaran',
                'edit penganggaran',
                'rekap penganggaran'
            ],
            'KEPALA UNIT KERJA' => [
                'view sbmsbi',
                'view pelaporan',
                'view pembayaran',
                'input pembayaran',
                'edit pembayaran',
                'view penganggaran',
                'input penganggaran',
                'approval penganggaran',
                'rekap penganggaran'
            ],
            'KPA (REKTOR)' => [
                'view pelaporan',
                'view perencanaan',
                'view penganggaran',
                'view pembayaran',
                'approval penganggaran',
                'rekap penganggaran'
            ],
            'BENDAHARA' => [
                'view sbmsbi',
                'view pembayaran',
                'approval bendahara',
                'approval pembayaran',
            ],
            'Pelaksana Kegiatan' => [
                'view sbmsbi',
                'view pembayaran',
                'input pembayaran',
                'edit pembayaran',
            ]
        ];
        foreach ($rolesAndPermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            foreach ($permissions as $permission) {
                if ($role->name !== 'SUPER ADMIN PERENCANAAN') {
                    $permission =  Permission::firstOrCreate(['name' => $permission]);
                    $role->givePermissionTo($permission);
                }
            }
        }
        Role::where('name', 'SUPER ADMIN PERENCANAAN')->first()->givePermissionTo(Permission::all());

        // sintak memperbaharui
        // php artisan db:seed --class=RolesAndPermissionsSeeder
        // Role::where('name', 'ADMIN FAKULTAS/UNIT')->first()->givePermissionTo('view SBM&SBI');
    }
}
