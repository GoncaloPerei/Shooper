<?php

namespace App\Classes;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Log;

class AssignVariablesService
{
    protected $user;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function assignStatus($data)
    {
        try {
            if ($data->status_id === null) {
                $data->status_id = 2;
            }
        } catch (QueryException $e) {
            Log::error('Assign status failed: ' . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            Log::error('Assign status failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function assignCreatedBy($data)
    {
        try {
            if (!$data->isDirty('created_by')) {
                $data->created_by = $this->user->id;
            }
        } catch (QueryException $e) {
            Log::error('Assign created by failed: ' . $e);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Assign created by failed: ' . $e);
            throw $e;
        }
    }

    public function assignStore($storeModel, $data)
    {
        try {
            $store = $storeModel::where('website_url', '!=', '')
                ->where(function ($query) use ($data) {
                    $query->whereRaw('? LIKE CONCAT("%", website_url, "%")', [$data->url]);
                })
                ->first();

            if (!$store || $store->status_id != 1) {
                throw ValidationException::withMessages(['Store not found']);
            }

            $data->store_id = $store->id;
        } catch (QueryException $e) {
            Log::error('Assign store failed: ' . $e);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Assign store failed: ' . $e);
            throw $e;
        }
    }
}
