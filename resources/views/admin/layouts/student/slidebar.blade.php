<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Trang quản lý
        </h3>
    </div>
    <div class="card-body text-center item-user border-bottom">
        <div class="profile-pic">
            @if(!empty(Auth::user()->image))
            <div class="profile-pic-img">
                <span class="bg-success dots" data-original-title="online" data-placement="top" data-toggle="tooltip" title="">
                </span>
                <img alt="user" class="brround" src="{{ getProfilePath(Auth::user()->image) }}"/>
            </div>
            @endif
            <a class="text-dark" href="#">
                <h4 class="mt-3 mb-0 font-weight-semibold">
                    {{ ucwords(Auth::user()->name) }}
                </h4>
            </a>
        </div>
    </div>
    <div class="item1-links mb-0">
        <a class="d-flex border-bottom {{ isActiveClass($active_class, 'users_edit') }}" href="/users/profile/{{Auth::user()->slug}}">
            <span class="icon1 mr-3">
                <i class="icon icon-user">
                </i>
            </span>
            Trang cá nhân
        </a>
        <a class="d-flex border-bottom {{ isActiveClass($active_class, 'reward-point') }}" href="{{ route('users.reward_point', Auth::user()->slug) }}">
            <span class="icon1 mr-3">
                <i class="fa fa-gift">
                </i>
            </span>
            Điểm tích luỹ
        </a>
        <a class="d-flex border-bottom {{ isActiveClass($active_class, 'reward-points-leaderboard') }}" href="{{ route('users.reward_points_leaderboard', Auth::user()->slug) }}">
            <span class="icon1 mr-3">
                <i class="icon icon-trophy">
                </i>
            </span>
            Bảng xếp hạng
        </a>
        <a class="d-flex border-bottom {{isActiveClass($active_class, 'lmscategories') }}" href="{{PREFIX.'lms/exam-categories/list'}}">
            <span class="icon1 mr-3">
                <i class="fa fa-graduation-cap">
                </i>
            </span>
            Khóa học của bạn
        </a>
        <a class="d-flex border-bottom {{ isActiveClass($active_class, 'lmsstudy') }}" href="{{PREFIX.'lms/exam-categories/study'}}">
            <span class="icon1 mr-3">
                <i class="icon icon-diamond">
                </i>
            </span>
            Khóa luyện thi của bạn
        </a>
        <a class="d-flex border-bottom {{ isActiveClass($active_class, 'lmscomments') }}" href="{{PREFIX.'lms/exam-categories/comments'}}">
            <span class="icon1 mr-3">
                <i class="icon icon-bell">
                </i>
            </span>
            Câu hỏi của bạn
        </a>
        <a class=" d-flex border-bottom {{isActiveClass($active_class, 'examslist') }}" href="/exams/student-exam-series/list">
            <span class="icon1 mr-3">
                <i class="icon icon-heart">
                </i>
            </span>
            Phòng thi của bạn
        </a>
		<a class=" d-flex border-bottom {{isActiveClass($active_class, 'resultexam') }}" href="/exams/exam-categories/result">
            <span class="icon1 mr-3">
                <i class="icon icon-star">
                </i>
            </span>
            Kết quả thi
        </a>
        <a class="d-flex border-bottom {{isActiveClass($active_class, 'lmspayments') }}" href="{{PREFIX.'lms/exam-categories/payment'}}">
            <span class="icon1 mr-3">
                <i class="icon icon-credit-card">
                </i>
            </span>
            Quản lý thanh toán
        </a>
        {{--
        <a class="d-flex border-bottom" href="orders.html">
            <span class="icon1 mr-3">
                <i class="icon icon-basket">
                </i>
            </span>
            Orders
        </a>
        <a class="d-flex border-bottom" href="tips.html">
            <span class="icon1 mr-3">
                <i class="icon icon-game-controller">
                </i>
            </span>
            Safety Tips
        </a>
        <a class="d-flex border-bottom" href="settings.html">
            <span class="icon1 mr-3">
                <i class="icon icon-settings">
                </i>
            </span>
            Settings
        </a>
        --}}
        <a class="d-flex" href="/logout">
            <span class="icon1 mr-3">
                <i class="icon icon-power">
                </i>
            </span>
            Thoát
        </a>
    </div>
</div>
{{--
<div class="card my-select">
    <div class="card-header">
        <h3 class="card-title">
            Search Classes
        </h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <input class="form-control" id="text" placeholder="What are you looking for?" type="text">
            </input>
        </div>
        <div class="form-group">
            <select aria-hidden="true" class="form-control custom-select select2-show-search select2-hidden-accessible" data-select2-id="select-countries" id="select-countries" name="country" tabindex="-1">
                <option data-select2-id="2" selected="" value="1">
                    All Categories
                </option>
                <option data-select2-id="6" value="2">
                    Web Security
                </option>
                <option data-select2-id="7" value="3">
                    Restaurant
                </option>
                <option data-select2-id="8" value="4">
                    Business
                </option>
                <option data-select2-id="9" value="5">
                    Online
                </option>
                <option data-select2-id="10" value="6">
                    Data Science
                </option>
                <option data-select2-id="11" value="7">
                    Driving
                </option>
                <option data-select2-id="12" value="8">
                    Education
                </option>
                <option data-select2-id="13" value="9">
                    Electronics
                </option>
                <option data-select2-id="14" value="10">
                    Pets & Offline
                </option>
                <option data-select2-id="15" value="11">
                    Computer
                </option>
                <option data-select2-id="16" value="12">
                    Mobile
                </option>
                <option data-select2-id="17" value="13">
                    Events
                </option>
                <option data-select2-id="18" value="14">
                    Python
                </option>
                <option data-select2-id="19" value="15">
                    Security Hacking
                </option>
            </select>
            <span class="select2 select2-container select2-container--default" data-select2-id="20" dir="ltr" style="width: 100%;">
                <span class="selection">
                    <span aria-expanded="false" aria-haspopup="true" aria-labelledby="select2-select-countries-container" class="select2-selection select2-selection--single" role="combobox" tabindex="0">
                        <span aria-readonly="true" class="select2-selection__rendered" id="select2-select-countries-container" role="textbox" title="All Categories">
                            All Categories
                        </span>
                        <span class="select2-selection__arrow" role="presentation">
                            <b role="presentation">
                            </b>
                        </span>
                    </span>
                </span>
                <span aria-hidden="true" class="dropdown-wrapper">
                </span>
            </span>
        </div>
        <div class="">
            <a class="btn btn-primary" href="#">
                Search
            </a>
        </div>
    </div>
</div>
--}}
            {{--
<div class="card mb-xl-0">
    <div class="card-header">
        <h3 class="card-title">
            Safety Tips For Buyers
        </h3>
    </div>
    <div class="card-body">
        <ul class="list-unstyled widget-spec mb-0">
            <li class="">
                <i aria-hidden="true" class="fa fa-check text-success">
                </i>
                Meet Seller at public Place
            </li>
            <li class="">
                <i aria-hidden="true" class="fa fa-check text-success">
                </i>
                Check item before you buy
            </li>
            <li class="">
                <i aria-hidden="true" class="fa fa-check text-success">
                </i>
                Pay only after collecting item
            </li>
            <li class="ml-5 mb-0">
                <a href="tips.html">
                    View more..
                </a>
            </li>
        </ul>
    </div>
</div>
--}}
