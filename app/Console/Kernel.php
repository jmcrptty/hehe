protected function schedule(Schedule $schedule)
{
    $schedule->command('peminjaman:check-late')->daily();
} 