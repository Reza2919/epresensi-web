@php
    $user = session('userdata');
@endphp

<nav class="header-navbar navbar navbar-expand-lg align-items-center navbar-dark navbar-shadow app-topbar">
    <div class="navbar-container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <a class="nav-link d-xl-none mr-1 menu-toggle" href="javascript:void(0);">
                <i data-feather="menu"></i>
            </a>
            <a class="app-brand d-flex align-items-center" href="{{ url('/') }}">
                <img class="app-brand__logo" src="{{ asset('assets/app-assets/images/logo/logo.png') }}" alt="e-Presensi logo">
                <span class="app-brand__text">e-Presensi</span>
            </a>
        </div>
        <ul class="nav navbar-nav align-items-center app-user-nav">
            <li class="nav-item dropdown dropdown-user app-user-dropdown">
                <a class="nav-link dropdown-toggle app-user-toggle d-flex align-items-center" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="app-user__meta d-none d-sm-flex">
                        <span class="app-user__role">{{ @$user->nama }}</span>
                        <span class="app-user__name">{{ @$user->satker ?? 'Admin'}}</span>
                    </div>
                    <span class="avatar app-user__avatar">
                        <img class="round" src="{{ @$user->foto ?? asset('assets/app-assets/images/profile/default-user.jpg') }}" alt="avatar" height="44" width="44">
                        <span class="avatar-status-online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                    <a class="dropdown-item" href="{{ route('profil') }}"><i class="mr-50" data-feather="user"></i> Profil</a>
                    <a class="dropdown-item" href="{{ url('faq') }}"><i class="mr-50" data-feather="help-circle"></i> FAQ</a>
                    @if($user->role == 'admin')
                        <a class="dropdown-item" href="{{ url('/profil') }}"><i class="mr-50" data-feather="key"></i> Ganti Password</a>
                    @else
                        <a class="dropdown-item" href="https://account.kemnaker.go.id/settings/security"><i class="mr-50" data-feather="key"></i> Ganti Password</a>
                    @endif
                    <a class="dropdown-item" href="{{ url('logout') }}"><i class="mr-50" data-feather="power"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
{{-- <ul class="main-search-list-defaultlist d-none">
    <li class="d-flex align-items-center"><a href="javascript:void(0);">
            <h6 class="section-label mt-75 mb-0">Files</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="{{ asset('assets') }}/app-assets/images/icons/xls.png" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Two new item submitted</p><small class="text-muted">Marketing Manager</small>
                </div>
            </div><small class="search-data-size mr-50 text-muted">&apos;17kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="{{ asset('assets') }}/app-assets/images/icons/jpg.png" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">52 JPG file Generated</p><small class="text-muted">FontEnd Developer</small>
                </div>
            </div><small class="search-data-size mr-50 text-muted">&apos;11kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="{{ asset('assets') }}/app-assets/images/icons/pdf.png" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">25 PDF File Uploaded</p><small class="text-muted">Digital Marketing Manager</small>
                </div>
            </div><small class="search-data-size mr-50 text-muted">&apos;150kb</small>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between w-100" href="app-file-manager.html">
            <div class="d-flex">
                <div class="mr-75"><img src="{{ asset('assets') }}/app-assets/images/icons/doc.png" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna_Strong.doc</p><small class="text-muted">Web Designer</small>
                </div>
            </div><small class="search-data-size mr-50 text-muted">&apos;256kb</small>
        </a></li>
    <li class="d-flex align-items-center"><a href="javascript:void(0);">
            <h6 class="section-label mt-75 mb-0">Members</h6>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-8.jpg" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">John Doe</p><small class="text-muted">UI designer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-1.jpg" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Michal Clark</p><small class="text-muted">FontEnd Developer</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-14.jpg" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Milena Gibson</p><small class="text-muted">Digital Marketing Manager</small>
                </div>
            </div>
        </a></li>
    <li class="auto-suggestion"><a class="d-flex align-items-center justify-content-between py-50 w-100" href="app-user-view.html">
            <div class="d-flex align-items-center">
                <div class="avatar mr-75"><img src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-6.jpg" alt="png" height="32"></div>
                <div class="search-data">
                    <p class="search-data-title mb-0">Anna Strong</p><small class="text-muted">Web Designer</small>
                </div>
            </div>
        </a></li>
</ul> --}}
<ul class="main-search-list-defaultlist-other-list d-none">
    <li class="auto-suggestion justify-content-between"><a class="d-flex align-items-center justify-content-between w-100 py-50">
            <div class="d-flex justify-content-start"><span class="mr-75" data-feather="alert-circle"></span><span>No results found.</span></div>
        </a></li>
</ul>
