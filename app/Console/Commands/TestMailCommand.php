<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : The email address to send the test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gửi email thử nghiệm để kiểm tra cấu hình SMTP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('email') ?? config('mail.from.address');
        
        $this->info("Đang gửi email thử nghiệm tới: {$recipient}...");

        try {
            \Illuminate\Support\Facades\Mail::raw('Đây là email thử nghiệm từ FlyVietNam Airlines. Nếu bạn nhận được thư này, cấu hình SMTP của bạn đã hoạt động chính xác!', function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Thử nghiệm cấu hình Mail FlyVietNam Airlines');
            });

            $this->info('Gửi mail thành công! Vui lòng kiểm tra hộp thư của bạn.');
        } catch (\Exception $e) {
            $this->error('Gửi mail thất bại: ' . $e->getMessage());
        }
    }
}
