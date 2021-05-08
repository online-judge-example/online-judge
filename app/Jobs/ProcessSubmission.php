<?php

namespace App\Jobs;

use App\Models\Submission;
use App\Models\Testcase;
use App\Models\Problem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


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

        $value['cpu'] = 0;
        $value['memory'] = 0;
        $value['verdict'] = 1;
        // get the submission details
        $sub_details = Submission::get_submission_details($this->sub_id);
        // get the testcase
        $testcase = Testcase::getAllTestcase($this->problem_id);
        // get the problem limits
        $problem = Problem::get_problem_limit($this->problem_id);

        $Acceptflag = false;   //
        $verdict = 0;
        $successCount = 0;  // success api call count

        foreach ($testcase as $tc){
            $response = Http::post('https://api.jdoodle.com/v1/execute', [
                'clientId' => '6f9e183fcfe6b78aa3981115e491432b',
                'clientSecret' => '6ba69d7123f3c3df6fef4d4798a6a79d2ecf851d00ba077e1583aeff3116139',
                'script' => $sub_details->code,
                'stdin' => $tc->input,
                'language' => $sub_details->language_id,
                'versionIndex' => config('app.language_index')[$sub_details->language_id],
            ]);

            if ($response->successful()) {
                // api call success
                $successCount++;

                $result = json_decode($response->body());

                //Storage::put('result.txt', var_dump($result));    // check the response status
                // check the compilation status
                //var_dump($result);
                //var_dump($testcase[0]->output);

                if (property_exists($result, "error")) {
                    $value['verdict'] = 6;
                    $Acceptflag = false;
                } else {

                    $server_tl = (float)$result->cpuTime;
                    $server_ml = (int)$result->memory;
                    $server_output = $output = preg_replace("/\r/", "", $result->output);
                    // get problem limit
                    $tl = (float)$problem->time_limit;
                    $ml = (int)$problem->memory_limit;
                    $epsilon = 0.0001;

                    //Storage::put('result1.txt', $server_output);
                    //Storage::put('result2.txt', $testcase[0]->output);
                    if ($server_ml == 0) {
                        // compilation error
                        //append(join) the error report with user code
                        $update_code = $server_output . " " . $sub_details->code;
                        Submission::append_user_code($this->sub_id, $update_code);
                        // update the verdict
                        $value['verdict'] = 5;
                        $value['cpu'] = $server_tl;
                        $value['memory'] = $server_ml;
                        $Acceptflag = false;
                    } else if (($tl + $epsilon) < $server_tl) {
                        // time limit
                        $value['verdict'] = 3;
                        $value['cpu'] = $tl;
                        $value['memory'] = max($value['memory'], $server_ml);
                        $Acceptflag = false;
                    } else if ($server_ml > $ml) {
                        // memory limit
                        $value['verdict'] = 4;
                        $value['cpu'] = max($value['cpu'], $server_tl);
                        $value['memory'] = $ml;
                        $Acceptflag = false;
                    } else if (strcmp($server_output, $tc->output) != 0) {
                        // wrong answer
                        //var_dump(strcmp($server_output, trim($testcase[0]->output)));
                        $value['verdict'] = 2;
                        $value['cpu'] = max($value['cpu'], $server_tl);
                        $value['memory'] = max($value['memory'], $server_ml);
                        $Acceptflag = false;
                    } else {
                        $value['verdict'] = 1;
                        $value['cpu'] = max($value['cpu'], $server_tl);
                        $value['memory'] = max($value['memory'], $server_ml);
                        $Acceptflag = true;
                    }

                }  // end else

                if(!$Acceptflag){
                    break;
                }

            } // end response success
        }  // end foreach
        Submission::update_submission_status($this->sub_id, $value);
        // if $AcceptFlag is true and $successCount is less the testcase then send back to the queue

    }
}
