<?php

namespace App\Commands;

use App\NightLands;
use App\User;

class ResearchConscription extends Command
{
    protected $signature = 'conscription:research';

    protected $description = 'Research the next level of conscription.';

    public function handle(NightLands $nightLands): void
    {
        $users = $this->selectUsers();

        $users->each(function (User $user) use ($nightLands) {
            $response = $nightLands->conscription($user->getLastIssuedToken())->research();
            $user->update([
                'conscription_upgrade_finished_at' => $response->upgradeFinishedAt(),
            ]);

            $this->info("Upgrading to level {$response->nextLevel()}.");
            $this->info("Finished at: {$response->upgradeFinishedAt()->toDateTimeString()}.");
        });
    }
}