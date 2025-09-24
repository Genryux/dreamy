<?php

namespace App\Console\Commands;

use App\Services\UserInvitationService;
use Illuminate\Console\Command;

class CleanupExpiredInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitations:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired teacher invitations';

    protected $invitationService;

    /**
     * Create a new command instance.
     */
    public function __construct(UserInvitationService $invitationService)
    {
        parent::__construct();
        $this->invitationService = $invitationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up expired teacher invitations...');

        $cleanedCount = $this->invitationService->cleanupExpiredInvitations();

        if ($cleanedCount > 0) {
            $this->info("Cleaned up {$cleanedCount} expired invitations.");
        } else {
            $this->info('No expired invitations found.');
        }

        return Command::SUCCESS;
    }
}