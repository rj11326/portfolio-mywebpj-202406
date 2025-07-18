<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyDashboardController extends Controller
{

    /**
     * 企業ダッシュボード
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 企業ダッシュボードのビュー
     */
    public function index()
    {
        // 企業ユーザーがログインしていることを確認
        $companyId = Auth::guard('company')->user()->company_id;

        // 直近の応募通知
        $latestApplications = Application::with('job')
            ->whereHas('job', function($q) use ($companyId) {
                $q->where('company_id', $companyId)
                  ->where('is_active', 1)
                  ->where('application_deadline', '>=', now());
            })
            ->where('status', 0)
            ->latest()
            ->take(5)
            ->get();


        return view('company.dashboard', [
            'latestApplications' => $latestApplications,
        ]);
    }
}
