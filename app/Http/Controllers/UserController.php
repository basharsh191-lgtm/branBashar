<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUSerRequests;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(RegisterUSerRequests $request)
    {

            $validated=$request->validated();
            $user=User::create($validated);
            $role=$user->assignRole('patient');

            return response()->json(
                ['massage'=>'User Registered Succssfully ',
                'User'=>$user,
                ], 201);
    }
      public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);
        if(!Auth::attempt($request->only('email','password')))
        return response()->json(['message'=>'Invalid email or password'], 401);
        $user=User::where('email',$request->email)->firstOrFail();

        // if (!$user->hasRole('patient')) {
        //     // إذا لم يكن مريضاً (مثلاً طبيب يحاول دخول تطبيق المرضى) نرفض دخوله
        //     Auth::logout(); // اختيارياً: إنهاء الجلسة
        //     return response()->json(['message' => 'عذراً، هذا التطبيق مخصص للمرضى فقط.'], 403);
        // }
        $token=$user->createToken('auth_Token')->plainTextToken;
        return response()->json(
            ['massage'=>'User log in Succssfully ',
                    'User'=>$user,
                    'Token'=>$token
                    ], 201);
    }
public function book(Request $request)
{
    $request->validate([
        // تأكد أن الـ doctor_id هو مستخدم لديه دور "doctor" فعلياً (اختياري لزيادة الأمان)
        'doctor_id' => 'required|exists:users,id',
        'appointment_date' => 'required|string', // يفضل التأكد أنه تاريخ مستقبلي
    ]);

    $appointment = Appointment::create([
        'patient_id'       => Auth::id(),
        'doctor_id'        => $request->doctor_id,
        'appointment_date' => $request->appointment_date, // تم تعديل الاسم هنا ليطابق الموديل
        'status'           => 'pending'
    ]);

    return response()->json([
        'message' => 'تم طلب الموعد بنجاح، انتظر تأكيد العيادة.',
        'data'    => $appointment->load('doctor:id,name') // تحميل بيانات الطبيب المختصرة للرد
    ], 201);
}
public function delete(User $user, Appointment $appointment)
{
    // يسمح بالحذف إذا كان المستخدم هو صاحب الموعد "أو" يمتلك دور أدمن
    return $user->id === $appointment->patient_id || $user->hasRole('admin');
}

}
