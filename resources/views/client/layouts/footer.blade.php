<!-- Footer -->
<footer class="bg-color-primary main-footer p-10px">
    <!-- Grid container -->
    <div class="text-light container" style="max-width: 1200px;">
        <!-- Section: Links -->
        <section>
            <!--Grid row-->
            <div class="border-bottom">
                <div class="row">
                    <!-- Về HIKARI section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="border-bottom">
                            <h5>Về HIKARI</h5>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 py-2">
                                <div>
                                    <a class="text-light text-decoration-none" href="https://hikariacademy.edu.vn">HIKARI
                                        ACADEMY</a>
                                </div>
                                <div>
                                    <a class="text-light text-decoration-none"
                                        href="{{ route('site_pages', 'privacy-policy') }}">Bảo mật thông tin</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="border-bottom">
                            <h5>Chăm sóc khách hàng</h5>
                        </div>
                        <div class="py-2">
                            <div>
                                <a class="text-light text-decoration-none"
                                    href="{{ route('site_pages', 'payment-instructions') }}">Hướng
                                    dẫn thanh toán
                                </a>
                            </div>
                            <div>
                                <a class="text-light text-decoration-none"
                                    href="{{ route('site_pages', 'terms-conditions') }}">Chính sách hoàn
                                    tiền</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="border-bottom">
                            <h5>Kết Nối Với Chúng Tôi</h5>
                        </div>
                        <div class="footer-social d-flex align-items-center">
                            <a class="px-2" href="https://www.facebook.com/nhatngu.hikariacademy">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a class="px-2" href="https://www.instagram.com/nhatngu.hikari/">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a class="px-2" href="https://www.youtube.com/@hikaritvchannel5109">
                                <i class="bi bi-youtube"></i>
                            </a>
                            <a href="https://zalo.me/0902390885">
                                <img style="height: 45px;" src="{{ asset('images/icon-zalo.jpg') }}" alt="icon-zalo">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Address-->
            <div class="border-bottom">
                <div class="row">
                    <div class="col-lg-6 col-md-12 py-2">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div>Giờ hoạt động:</div>
                                <div>Thứ 2-6: 7h00 - 21h00</div>
                                <div>Thứ 7: 8h00 - 15h00</div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div>Liên hệ:</div>
                                <div>Email: info@hikariacademy.edu.vn</div>
                                <div>Điện thoại: 028 3849 8071</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 py-2">
                        <div>Cơ sở 1: Số 310 Lê Quang Định, Phường 11, Quận Bình Thạnh, TP. Hồ Chí Minh</div>
                        <div>Cơ sở 2: Tòa nhà JVPE, Lô 20, Đường số 2, Công viên phần mềm Quang Trung, Phường Tân Chánh
                            Hiệp, Quận 12, TP. Hồ Chí Minh</div>
                    </div>
                </div>
            </div>
            <!--Address-->

            <!--Copyright-->
            <div class="row">
                <div class="col-12 col-sm-6 py-2">
                    <div>Copyright © 2024 HIKARI</div>
                    <div>Mã số thuế: 0305322160, do sở kế hoạch và Đầu tư TP.Hồ Chi Minh cấp ngày 19/11/2007</div>
                    <div>Quyết định cho phép hoạt động giáo dục Trung tâm Nhật ngữ Quang Việt, số 1789/QĐ-GDĐT-TC do Sở
                        giáo
                        dục và Đào tạo TP.Hồ Chí Minh cấp ngày 28/08/2020</div>
                </div>
                <div class="col-12 col-sm-6 footer-social d-flex align-items-center">
                    <div class="col-lg-12 d-flex align-items-center justify-content-center">
                        <a href="http://online.gov.vn/Home/WebDetails/63991" class="me-2" target="_blank">
                            <img class="logo-bocongthuong ms-2 mt-2" src="{{ asset('images/bocongthuong.png') }}"
                                alt="" height="70">
                        </a>
                        <!-- Android Download Button -->
                        <a href="{{ route('downloadApp', ['encoded' => Crypt::encrypt('hikari-prod-v1.0.0.apk')]) }}"
                            class="download-button ajax-download">
                             <img src="{{ asset('images/icons/android-dowload.png') }}" alt="">
                         </a>

                        <!-- iOS Download Button -->
                        <a href="{{ route('downloadApp', ['encoded' => Crypt::encrypt('hikari-prod-v1.0.0.ipa')]) }}"
                            class="download-button ajax-download">
                            <img src="{{ asset('images/icons/ios-dowload.svg') }}" alt="">
                        </a>

                    </div>
                </div>
            </div>
            <!--Address-->
        </section>
        <!-- Section: Links -->
    </div>
    <!-- Grid container -->
</footer>
<!-- Footer -->
