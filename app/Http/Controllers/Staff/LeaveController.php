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
            
            // ✅  فحص الرصيد
            $staff = Auth::user();
            $totalDays = 1;
            
            if ($request->duration_type == 'days' && $request->end_date) {
                $start = \Carbon\Carbon::parse($request->start_date);
                $end = \Carbon\Carbon::parse($request->end_date);
                $totalDays = (int)($start->diffInDays($end)) + 1;
            }
            
            if ($request->leave_type == 'سنوية' && ($staff->remaining_annual_leave ?? 0) < $totalDays) {
                return response()->json(['success' => false, 'message' => 'رصيد إجازات سنوية غير كاف']);
            }
            if ($request->leave_type == 'مرضية' && ($staff->remaining_sick_leave ?? 0) < $totalDays) {
                return response()->json(['success' => false, 'message' => 'رصيد إجازات مرضية غير كاف']);
            }
            if ($request->leave_type == 'طارئة' && ($staff->remaining_emergency_leave ?? 0) < $totalDays) {
                return response()->json(['success' => false, 'message' => 'رصيد إجازات طارئة غير كاف']);
            }
            // ✅ 
            
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