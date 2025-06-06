@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Edit Parent</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.parents') }}">
                            <div class="text-tiny">Parents</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Edit parent</div>
                    </li>
                </ul>
            </div>

            <form class="tf-section-2 form-edit-parent" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.parent.update', $parent->user_id) }}">
                @csrf
                @method('PUT')
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Họ và tên <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter full parent name" name="full_name"
                            tabindex="0" value="{{ old('full_name', $parent->full_name) }}" aria-required="true" required>
                        <div class="text-tiny">Do not exceed 100 characters when entering the parent fullname.</div>
                    </fieldset>
                    @error('full_name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Email <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter parent email" name="email"
                                tabindex="0" value="{{ old('email', $parent->email) }}" aria-required="true" required>
                        </fieldset>
                        @error('email')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter parent phone number" name="mobile"
                                tabindex="0" value="{{ old('mobile', $parent->mobile) }}" aria-required="true" required>
                        </fieldset>
                        @error('mobile')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="name">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter parent address" name="address"
                            tabindex="0" value="{{ old('address', $parent->address) }}" aria-required="true" required>
                    </fieldset>
                    @error('address')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Trường học của con <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter school name" name="school"
                                tabindex="0" value="{{ old('school', $parent->school) }}" aria-required="true" required>
                        </fieldset>
                        @error('school')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Lớp của con <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter child class" name="grade"
                                tabindex="0" value="{{ old('grade', $parent->grade) }}" aria-required="true" required>
                        </fieldset>
                        @error('grade')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="category">
                            <div class="body-title mb-10">Trạng thái tìm hiểu <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="status" required>
                                    <option disabled {{ old('status', $parent->status) ? '' : 'selected' }}>Chọn trạng thái
                                    </option>
                                    <option value="pending"
                                        {{ old('status', $parent->status) == 'pending' ? 'selected' : '' }}>Chờ đợi
                                    </option>
                                    <option value="interested"
                                        {{ old('status', $parent->status) == 'interested' ? 'selected' : '' }}>Quan tâm
                                    </option>
                                    <option value="exploring"
                                        {{ old('status', $parent->status) == 'exploring' ? 'selected' : '' }}>Tìm hiểu
                                    </option>
                                    <option value="doubtful"
                                        {{ old('status', $parent->status) == 'doubtful' ? 'selected' : '' }}>Nghi ngờ
                                    </option>
                                    <option value="rejected"
                                        {{ old('status', $parent->status) == 'rejected' ? 'selected' : '' }}>Từ chối
                                    </option>
                                    <option value="completed"
                                        {{ old('status', $parent->status) == 'completed' ? 'selected' : '' }}>Hoàn thành
                                    </option>
                                    <option value="reserved"
                                        {{ old('status', $parent->status) == 'reserved' ? 'selected' : '' }}>Bảo lưu
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $parent->status) == 'inactive' ? 'selected' : '' }}>Ngừng khai
                                        thác</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('status')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="brand">
                            <div class="body-title mb-10">Hình thức học <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="learning_format" required>
                                    <option disabled
                                        {{ old('learning_format', $parent->learning_format) ? '' : 'selected' }}>Chọn hình
                                        thức học</option>
                                    <option value="online"
                                        {{ old('learning_format', $parent->learning_format) == 'online' ? 'selected' : '' }}>
                                        Online</option>
                                    <option value="offline"
                                        {{ old('learning_format', $parent->learning_format) == 'offline' ? 'selected' : '' }}>
                                        Offline</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('learning_format')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.parents') }}">
                        <i class="icon-arrow-left"></i> Quay lại
                    </a>
                </div>

                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload Avatar Parent <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview"
                                style="{{ $parent->image && $parent->image != 'default.png' ? '' : 'display: none;' }}">
                                <img src="{{ $parent->image && $parent->image != 'default.png' ? asset('uploads/avatars/' . $parent->image) : '' }}"
                                    class="effect8" alt="parent avatar">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Chọn môn học <span class="tf-color-1">*</span></div>
                        <div class="custom-multiselect-container">
                            <select name="subjects[]" multiple id="subjectsSelect" class="hidden-select">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                        {{ in_array($subject->id, $selectedSubjects) ? 'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="custom-multiselect" id="customMultiselect">
                                <div class="selected-display" id="selectedDisplay">
                                    @if(count($selectedSubjects) > 0)
                                        @foreach($subjects as $subject)
                                            @if(in_array($subject->id, $selectedSubjects))
                                                <span class="subject-tag" data-id="{{ $subject->id }}">
                                                    {{ $subject->subject_name }}
                                                    <span class="remove-subject">×</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="placeholder-text">Chưa chọn môn học</span>
                                    @endif
                                </div>
                                <div class="dropdown-list" id="subjectDropdown" style="display: none;">
                                    @foreach ($subjects as $subject)
                                        <div class="dropdown-item {{ in_array($subject->id, $selectedSubjects) ? 'selected' : '' }}"
                                            data-id="{{ $subject->id }}" data-name="{{ $subject->subject_name }}">
                                            {{ $subject->subject_name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @error('subjects')
                            <span class="alert alert-danger d-block mt-2 text-center">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Nguồn marketing <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="marketing_source" required>
                                    <option disabled
                                        {{ old('marketing_source', $parent->marketing_source) ? '' : 'selected' }}>Chọn
                                        nguồn</option>
                                    <option value="none"
                                        {{ old('marketing_source', $parent->marketing_source) == 'none' ? 'selected' : '' }}>
                                        Chưa chọn</option>
                                    <option value="ads_content"
                                        {{ old('marketing_source', $parent->marketing_source) == 'ads_content' ? 'selected' : '' }}>
                                        Quảng cáo</option>
                                    <option value="consultant"
                                        {{ old('marketing_source', $parent->marketing_source) == 'consultant' ? 'selected' : '' }}>
                                        Tham khảo</option>
                                    <option value="class_management"
                                        {{ old('marketing_source', $parent->marketing_source) == 'class_management' ? 'selected' : '' }}>
                                        CSKH - Quản lý lớp học</option>
                                    <option value="workshop"
                                        {{ old('marketing_source', $parent->marketing_source) == 'workshop' ? 'selected' : '' }}>
                                        Workshop</option>
                                    <option value="sales_marketing"
                                        {{ old('marketing_source', $parent->marketing_source) == 'sales_marketing' ? 'selected' : '' }}>
                                        Sale & Marketing</option>
                                    <option value="teacher"
                                        {{ old('marketing_source', $parent->marketing_source) == 'teacher' ? 'selected' : '' }}>
                                        Giáo viên</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('marketing_source')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="description">
                        <div class="body-title mb-10">Ghi chú <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10" name="notes" placeholder="Ghi chú" tabindex="0" aria-required="true" required>{{ old('notes', $parent->notes) }}</textarea>
                    </fieldset>
                    @error('notes')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Update Parent</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        select {
            border: none;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        select.form-control[multiple] {
            height: 170px;
        }

        .alert.alert-danger {
            font-size: 13px;
            padding: 5px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .tf-button {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
        }

        .tf-button.style-1 {
            background-color: #6c757d;
            color: #fff;
        }

        .cols.gap22 {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .cols.gap22>* {
            flex: 1;
            min-width: 200px;
        }

        .upload-image .item {
            margin-bottom: 10px;
        }

        .upload-image img {
            max-width: 150px;
            border-radius: 8px;
        }
        
        /* New multiselect dropdown styles */
        .hidden-select {
            display: none;
        }
        
        .custom-multiselect-container {
            position: relative;
            width: 100%;
        }
        
        .custom-multiselect {
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            width: 100%;
            position: relative;
        }
        
        .selected-display {
            min-height: 42px;
            padding: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            cursor: pointer;
        }
        
        .placeholder-text {
            color: #999;
        }
        
        .subject-tag {
            background-color: #73dded;
            color: #fff;
            border-radius: 4px;
            padding: 3px 8px;
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        
        .remove-subject {
            margin-left: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .dropdown-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dcdcdc;
            border-radius: 0 0 6px 6px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            font-size: 15px;
        }
        
        .dropdown-item:hover {
            background-color: #f5f5f5;
        }
        
        .dropdown-item.selected {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(function() {
            // Image preview
            $("#myFile").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            // Improved Subjects Multiselect
            const subjectsSelect = $('#subjectsSelect');
            const customMultiselect = $('#customMultiselect');
            const selectedDisplay = $('#selectedDisplay');
            const subjectDropdown = $('#subjectDropdown');
            
            // Show/hide dropdown
            selectedDisplay.on('click', function() {
                subjectDropdown.slideToggle(200);
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!customMultiselect.is(e.target) && customMultiselect.has(e.target).length === 0) {
                    subjectDropdown.slideUp(200);
                }
            });
            
            // Select subject
            $(document).on('click', '.dropdown-item', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                
                // Add to select
                if (!subjectsSelect.find(`option[value="${id}"]`).prop('selected')) {
                    subjectsSelect.find(`option[value="${id}"]`).prop('selected', true);
                    
                    // Add tag
                    if (selectedDisplay.find('.placeholder-text').length > 0) {
                        selectedDisplay.empty();
                    }
                    
                    selectedDisplay.append(`
                        <span class="subject-tag" data-id="${id}">
                            ${name}
                            <span class="remove-subject">×</span>
                        </span>
                    `);
                    
                    // Hide option in dropdown
                    $(this).addClass('selected');
                }
            });
            
            // Remove subject
            $(document).on('click', '.remove-subject', function(e) {
                e.stopPropagation();
                const tag = $(this).parent();
                const id = tag.data('id');
                
                // Remove from select
                subjectsSelect.find(`option[value="${id}"]`).prop('selected', false);
                
                // Remove tag
                tag.remove();
                
                // Show option in dropdown
                subjectDropdown.find(`.dropdown-item[data-id="${id}"]`).removeClass('selected');
                
                // Show placeholder if no tags
                if (selectedDisplay.children().length === 0) {
                    selectedDisplay.html('<span class="placeholder-text">Chưa chọn môn học</span>');
                }
            });
        });
    </script>
@endpush