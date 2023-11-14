<?php

namespace App\Console\Commands\V1;


use App\Models\V1\Pqr;
use Illuminate\Console\Command;

class PqrSolvedValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:validate_pqr_solver';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Valida los pqr que estan en estado solucionado para pasarlos a cerrado cuando el cliente no lo cierra';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = now()->subDays(1);

        foreach (Pqr::whereStatus(Pqr::STATUS_RESOLVED)->where("status_resolved_at", "<=", $today)->get() as $pqr) {
            $pqr->update([
                "status" => "closed"
            ]);
        }

    }


}
