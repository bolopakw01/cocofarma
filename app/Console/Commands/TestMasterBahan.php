<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\BahanBakuController;
use Illuminate\Http\Request;

class TestMasterBahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-master-bahan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test master bahan page functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Master Bahan functionality...');

        // Test controller
        $controller = new BahanBakuController();
        $request = new Request();
        $request->merge(['per_page' => 5]);

        // Simulate master-bahan route by setting route resolver
        $request->setRouteResolver(function () {
            return new class {
                public function getName() {
                    return 'backoffice.master-bahan.index';
                }
            };
        });

        try {
            $response = $controller->index($request);
            $this->info('Controller executed successfully');

            // Check if response is a view
            if (method_exists($response, 'getData')) {
                $data = $response->getData();
                if (isset($data['bahanBakus'])) {
                    $this->info('Data found: ' . $data['bahanBakus']->count() . ' items');
                    $this->info('Total items: ' . $data['bahanBakus']->total());
                } else {
                    $this->error('No bahanBakus data found in view');
                }
            } else {
                $this->error('Response is not a view');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
