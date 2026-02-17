<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class FlashMessage
{
    /**
     * إرسال رسالة نجاح
     */
    public static function success($message)
    {
        Session::flash('success', $message);
    }

    /**
     * إرسال رسالة خطأ
     */
    public static function error($message)
    {
        Session::flash('error', $message);
    }

    /**
     * إرسال رسالة تحذير
     */
    public static function warning($message)
    {
        Session::flash('warning', $message);
    }

    /**
     * إرسال رسالة معلومات
     */
    public static function info($message)
    {
        Session::flash('info', $message);
    }

    /**
     * إرسال رسالة مع إعادة توجيه
     */
    public static function successAndRedirect($message, $route, $parameters = [])
    {
        Session::flash('success', $message);
        return redirect()->route($route, $parameters);
    }

    /**
     * إرسال رسالة خطأ مع إعادة توجيه
     */
    public static function errorAndRedirect($message, $route, $parameters = [])
    {
        Session::flash('error', $message);
        return redirect()->route($route, $parameters);
    }

    /**
     * إرسال رسالة مع العودة للصفحة السابقة
     */
    public static function successAndBack($message)
    {
        Session::flash('success', $message);
        return back();
    }

    /**
     * إرسال رسالة خطأ مع العودة للصفحة السابقة
     */
    public static function errorAndBack($message)
    {
        Session::flash('error', $message);
        return back();
    }
}
