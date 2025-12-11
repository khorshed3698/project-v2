<?php

namespace App\Console\Commands;

use App\Libraries\CommonFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\ProcessPath\Models\ProcessDoc;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Users\Models\Users;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\File;

class ShadowFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shadow:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
     * @return mixed
     */
    public function handle()
    {
        try {

            $shadowFileInfo = \App\Modules\Apps\Models\ShadowFile::where('is_generate', 0)->get();
            foreach ($shadowFileInfo as $data) {
                $shadowFileId = $data->id;
                $jsonData = json_decode($data->shadow_file_perimeter);
                $process_id = $jsonData->process_id;
                $module_name = str_replace("", '', $jsonData->module_name);
                $process_type_id = $jsonData->process_type_id;
                $app_id = $jsonData->app_id;

                //dynamic module wise application
                $NameSpacedModel = "/App/Modules/$module_name/Models/" . $module_name;
                $modelPath = str_replace('/', '\\', $NameSpacedModel);

                $getAppData = $modelPath::where('id', $app_id)->first();
                $userName = Users::where('id', $getAppData->created_by)->first(['user_first_name', 'user_middle_name', 'user_last_name']);
                if($userName == ''){
                    $UserFullName = "Test User";
                }else{
                    $UserFullName = $userName->user_first_name . ' ' . $userName->user_middle_name . ' ' . $userName->user_last_name;
                }
                $getProcessData = ProcessList::where('id', $process_id)->first();
                $tracking_no = $getProcessData->tracking_no;
                $processName = ProcessType::where('id', $process_type_id)->first()->name;
                $processDocument = ProcessDoc::where('process_type_id', $process_type_id)
                    ->where('ref_id', $process_id)->get();

                $getFile = AppDocuments::where('ref_id', $app_id)
                    ->where('process_type_id', $process_type_id)->get();

                $process_history = DB::select(DB::raw("select  `process_list_hist`.`desk_id`,`as`.`status_name`,
                                `process_list_hist`.`process_id`,                           
                                if(`process_list_hist`.`desk_id`=0,\"-\",`ud`.`desk_name`) `deskname`,
                                `users`.`user_full_name`, 
                                `process_list_hist`.`updated_by`, 
                                `process_list_hist`.`status_id`, 
                                `process_list_hist`.`process_desc`, 
                                `process_list_hist`.`process_id`, 
                                `process_list_hist`.`updated_at`,
                                 group_concat(`pd`.`file`) as files
                                
                    
                                from `process_list_hist`
                                left join `process_documents` as `pd` on `process_list_hist`.`id` = `pd`.`process_hist_id`
                                left join `user_desk` as `ud` on `process_list_hist`.`desk_id` = `ud`.`id`
                                left join `users` on `process_list_hist`.`updated_by` = `users`.`id`     
                                
                                left join `process_status` as `as` on `process_list_hist`.`status_id` = `as`.`id`
                                and `process_list_hist`.`process_type` = `as`.`process_type_id`
                                where `process_list_hist`.`process_id`  = '$process_id'
                                and `process_list_hist`.`process_type` = '$process_type_id' 
                               
                                and `process_list_hist`.`status_id` != -1
                    group by `process_list_hist`.`process_id`,`process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, process_list_hist.updated_at
                    order by process_list_hist.updated_at desc

                    "));
                DB::beginTransaction();

                $processPath = "Process Path:" . json_encode($process_history);
                $getAppData = "Application Data:" . json_encode($getAppData);
                $getProcessData = "Process List Data:" . json_encode($getProcessData);
                $path = env("server_path") . 'AppInfo_' . $tracking_no;
                $mdFile = env("server_path") . 'readme' . $app_id . '.md';
                File::put($path, $getAppData . "\n \n" . $getProcessData . "\n \n" . $processPath);
                $getDate = date('d/m/Y h:i:s');
                File::put($mdFile, "## shadow file generated by $UserFullName  for the application of $processName. 
                Tracking Number: $tracking_no Generate Date: $getDate
                ");

                $appsFiles = [];
                foreach ($getFile as $key => $f) {
                    if ($f->doc_file_path != '') {
                        $appsFiles[] = env("server_path") . "uploads/" . $f->doc_file_path;
                    }
                }

                if (!empty($processDocument)) {
                    foreach ($processDocument as $doc) {
                        if ($doc->file != '') {
                            $processDoc = env("server_path") . $doc->file;
                            array_push($appsFiles, $processDoc);
                        }
                    }
                }
                array_push($appsFiles, $path, $mdFile);
                $fileDir = 'shadow-file/' . $module_name . '_' . $tracking_no . '_' . uniqid() . '.zip';
                $archiveFile = public_path($fileDir);
                $archive = new ZipArchive();

                if ($archive->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                    foreach ($appsFiles as $file) {
                        if ($archive->addFile($file, basename($file))) {
                            continue;
                        } else {
                            DB::rollback();
                            throw new Exception("file `{$file}` could not be added to the zip file: " . $archive->getStatusString());
                        }
                    }

                    if ($archive->close()) {
                        \App\Modules\Apps\Models\ShadowFile::where('id', $shadowFileId)->update([
                            'file_path' => $fileDir,
                            'process_type_id' => $process_type_id,
                            'ref_id' => $app_id,
                            'is_generate' => 1,
                        ]);

                        File::delete($path);
                        File::delete($mdFile);
                        DB::commit();
                        return response()->json(['responseCode' => 1, 'status' => 'success']);
                    }

                } else {
                    DB::rollback();
                    throw new Exception("could not close zip file: " . $archive->getStatusString());
                }
            }

        } catch (Exception $e) {
            DB::rollback();
            dd(CommonFunction::showErrorPublic($e->getMessage()));
//            \App\Modules\Apps\Models\ShadowFile::where('id', $shadowFileId)->update([
//                'error_messages' => CommonFunction::showErrorPublic($e->getLine()),
//                'process_type_id' => $process_type_id,
//                'ref_id' => $app_id
//            ]);
        }
    }
}
