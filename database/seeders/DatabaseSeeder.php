<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Collection;
use App\Models\Pen;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Full system access'],
            ['name' => 'User', 'slug' => 'user', 'description' => 'Standard account'],
            ['name' => 'Member', 'slug' => 'member', 'description' => 'Community member'],
            ['name' => 'Subscriber', 'slug' => 'subscriber', 'description' => 'Read-only subscriber'],
        ];

        collect($roles)->each(function (array $role): void {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        });

        $user = User::factory()
            ->withPersonalTeam()
            ->create([
                'name' => 'Demo Maker',
                'email' => 'demo@example.com',
                'role_id' => Role::where('slug', 'admin')->value('id'),
            ]);

        $team = $user->ownedTeams->first();

        $pens = Pen::factory()
            ->count(6)
            ->for($user)
            ->state(fn () => ['team_id' => $team?->id])
            ->create();

        $pens->each(function (Pen $pen) use ($team, $user): void {
            Asset::factory()
                ->count(2)
                ->for($user)
                ->state([
                    'team_id' => $team?->id,
                    'pen_id' => $pen->id,
                ])
                ->create();
        });

        Collection::factory()
            ->count(3)
            ->for($user)
            ->state(fn () => ['team_id' => $team?->id])
            ->create()
            ->each(function (Collection $collection) use ($pens): void {
                $collection->pens()->sync(
                    $pens->random(rand(2, 4))->modelKeys()
                );
            });
    }
}
