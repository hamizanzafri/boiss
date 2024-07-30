<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $superAdmin = Role::create(['name' => 'superadmin']);
        $inventoryManager = Role::create(['name' => 'inventory_manager']);
        $eventManager = Role::create(['name' => 'event_manager']);
        $marketingManager = Role::create(['name' => 'marketing_manager']);

        $manageAll = Permission::create(['name' => 'manage all']);
        $manageInventory = Permission::create(['name' => 'manage inventory']);
        $manageEvents = Permission::create(['name' => 'manage events']);
        $manageMarketing = Permission::create(['name' => 'manage marketing']);

        $superAdmin->permissions()->attach([$manageAll->id, $manageInventory->id, $manageEvents->id, $manageMarketing->id]);
        $inventoryManager->permissions()->attach($manageInventory->id);
        $eventManager->permissions()->attach($manageEvents->id);
        $marketingManager->permissions()->attach($manageMarketing->id);
    }
}
