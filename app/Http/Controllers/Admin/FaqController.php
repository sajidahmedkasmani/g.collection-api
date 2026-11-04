<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $faqs = $query->orderBy('sort_order')->orderBy('id')->get();
        $categories = Faq::distinct()->pluck('category')->filter()->values();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = Faq::distinct()->pluck('category')->filter()->values();
        return view('admin.faqs.form', [
            'faq' => new Faq(['sort_order' => Faq::max('sort_order') + 1, 'is_active' => true]),
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = $request->boolean('is_active');

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ added successfully.');
    }

    public function edit(Faq $faq)
    {
        $categories = Faq::distinct()->pluck('category')->filter()->values();
        return view('admin.faqs.form', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active'] = $request->boolean('is_active');

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function toggleActive(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);
        return back()->with('success', 'FAQ status updated.');
    }


    public function reorder(Request $request)
    {
        abort_unless($request->user()->can('faqs.edit'), 403);

        $ids = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:faqs,id'],
        ])['ids'];

        DB::transaction(function () use ($ids) {
            // reuse the existing sort_order slots so category-filtered views stay consistent
            $slots = Faq::whereIn('id', $ids)->pluck('sort_order')->sort()->values();
            if ($slots->unique()->count() !== count($ids)) {
                $slots = collect(range(1, count($ids)));   // fallback if numbers were duplicated or all 0
            }
            foreach ($ids as $i => $id) {
                Faq::where('id', $id)->update(['sort_order' => $slots[$i]]);
            }
        });

        return response()->json(['ok' => true]);
    }
}
