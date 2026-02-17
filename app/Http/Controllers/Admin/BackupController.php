<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    /**
     * عرض جميع النسخ الاحتياطية
     */
    public function index()
    {
        $backups = collect();

        if (Storage::disk('local')->exists('backups')) {
            $files = Storage::disk('local')->files('backups');
            $backups = collect($files)->map(function ($file) {
                return [
                    'filename' => basename($file),
                    'size' => Storage::disk('local')->size($file),
                    'created_at' => Storage::disk('local')->lastModified($file)
                ];
            })->sortByDesc('created_at');
        }

        return view('admin.backups.index', compact('backups'));
    }

    /**
     * إنشاء نسخة احتياطية جديدة
     */
    public function create()
    {
        try {
            Artisan::call('backup:run');
            return back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }

    /**
     * تحميل نسخة احتياطية
     */
    public function download($filename)
    {
        $path = 'backups/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * حذف نسخة احتياطية
     */
    public function destroy($filename)
    {
        $path = 'backups/' . $filename;

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return back()->with('success', 'تم حذف النسخة الاحتياطية بنجاح');
        }

        return back()->with('error', 'الملف غير موجود');
    }
}
