<?php
namespace App\Repositories\Eloquents;
use Exception;
use App\Models\ThemeOption;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Exceptions\ExceptionHandler;
use Prettus\Repository\Eloquent\BaseRepository;

class ThemeOptionRepository extends BaseRepository
{
    function model()
    {
       return ThemeOption::class;
    }
    
    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            // Try to get the first theme option record
            $themeOptions = $this->model->first();
            
            // If no record exists, create a new one
            if (!$themeOptions) {
                $themeOptions = new ThemeOption();
                
                // If you need to set any default values for required fields, do it here
                // For example:
                // $themeOptions->status = $request['status'] ?? 1;
                
                // Save the new record first
                $themeOptions->save();
            }
            
            // Now update with the request data
            $themeOptions->update($request);
            
            DB::commit();
            return $themeOptions;
        } catch (Exception $e) {
            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }
}