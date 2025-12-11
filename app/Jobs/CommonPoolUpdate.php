<?php

namespace App\Jobs;

use App\Jobs\Job;
use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CommonPoolUpdate extends Job implements SelfHandling, ShouldQueue
{

    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;


    private $class_name;
    private $method_name;
    private $args;


    /**
     * __construct
     *
     * @param  mixed $class_name
     * @param  mixed $method_name
     * @param  mixed $args
     * @return void
     */
    public function __construct($class_name, $method_name, $args = array())
    {
        $this->class_name = $class_name;
        $this->method_name = $method_name;
        $this->args = $args;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            if (is_callable($this->class_name, $this->method_name)) {
                return call_user_func_array(array($this->class_name, $this->method_name), $this->args);
            }

            throw new Exception('Undefined method - ' . $this->class_name . '::' . $this->method_name);
        } catch (\Exception $exception) {
            // dd($exception->getMessage(), $exception->getFile(), $exception->getCode());
            /**
             * if a job failed to process then it will be attempted again until maximum tries. 
             * The release method accepts one argument: the number of seconds you wish to wait until the job is made available again.
             * 
             * Release for 60 seconds
             */
            $this->release(60);
        }
    }
}
