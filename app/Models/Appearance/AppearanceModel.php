<?php

namespace App\Models\Appearance;

use CodeIgniter\Model;
use Config\Database;
use Carbon\Carbon;

class AppearanceModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getTotalAppearings($cause_list_from_date, $cause_list_to_date = '')
    {
        $db1 = Database::connect('e_services');
        $builder = $db1->table('appearing_in_diary');
        $builder->where('is_active', 1)
            ->where('is_submitted', 1);
        if (!empty($cause_list_to_date)) {
            $builder->where("list_date BETWEEN " . $db1->escape($cause_list_from_date) . " AND " . $db1->escape($cause_list_to_date));
        } else {
            $builder->where('list_date', $cause_list_from_date);
        }
        return $builder->countAllResults();
    }

    public static function currentWeekLastWeekSubmissions()
    {
        $db1 = Database::connect('e_services');
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
        ];
        $weeklySubmissions = [];
        foreach ($days as $key => $value) {
            if ($key == 0) {
                $current_week_date = Carbon::now()->startOfWeek()->toDateString();
                $last_week_date = Carbon::now()->subWeek()->startOfWeek()->toDateString();
            } else {
                $current_week_date = Carbon::now()->startOfWeek()->addDays($key)->toDateString();
                $last_week_date = Carbon::now()->subWeek()->startOfWeek()->addDays($key)->toDateString();
            }
            $current_week_daywise_total_records = $db1->table('appearing_in_diary')
                ->where('is_active', 1)
                ->where('is_submitted', 1)
                ->where('list_date', $current_week_date)
                ->countAllResults();
            $last_week_daywise_total_records = $db1->table('appearing_in_diary')
                ->where('is_active', 1)
                ->where('is_submitted', 1)
                ->where('list_date', $last_week_date)
                ->countAllResults();
            $weeklySubmissions[$value] = [$current_week_daywise_total_records, $last_week_daywise_total_records];
        }
        return $weeklySubmissions;
    }

}