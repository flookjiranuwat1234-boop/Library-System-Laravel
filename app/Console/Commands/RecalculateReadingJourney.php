<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ReadingJourneyService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('library:recalculate-journey {--user= : User ID to recalculate}')]
#[Description('Recalculate reading journey stats and badges for library users')]
class RecalculateReadingJourney extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ReadingJourneyService $journeyService): int
    {
        $userId = $this->option('user');

        if ($userId !== null) {
            $user = User::query()->find($userId);

            if (! $user) {
                $this->error("ไม่พบผู้ใช้งานรหัส #{$userId}");

                return self::FAILURE;
            }

            $journeyService->recalculateFor($user);
            $journeyService->syncBadgesFor($user);
            $this->info("คำนวณสถิติและตราเกียรติยศสำหรับผู้ใช้ {$user->name} (#{user->id}) เรียบร้อยแล้ว");

            return self::SUCCESS;
        }

        $processedCount = 0;

        User::query()
            ->where('role', 'user')
            ->chunkById(100, function ($users) use ($journeyService, &$processedCount): void {
                /** @var User $user */
                foreach ($users as $user) {
                    $journeyService->recalculateFor($user);
                    $journeyService->syncBadgesFor($user);
                    $processedCount++;
                }
            });

        $this->info("คำนวณสถิติ Reading Journey ให้สมาชิกทั้งหมด {$processedCount} รายการเรียบร้อยแล้ว");

        return self::SUCCESS;
    }
}
