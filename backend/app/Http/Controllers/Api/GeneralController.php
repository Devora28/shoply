<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutMilestoneResource;
use App\Http\Resources\AboutPageResource;
use App\Http\Resources\AboutStatResource;
use App\Http\Resources\AboutTeamMemberResource;
use App\Http\Resources\AboutValueResource;
use App\Models\AboutMilestone;
use App\Models\AboutPage;
use App\Models\AboutStat;
use App\Models\AboutTeamMember;
use App\Models\AboutValue;
use App\Models\Setting;
use App\Services\ContactMessageService;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function aboutUs(){
        return response()->json([
            'success' => true,
            'message' => 'About Us',
            'data' => [
                'page' => new AboutPageResource(AboutPage::firstOrFail()),
                'stats' => AboutStatResource::collection(AboutStat::orderBy('sort_order')->get()),
                'values' => AboutValueResource::collection(AboutValue::orderBy('sort_order')->get()),
                'team' => AboutTeamMemberResource::collection(AboutTeamMember::orderBy('sort_order')->get()),
                'milestones' => AboutMilestoneResource::collection(AboutMilestone::orderBy('sort_order')->get()),
            ]
        ]);
    }
    public function contactUs(){
        return response()->json([
            'success' => true,
            'message' => 'Contact Us',
            'data' => [
                'settings' => Setting::firstOrFail(),
            ]
        ]);
    }
    public function contactMessage(Request $request,ContactMessageService $contactMessageService){
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|min:10|max:500',
        ]);
        $contactMessageService->submitMessage($validated);
        return response()->json([
            'success' => true,
            'message' => 'Message Sent',
        ]);
    }
}
