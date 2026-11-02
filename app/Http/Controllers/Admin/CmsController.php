<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $activeTab = $request->get('tab', 'home');
        return view('admin.cms.index', compact('settings', 'activeTab'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('_tab', 'home');
        $inputs = $request->except(['_token', '_tab']);

        // Handle image uploads
        $imageFields = [
            'collection_1_image',
            'collection_2_image',
            'collection_3_image',
            'plum_b1_image',
            'plum_b2_image_1',
            'plum_b2_image_2',
        ];

        foreach ($imageFields as $field) {
            $fileInput = $field . '_file';
            if ($request->hasFile($fileInput)) {
                $oldVal = Setting::get($field);
                if ($oldVal && !str_starts_with($oldVal, 'http')) {
                    @unlink(public_path('uploads/' . $oldVal));
                }

                $file = $request->file($fileInput);
                $dir = public_path('uploads/cms');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $filename = 'cms_' . $field . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $filename);
                $inputs[$field] = 'cms/' . $filename;
            } elseif ($request->boolean('remove_' . $field)) {
                $oldVal = Setting::get($field);
                if ($oldVal && !str_starts_with($oldVal, 'http')) {
                    @unlink(public_path('uploads/' . $oldVal));
                }
                $inputs[$field] = '';
            }
            unset($inputs[$fileInput], $inputs['remove_' . $field]);
        }

        // Checkbox fields that default to 0 if not present
        if ($tab === 'announcement' || $request->has('_has_announcement')) {
            $inputs['announcement_enabled'] = $request->has('announcement_enabled') ? '1' : '0';
            unset($inputs['_has_announcement']);
        }

        Setting::setMany($inputs);

        return redirect()->route('admin.cms.index', ['tab' => $tab])
            ->with('success', 'Website content updated successfully.');
    }
}
