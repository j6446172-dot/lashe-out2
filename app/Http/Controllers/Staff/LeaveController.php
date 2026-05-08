<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function request(Request $request)
    {
        try {
            $data = $request->validate([
                'leave_type' => 'required|string',
                'duration_type' => 'required|in:days,hours',
                'start_date' => 'required|date',
                'reason' => 'nullable|string|max:500'
            ]);
            
            $leaveData = [
                'staff_id' => Auth::id(),
                'leave_type' => $request->leave_type,
                'duration_type' => $request->duration_type,
                'start_date' => $request->start_date,
                'reason' => $request->reason,
                'status' => 'pending'
            ];
            
            if ($request->duration_type == 'days') {
                $request->validate([
                    'end_date' => 'required|date|after_or_equal:start_date'
                ]);
                $leaveData['end_date'] = $request->end_date;
                $leaveData['hours'] = null;
                $leaveData['start_time'] = null;
                $leaveData['end_time'] = null;
            } else {
                $request->validate([
                    'hours' => 'required|integer|min:1|max:12',
                    'start_time' => 'required',
                    'end_time' => 'required'
                ]);
                $leaveData['end_date'] = $request->start_date;
                $leaveData['hours'] = $request->hours;
                $leaveData['start_time'] = $request->start_time;
                $leaveData['end_time'] = $request->end_time;
            }
            
            LeaveRequest::create($leaveData);
            
            return response()->json(['success' => true, 'message' => 'تم إرسال طلب الإجازة بنجاح']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function history()
    {
        $leaves = LeaveRequest::where('staff_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($leave) {
                return [
                    'id' => $leave->id,
                    'leave_type' => $leave->leave_type,
                    'display_text' => $leave->display_text,
                    'status_name' => $leave->status_name,
                    'status_color' => $leave->status_color,
                    'admin_notes' => $leave->admin_notes,
                    'created_at' => $leave->created_at
                ];
            });
        
        return response()->json($leaves);
    }
    
    // ✅ أضف int قبل $id
    public function show(int $id)
    {
        $leave = LeaveRequest::where('staff_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        return response()->json([
            'id' => $leave->id,
            'leave_type' => $leave->leave_type,
            'display_text' => $leave->display_text,
            'reason' => $leave->reason,
            'status_name' => $leave->status_name,
            'admin_notes' => $leave->admin_notes,
            'reviewed_at' => $leave->reviewed_at
        ]);
    }
}