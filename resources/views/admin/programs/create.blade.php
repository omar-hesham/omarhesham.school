@extends('layouts.dashboard')
@section('dashboard_content')
<div class="p-8 max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.programs.index') }}" class="text-[#1B4332] hover:underline text-sm font-medium">← Programs</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-2xl font-extrabold text-gray-900">New Program</h1>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.programs.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title (English)</label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title (Arabic)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" dir="rtl"
                       class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description (English)</label>
                <textarea name="description" rows="3"
                          class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none resize-none transition">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description (Arabic)</label>
                <textarea name="description_ar" rows="3" dir="rtl"
                          class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none resize-none transition">{{ old('description_ar') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Access Level</label>
                <select name="access_level" class="w-full border-2 border-gray-200 focus:border-[#1B4332] rounded-xl px-4 py-3 text-sm outline-none transition">
                    <option value="free" {{ old('access_level') === 'free' ? 'selected' : '' }}>Free</option>
                    <option value="premium" {{ old('access_level') === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-[#1B4332] focus:ring-[#1B4332]">
                <span class="text-sm font-medium text-gray-700">Publish immediately</span>
            </label>
            <button type="submit" class="w-full bg-gradient-to-r from-[#D4AF37] to-[#F0C040] text-[#0A1F14] font-bold py-3 rounded-xl text-sm">Create Program</button>
        </form>
    </div>
</div>
@endsection
