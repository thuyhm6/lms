@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="breadcrumb-wrapper">
            <ul class="breadcrumb">
                {{-- <li>
                    <a href="{{ route('student.dashboard') }}">
                        <div class="text-tiny">Trang chủ</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('student.subjects') }}">
                        <div class="text-tiny">Môn học</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Bài giảng</div>
                </li> --}}
            </ul>
        </div>

        <!-- Lesson Header -->
        <div class="wg-box mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="lesson-header">
                        <h1 class="lesson-title mb-3">{{ $lesson->lesson_name ?? 'Tên bài giảng' }}</h1>

                        <div class="lesson-meta d-flex flex-wrap gap-3 mb-3">
                            <div class="meta-item">
                                <span class="badge bg-primary">{{ $lesson->topic ?? 'Chủ đề' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="badge bg-info">{{ $lesson->type ?? 'Bài giảng' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="badge bg-success">{{ $lesson->fee_type ?? 'Offline' }}</span>
                            </div>
                            <div class="meta-item">
                                <span class="badge bg-warning">{{ $lesson->file_type ?? 'iSpring' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="icon-clock"></i>
                                <span>Thời lượng: {{ $lesson->duration ?? '01:30:00' }}</span>
                            </div>
                        </div>

                        @if ($lesson->content)
                            <div class="lesson-description">
                                <h6>Mô tả bài giảng:</h6>
                                <p class="text-muted">{{ $lesson->content }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson Content -->
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <div class="wg-box">
                    <div class="lesson-content-wrapper">
                        <h5 class="mb-3">Nội dung bài giảng</h5>

                        <!-- Content Frame based on file type -->
                        <div class="content-frame">
                            @if ($lesson->file_type == 'Video')
                                <!-- Video Content -->
                                <div class="video-wrapper">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item"
                                            src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @elseif($lesson->file_type == 'PDF')
                                <!-- PDF Content -->
                                <div class="pdf-wrapper">
                                    <div class="pdf-placeholder">
                                        <i class="icon-file-pdf" style="font-size: 48px; color: #dc3545;"></i>
                                        <h6 class="mt-2">Tài liệu PDF</h6>
                                        <p class="text-muted">Nội dung bài giảng dạng PDF sẽ được hiển thị tại đây</p>
                                        <button class="btn btn-outline-primary">
                                            <i class="icon-download"></i> Tải xuống PDF
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- iSpring or other content -->
                                <div class="ispring-wrapper">
                                    <div class="content-placeholder">
                                        <i class="icon-play-circle" style="font-size: 48px; color: #28a745;"></i>
                                        <h6 class="mt-2">Nội dung tương tác</h6>
                                        <p class="text-muted">Bài giảng tương tác sẽ được hiển thị tại đây</p>
                                        <button class="btn btn-primary">
                                            <i class="icon-play"></i> Bắt đầu học
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Lesson Info -->
                <div class="wg-box mb-3">
                    <h6 class="mb-3">Thông tin bài giảng</h6>
                    <div class="lesson-info">
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Giáo viên:</span>
                            <span>{{ $lesson->teacher->full_name ?? 'Chưa có thông tin' }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Môn học:</span>
                            <span>{{ $lesson->subject->name ?? 'Chưa có thông tin' }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Ngày tạo:</span>
                            <span>{{ $lesson->created_at ? $lesson->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between">
                            <span class="text-muted">Cập nhật:</span>
                            <span>{{ $lesson->updated_at ? $lesson->updated_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Homework Section -->
                <div class="wg-box">
                    <h6 class="mb-3">Bài tập về nhà</h6>

                    <!-- View Homework Button -->
                    <div class="homework-actions">
                        <button class="btn btn-outline-primary w-100 mb-2" onclick="viewHomework()">
                            <i class="icon-eye"></i> Xem bài tập về nhà
                        </button>

                        <!-- Upload Homework Button -->
                        <button class="btn btn-success w-100" onclick="uploadHomework()">
                            <i class="icon-upload"></i> Nộp bài tập
                        </button>
                    </div>

                    <!-- Homework Status -->
                    <div class="homework-status mt-3">
                        <div class="status-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Trạng thái:</span>
                            <span class="badge bg-warning">Chưa nộp</span>
                        </div>
                        <div class="status-item d-flex justify-content-between align-items-center mt-2">
                            <span class="text-muted">Hạn nộp:</span>
                            <span class="text-danger">25/12/2024</span>
                        </div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="wg-box mt-3">
                    <h6 class="mb-3">Tiến độ học tập</h6>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Đã hoàn thành 75% bài giảng</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Homework Modal -->
    <div class="modal fade" id="homeworkModal" tabindex="-1" aria-labelledby="homeworkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="homeworkModalLabel">Bài tập về nhà</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="homeworkContent">
                        <!-- Homework content will be loaded here -->
                        <p>Nội dung bài tập về nhà sẽ được hiển thị tại đây...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Tải xuống</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Homework Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Nộp bài tập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadHomeworkForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="homeworkFile" class="form-label">Chọn file bài tập</label>
                            <input type="file" class="form-control" id="homeworkFile" name="homework_file"
                                accept=".pdf,.doc,.docx,.jpg,.png">
                            <div class="form-text">Hỗ trợ: PDF, DOC, DOCX, JPG, PNG (Tối đa 10MB)</div>
                        </div>
                        <div class="mb-3">
                            <label for="homeworkNote" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="homeworkNote" name="note" rows="3"
                                placeholder="Thêm ghi chú cho bài tập..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">Nộp bài</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .lesson-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }

        .lesson-title {
            color: #2c3e50;
            font-weight: 600;
        }

        .lesson-meta .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .content-frame {
            min-height: 400px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-wrapper,
        .pdf-wrapper,
        .ispring-wrapper {
            height: 400px;
            position: relative;
        }

        .embed-responsive {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
        }

        .embed-responsive-16by9 {
            padding-bottom: 56.25%;
        }

        .embed-responsive-item {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .pdf-placeholder,
        .content-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }

        .homework-actions .btn {
            transition: all 0.3s ease;
        }

        .homework-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .lesson-info .info-item {
            padding: 8px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .lesson-info .info-item:last-child {
            border-bottom: none;
        }

        .homework-status .status-item {
            padding: 5px 0;
        }

        .breadcrumb-wrapper {
            margin-bottom: 20px;
        }

        .breadcrumb {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
            gap: 10px;
        }

        .breadcrumb li {
            display: flex;
            align-items: center;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #6c757d;
        }

        .breadcrumb a:hover {
            color: #007bff;
        }

        .text-tiny {
            font-size: 14px;
        }
    </style>

    <script>
        function viewHomework() {
            // Show homework modal
            const modal = new bootstrap.Modal(document.getElementById('homeworkModal'));
            modal.show();

            // Load homework content (you can make an AJAX call here)
            document.getElementById('homeworkContent').innerHTML = `
        <div class="homework-detail">
            <h6>Bài tập: {{ $lesson->lesson_name ?? 'Tên bài giảng' }}</h6>
            <div class="homework-description">
                <p><strong>Yêu cầu:</strong></p>
                <ul>
                                        <li>Hoàn thành các bài tập trong tài liệu đính kèm</li>
                    <li>Trả lời đầy đủ các câu hỏi lý thuyết</li>
                    <li>Nộp bài trước hạn chót</li>
                </ul>
                <p><strong>Hướng dẫn:</strong></p>
                <p>Học sinh cần đọc kỹ tài liệu bài giảng và áp dụng kiến thức đã học để giải quyết các bài tập. Bài làm cần được trình bày rõ ràng, có lập luận logic.</p>
                <p><strong>Thời gian:</strong> 2 tiếng</p>
                <p><strong>Hạn nộp:</strong> <span class="text-danger">25/12/2024 - 23:59</span></p>
            </div>
            <div class="homework-files mt-3">
                <h6>Tài liệu đính kèm:</h6>
                <div class="file-list">
                    <div class="file-item d-flex align-items-center justify-content-between p-2 border rounded mb-2">
                        <div class="d-flex align-items-center">
                            <i class="icon-file-pdf text-danger me-2"></i>
                            <span>Bài tập chương 1.pdf</span>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="icon-download"></i> Tải
                        </button>
                    </div>
                    <div class="file-item d-flex align-items-center justify-content-between p-2 border rounded">
                        <div class="d-flex align-items-center">
                            <i class="icon-file-word text-primary me-2"></i>
                            <span>Mẫu bài làm.docx</span>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="icon-download"></i> Tải
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
        }

        function uploadHomework() {
            // Show upload modal
            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }

        // Handle homework upload form submission
        document.getElementById('uploadHomeworkForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const fileInput = document.getElementById('homeworkFile');

            if (!fileInput.files[0]) {
                alert('Vui lòng chọn file bài tập!');
                return;
            }

            // Check file size (10MB limit)
            if (fileInput.files[0].size > 10 * 1024 * 1024) {
                alert('File quá lớn! Vui lòng chọn file nhỏ hơn 10MB.');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Đang tải lên...';
            submitBtn.disabled = true;

            // Simulate upload (replace with actual AJAX call)
            setTimeout(() => {
                alert('Nộp bài thành công!');

                // Update homework status
                document.querySelector('.homework-status .badge').textContent = 'Đã nộp';
                document.querySelector('.homework-status .badge').className = 'badge bg-success';

                // Reset form and close modal
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                modal.hide();

                // Update progress
                updateProgress(85);
            }, 2000);

            /* 
            // Actual AJAX implementation:
            fetch('{{ route('student.homework.upload') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Nộp bài thành công!');
                    // Update UI
                } else {
                    alert('Có lỗi xảy ra: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tải lên!');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
            */
        });

        function updateProgress(percentage) {
            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.querySelector('.progress').nextElementSibling;

            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            progressText.textContent = `Đã hoàn thành ${percentage}% bài giảng`;
        }

        // Auto-update progress based on lesson completion
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate lesson progress tracking
            let watchTime = 0;
            const totalTime = 90 * 60; // 90 minutes in seconds

            // Update progress every 30 seconds (for demo)
            setInterval(() => {
                watchTime += 30;
                const progress = Math.min((watchTime / totalTime) * 100, 100);
                updateProgress(Math.round(progress));
            }, 30000);
        });

        // Handle video events (if using video)
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.querySelector('iframe');
            if (iframe) {
                // You can add YouTube API integration here to track video progress
                console.log('Video player loaded');
            }
        });

        // File preview functionality
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = document.getElementById('filePreview');

                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        preview.innerHTML =
                            `<img src="${e.target.result}" class="img-fluid" style="max-height: 200px;">`;
                    } else {
                        preview.innerHTML = `
                    <div class="file-info p-3 bg-light rounded">
                        <i class="icon-file me-2"></i>
                        <span>${file.name}</span>
                        <small class="text-muted d-block">Kích thước: ${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                    </div>
                `;
                    }
                };

                reader.readAsDataURL(file);
            }
        }

        // Add file preview to upload modal
        document.getElementById('homeworkFile').addEventListener('change', function() {
            previewFile(this);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Press 'H' to open homework modal
            if (e.key === 'h' || e.key === 'H') {
                if (!document.querySelector('.modal.show')) {
                    viewHomework();
                }
            }

            // Press 'U' to open upload modal
            if (e.key === 'u' || e.key === 'U') {
                if (!document.querySelector('.modal.show')) {
                    uploadHomework();
                }
            }

            // Press 'Escape' to close modals
            if (e.key === 'Escape') {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    const modal = bootstrap.Modal.getInstance(openModal);
                    modal.hide();
                }
            }
        });

        // Add tooltips for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Responsive video handling
        function handleResponsiveVideo() {
            const videoWrapper = document.querySelector('.video-wrapper');
            if (videoWrapper) {
                const iframe = videoWrapper.querySelector('iframe');
                if (iframe) {
                    // Adjust video size based on container
                    const containerWidth = videoWrapper.offsetWidth;
                    const aspectRatio = 16 / 9;
                    const height = containerWidth / aspectRatio;

                    iframe.style.width = '100%';
                    iframe.style.height = height + 'px';
                }
            }
        }

        // Handle window resize
        window.addEventListener('resize', handleResponsiveVideo);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', handleResponsiveVideo);
    </script>

    <!-- Add file preview container to upload modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add file preview container to upload modal
            const modalBody = document.querySelector('#uploadModal .modal-body');
            const filePreviewContainer = document.createElement('div');
            filePreviewContainer.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Xem trước file</label>
            <div id="filePreview" class="border rounded p-3 text-center text-muted">
                Chưa có file nào được chọn
            </div>
        </div>
    `;
            modalBody.insertBefore(filePreviewContainer, modalBody.lastElementChild);
        });
    </script>

    <!-- Additional CSS for better mobile responsiveness -->
    <style>
        @media (max-width: 768px) {
            .lesson-meta {
                flex-direction: column;
                gap: 10px !important;
            }

            .lesson-meta .meta-item {
                justify-content: center;
            }

            .content-frame {
                min-height: 250px;
            }

            .video-wrapper,
            .pdf-wrapper,
            .ispring-wrapper {
                height: 250px;
            }

            .homework-actions .btn {
                font-size: 14px;
                padding: 8px 12px;
            }

            .lesson-info .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .modal-dialog {
                margin: 10px;
            }
        }

        @media (max-width: 576px) {
            .breadcrumb {
                flex-wrap: wrap;
                gap: 5px;
            }

            .lesson-title {
                font-size: 1.5rem;
            }

            .content-frame {
                min-height: 200px;
            }

            .video-wrapper,
            .pdf-wrapper,
            .ispring-wrapper {
                height: 200px;
            }
        }

        /* Loading animation */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* File item hover effect */
        .file-item:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 0.6s ease;
        }

        /* Badge animations */
        .badge {
            transition: all 0.3s ease;
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }

        /* Button hover effects */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Custom scrollbar for modal content */
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
