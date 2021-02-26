<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Models\Testcase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;


class ProcessSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $sub_id;
    protected $problem_id;

    /**
     * Create a new job instance.
     *
     * @param $submission_id
     * @param $problem_id
     */
    public function __construct($submission_id, $problem_id)
    {
        //
        $this->sub_id = $submission_id;
        $this->problem_id = $problem_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        // get the code of the submission_id
        // get the problem limitation

        // compile the code
        // run the code
        // check time limit
        // check memory limit
        // check all the input output
        // update the submission status

        $value['cpu'] = 1.03;
        $value['memory'] = 1800;
        $value['verdict'] = 1;
        // get the submission details
        $sub_details = Submission::get_submission_details($this->sub_id);
        // get the testcase
        $testcase = Testcase::getAllTestcase($this->problem_id);
        Submission::update_submission_status($this->sub_id, $value);
        /*
        $response = Http::post('https://api.jdoodle.com/v1/execute', [
            'clientId' => '6f9e183fcfe6b78aa3981115e491432b',
            'clientSecret' => '6ba69d7123f3c3df6fef4d4798a6a79d2ecf851d00ba077e1583aeff3116139',
            'script' => $sub_details->code,
            'stdin' => $testcase[0]->input,
            'language' => 'cpp',
            'versionIndex' => '0',
        ]);
        */
        /*
        if($response->successful()){
            // api call success
            $result = json_decode($response->body());

            // check the compilation status

            if(isset($result->error)){
                $value['verdict'] = 6;
            }else{
                // check time limit
                if(abs(floatval($sub_details->cpu)- floatval($result->cupTime)) < 0.001 ){
                    $value['cpu'] = $result->cpuTime;
                    $value['verdict'] = 3;
                }

                else if($result->memory > $sub_details->memory){
                    // check memory limit
                    $value['memory'] = $result->memory;
                    $value['verdict'] = 4;
                }
                else if($result->output != $testcase[0]->output){
                    // check wrong answer
                    $value['cpu'] = $result->cpuTime;
                    $value['memory'] = $result->memory;
                    $value['verdict'] = 2;
                }
                else{
                    $value['cpu'] = $result->cpuTime;
                    $value['memory'] = $result->memory;
                    $value['verdict'] = 1;
                }
            //}


        }
        */
    }
}
