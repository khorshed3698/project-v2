<?php

use App\Modules\ProcessPath\Services\BRCommonPoolManager;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    // public function testBasicExample()
    // {
    //     $this->visit('/')
    //          ->see('Laravel 5');
    // }

    public function test_example_2()
    {

        $response = $this->get(route('test'));

        $response->assertResponseStatus(302);
    }

    public function test_example_3()
    {
        $paymentId = 12;
        $response = $this->get(route('WaiverCondition7.waiver-condition-7.afterPayment', ['payment_id' => $paymentId]));
        $response->assertResponseStatus(200);
    }


    public function testBRMachineryDataStore()
    {
        $tracking_no = 'BR-05Jun2023-00001';
        $ref_id = '658';

        $result = BRCommonPoolManager::BRMachineryDataStore($tracking_no, $ref_id);

        $this->assertTrue($result);
        
    }
}
