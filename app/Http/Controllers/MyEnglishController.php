<?php

namespace App\Http\Controllers;
use App\Models\English;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MyEnglishController extends Controller
{
    Public function convertFromMinutestoTimeString($number)
    {
        $hours = floor($number / 60);
        $minutes = $number % 60;
        
        if ($hours > 0) {
            $timeString = $hours . ' hour';
            if ($hours > 1) {
                $timeString .= 's';
            }
            if ($minutes > 0) {
                $timeString .= ' and ' . $minutes . ' minute';
                if ($minutes > 1) {
                    $timeString .= 's';
                }
            }
        } else {
            $timeString = $minutes . ' minute';
            if ($minutes > 1) {
                $timeString .= 's';
            }
        }
        return  $timeString;

    }
    public function myenglishdataentry(Request $request, $student){


        $studentname=$student;
        $entries = English::orderBy('created_at', 'desc')
        ->where('student','=',$student)
        ->take(20)
        ->get();
        $todaydate=Carbon::today();
        $todayMinutes =English::where('ondate','=',$todaydate)->where('student','=',$student)->sum('inminutes');
       
        $todayintimestring=$this->convertFromMinutestoTimeString($todayMinutes );
        $distinctDateCount = English::where('student','=',$student)->distinct('ondate')->count('ondate');
        $student_total_in_minutes=English::where('student','=',$student)->sum('inminutes');
       if($distinctDateCount>0)
       {
        $avg_in_minutes=floor($student_total_in_minutes/$distinctDateCount);
        $avg_in_time_string=$this->convertFromMinutestoTimeString( $avg_in_minutes);
       }
       else
       {
        $avg_in_time_string=$todayintimestring;

       }
       $total=$this->convertFromMinutestoTimeString( $student_total_in_minutes);
       $totalperday = English::where('student', '=', $student)
       ->groupBy('ondate')
       ->orderBy('ondate', 'desc')
       ->selectRaw('SUM(inminutes) as total_minutes, ondate')
       ->get();
       
        return view('myenglish.dailypractices',['entries'=>$entries,
        'studentname'=>$studentname,
        'today'=>$todayintimestring,
        'distinctDateCount'=>$distinctDateCount,
        'avg_in_time_string'=>$avg_in_time_string,
        'total'=>$total,
        'totalperday'=>$totalperday,
    ]);
    }

    public function myenglishdatadelete($id)
    {
        $entry= English::find($id);
        $entry->delete();
        return redirect()->back()->with('success','Deleted');

    }

    
    public function myenglishdatastore(Request $request){

        $validator = Validator::make($request->all(), [
            
            'inminutes' => 'required|integer|min:1|max:180',
            'ondate' => [
                'required',
                'date',
                'after_or_equal:' . Carbon::today()->subDays(3)->format('Y-m-d'),
                'before_or_equal:' . Carbon::today()->format('Y-m-d')
            ],

        ]);
        $validator->setAttributeNames([
            'ondate' => 'Date',
            'inminutes' => 'Minutes',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }


        $newentry =new English();
        $newentry->ondate=$request->input('ondate');
        $newentry->inminutes=$request->input('inminutes');
        $newentry->student=$request->input('student');
        $newentry->save();

        return redirect()->back()->with('success','Entered');
    }
}
