@extends('layouts.app') {{-- //HMT Cần check chỗ này --}}
@section('title', 'Về chúng tôi')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="contact-us container">
      <div class="mw-930">
        <h2 class="page-title">About US</h2>
      </div>

      <div class="about-us__content pb-5 mb-5">
        <p class="mb-5">
          <img loading="lazy" class="w-100 h-auto d-block" src="assets/images/about/about-1.jpg" width="1410"
            height="550" alt="" />
        </p>
        <div class="mw-930">
          <h3 class="mb-4">Câu chuyện của chúng tôi</h3>
          <p class="fs-6 fw-medium mb-4">Chúng tôi bắt đầu từ một quầy kem nhỏ trên góc phố, nơi từng viên kem không chỉ mang hương vị ngọt ngào mà còn chứa đựng cả đam mê và ước mơ lớn.</p>
          <p class="mb-4">Từ những ngày đầu tự tay làm kem, thử nghiệm từng công thức đến khi xây dựng thương hiệu, chúng tôi luôn giữ vững tinh thần sáng tạo và chất lượng. Hành trình từ một cửa hàng nhỏ đến một doanh nghiệp phát triển hôm nay là minh chứng cho sự kiên trì, đổi mới và cam kết mang đến sản phẩm tốt nhất cho khách hàng.</p>
          <div class="row mb-3">
            <div class="col-md-6">
              <h5 class="mb-3">Sứ mệnh của chúng tôi</h5>
              <p class="mb-3">Chúng tôi cam kết mang đến những sản phẩm chất lượng, không chỉ làm hài lòng vị giác mà còn tạo nên những khoảnh khắc đáng nhớ. Sứ mệnh của chúng tôi là kết nối con người qua những trải nghiệm hương vị độc đáo, đồng thời không ngừng cải tiến để đáp ứng nhu cầu của thị trường.</p>
            </div>
            <div class="col-md-6">
              <h5 class="mb-3">Tầm nhìn của chúng tôi</h5>
              <p class="mb-3">Trở thành thương hiệu dẫn đầu trong ngành thực phẩm & đồ uống, không chỉ với sản phẩm ngon mà còn với sự sáng tạo và giá trị cộng đồng. Chúng tôi hướng tới việc mở rộng quy mô, đưa sản phẩm đến nhiều thị trường hơn và trở thành lựa chọn tin cậy cho khách hàng ở mọi nơi.</p>
            </div>
          </div>
        </div>
        <div class="mw-930 d-lg-flex align-items-lg-center">
          <div class="image-wrapper col-lg-6">
            <img class="h-auto" loading="lazy" src="assets/images/about/about-1.jpg" width="450" height="500" alt="">
          </div>
          <div class="content-wrapper col-lg-6 px-lg-4">
            <h5 class="mb-3">Công ty của chúng tôi</h5>
            <p>Chúng tôi không chỉ là một doanh nghiệp, mà còn là một cộng đồng của những con người yêu thích hương vị và sự đổi mới. Với đội ngũ giàu nhiệt huyết, cùng hệ thống sản xuất và phân phối chuyên nghiệp, chúng tôi đang từng bước hiện thực hóa tầm nhìn của mình – mang sản phẩm chất lượng đến với nhiều người hơn, theo cách bền vững và sáng tạo nhất.</p>
          </div>
        </div>
      </div>
    </section>


  </main>
@endsection