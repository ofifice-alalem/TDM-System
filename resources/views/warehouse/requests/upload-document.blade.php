@extends('layouts.app')

@section('title', 'رفع الفاتورة الموقعة')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">رفع الفاتورة الموقعة</h1>
        
        <form id="uploadForm">
            <div class="mb-4">
                <label class="block mb-2 font-semibold">اختر صورة الفاتورة</label>
                <input type="file" id="stamped_image" accept="image/*" class="w-full border rounded p-2" required>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">رفع وتوثيق</button>
                <a href="/warehouse/requests" class="bg-gray-500 text-white px-6 py-2 rounded">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('uploadForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const id = new URLSearchParams(window.location.search).get('id');
        const fileInput = document.getElementById('stamped_image');
        
        const formData = new FormData();
        formData.append('stamped_image', fileInput.files[0]);
        
        const token = localStorage.getItem('token');
        const response = await fetch(`${API_BASE_URL}/warehouse/requests/${id}/document`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}` },
            body: formData
        });
        
        if (response.ok) {
            alert('تم التوثيق بنجاح');
            window.location.href = '/warehouse/requests';
        }
    });
</script>
@endpush
