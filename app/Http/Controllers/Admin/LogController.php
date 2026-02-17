<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    /**
     * عرض جميع ملفات السجلات
     */
    public function index()
    {
        $logFiles = collect();
        $logPath = storage_path('logs');

        if (File::exists($logPath)) {
            $files = File::files($logPath);
            $logFiles = collect($files)
                ->filter(function ($file) {
                    return $file->getExtension() === 'log';
                })
                ->map(function ($file) {
                    return [
                        'filename' => $file->getFilename(),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime()
                    ];
                })
                ->sortByDesc('modified');
        }

        return view('admin.logs.index', compact('logFiles'));
    }

    /**
     * عرض محتوى ملف سجل معين
     */
    public function show($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (!File::exists($logPath)) {
            abort(404);
        }

        $content = File::get($logPath);
        $lines = collect(explode("\n", $content))->reverse()->take(1000)->reverse();

        return view('admin.logs.show', compact('filename', 'lines'));
    }

    /**
     * حذف ملف سجل
     */
    public function destroy($filename)
    {
        $logPath = storage_path('logs/' . $filename);

        if (File::exists($logPath)) {
            File::delete($logPath);
            return back()->with('success', 'تم حذف ملف السجل بنجاح');
        }

        return back()->with('error', 'الملف غير موجود');
    }
}
