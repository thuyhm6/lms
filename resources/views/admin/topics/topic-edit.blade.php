@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap vh-100">

            <div class="flex items-center flex-wrap justify-between gap20">
                <div class="d-flex items-center gap20">
                    <h3>Chỉnh sửa chủ đề</h3>
                    <div class="mt-4">
                        <a class="tf-button style-1 w208" href="{{ route('admin.topic') }}">
                            <i class="icon-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa chủ đề</div>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('admin.topic.update', $topic->id) }}" enctype="multipart/form-data"
                class="d-flex justify-between">
                @csrf
                @method('PUT')
                <div class="p-2 col-lg-12">
                    <div class="bg-white rounded p-3 d-flex flex-wrap justify-between align-items-end">

                        {{-- Tên chủ đề --}}
                        <fieldset class="my-3 title col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Tên chủ đề <span class="tf-color-1">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $topic->name) }}">
                            @error('name')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- Chủ đề cha --}}
                        <fieldset class="my-3 news_events col-lg-6 col-md-12 p-3">
                            <label class="body-title mb-4">Chủ đề cha <span class="tf-color-1">*</span></label>
                            <select name="parent_id">
                                <option value="" {{ is_null(old('parent_id', $topic->parent_id)) ? 'selected' : '' }}>-- Không có --</option>
                                @foreach ($topics as $parent)
                                    @if ($parent->id != $topic->id) {{-- Tránh chọn chính nó làm cha --}}
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $topic->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="text-danger"> {{ $message }}</p>
                            @enderror
                        </fieldset>

                        <div class="mt-4 col-lg-12 col-md-12 p-3">
                            <button type="submit" class="tf-button w-full">Cập nhật chủ đề</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $("input[name='name']").on("change", function(e) {
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });

        function StringToSlug(param) {
            return param.toLowerCase().replace(/[^\w ]+/g, "").replace(/ +/g, "-");
        }
    </script>
@endpush
