@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.programs.index') }}" class="text-[#1B4332] hover:underline text-sm font-medium">← Programs</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-extrabold text-gray-900">Edit: {{ $program->title }}</h1>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.programs.update', $program) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title (English)</label>
                <input type="text" name="title" required value="{{ old('title', $program->title) }}"
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title (Arabic)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $program->title_ar) }}" dir="rtl"
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description (English)</label>
                <textarea name="description" rows="3"
                          class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none resize-none transition">{{ old('description', $program->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description (Arabic)</label>
                <textarea name="description_ar" rows="3" dir="rtl"
                          class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none resize-none transition">{{ old('description_ar', $program->description_ar) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Access Level</label>
                <select name="access_level" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                    <option value="free" {{ $program->access_level === 'free' ? 'selected' : '' }}>Free</option>
                    <option value="premium" {{ $program->access_level === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ $program->is_published ? 'checked' : '' }}
                       class="rounded border-gray-300 text-[#1B4332] focus:ring-[#1B4332]">
                <span class="text-sm font-medium text-gray-700">Published</span>
            </label>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">Save Changes</button>
                <a href="{{ route('admin.programs.index') }}"
                   class="flex-1 border-2 border-gray-200 text-gray-600 font-semibold py-3 rounded-xl text-sm text-center hover:bg-gray-50 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
