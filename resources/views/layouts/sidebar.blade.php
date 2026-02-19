
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow app-sidebar" data-scroll-to-active="true">
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ Request::is('/') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboard">Dashboard</span></a>
            <li class="{{ Request::is('pegawai') || Request::is('pegawai/*') || Request::is('jurnal/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/pegawai') }}"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Data Pegawai">Data Pegawai</span></a></li>

            @if (@$user->role == 'admin')
            <li class="{{ Request::is('satker') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/satker') }}"><i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Satker">Satuan Kerja</span></a>
            </li>
            <li class="{{ Request::is('presensi-log') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/presensi-log') }}"><i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Data Presensi Log">Data Presensi Log</span></a></li>
            @endif
            @if (@$user->role == 'tu' || @$user->role == 'pimpinan')
            <li class="{{ Request::is('/presensi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/presensi') }}"><i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Data Presensi">Data Presensi</span></a></li>
            <li class="{{ Request::is('/koordinator') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/koordinator') }}"><i data-feather="user-plus"></i><span class="menu-title text-truncate" data-i18n="Data Koordinator">Data Koordinator</span></a></li>
            <li class="{{ Request::is('laporan-presensi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/laporan-presensi') }}"><i data-feather="printer"></i><span class="menu-title text-truncate" data-i18n="Laporan Presensi">Report Presensi</span></a>
            <li class="{{ Request::is('satker-setting') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/satker-setting') }}"><i data-feather="award"></i><span class="menu-title text-truncate" data-i18n="Satuan Kerja">Satuan Kerja</span></a>
            </li>
            @endif
            @if (@$user->role == 'admin')
            <li class="{{ Request::is('user') || Request::is('user/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/user') }}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="User">Superadmin</span></a>
            <li class="{{ Request::is('laporan-presensi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/laporan-presensi') }}"><i data-feather="printer"></i><span class="menu-title text-truncate" data-i18n="Laporan Presensi">Report Presensi</span></a>
            <li class="{{ Request::is('generate-presensi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/generate-presensi') }}"><i data-feather="refresh-cw"></i><span class="menu-title text-truncate" data-i18n="Laporan Presensi">Generate Presensi</span></a>
            <li class="{{ Request::is('sistem-kerja') || Request::is('sistem-kerja/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/sistem-kerja') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Sistem Kerja">Sistem Kerja</span></a>
            </li>
            <li class="{{ Request::is('periode') || Request::is('periode/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/periode') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Periode Tukin">Periode Tukin</span></a>
            </li>
            <li class="{{ Request::is('tukin') || Request::is('tukin/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/tukin') }}"><i data-feather="credit-card"></i><span class="menu-title text-truncate" data-i18n="Tunjangan Kinerja">Tunjangan Kinerja</span></a>
            </li>
            <li class="{{ Request::is('golongan-pajak') || Request::is('golongan-pajak/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/golongan-pajak') }}"><i data-feather="percent"></i><span class="menu-title text-truncate" data-i18n="Tunjangan Kinerja">Golongan Pajak</span></a>
            </li>
            <li class="{{ Request::is('cuti') || Request::is('cuti/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/cuti') }}"><i data-feather="check-square"></i><span class="menu-title text-truncate" data-i18n="Cuti">Cuti</span></a>
            </li>
            <li class="{{ Request::is('libur') || Request::is('libur/*') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/libur') }}"><i data-feather="calendar"></i><span class="menu-title text-truncate" data-i18n="Libur">Libur</span></a>
            </li>
            <li class="{{ Request::is('config') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/config') }}"><i data-feather="settings"></i><span class="menu-title text-truncate" data-i18n="Config">Config</span></a>
            </li>
            <li class="{{ Request::is('config/kebijakan-privasi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/config/kebijakan-privasi') }}"><i data-feather="shield"></i><span class="menu-title text-truncate" data-i18n="Config">Kebijakan Privasi</span></a>
            </li>
                <li class="{{ Request::is('config/manual-book') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/manual-book') }}"><i data-feather="book"></i><span class="menu-title text-truncate" data-i18n="Config">Manual Book</span></a>
                </li>
            <li class="{{ Request::is('config/syarat-ketentuan') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/config/syarat-ketentuan') }}"><i data-feather="list"></i><span class="menu-title text-truncate" data-i18n="Config">Syarat & Ketentuan</span></a>
            </li>
            <li class="{{ Request::is('config/tentang-aplikasi') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('/config/tentang-aplikasi') }}"><i data-feather="info"></i><span class="menu-title text-truncate" data-i18n="Config">Tentang Aplikasi</span></a>
            </li>
                <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="help-circle"></i><span class="menu-title text-truncate" data-i18n="Card">FAQ</span></a>
                    <ul class="menu-content">
                        <li class="{{ Request::is('setting/kategori') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('setting/kategori') }}"><i data-feather="circle"></i><span class="menu-title text-truncate" data-i18n="Config">Kategori</span></a>
                        </li>
                        <li class="{{ Request::is('setting/faq') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ url('setting/faq') }}"><i data-feather="circle"></i><span class="menu-title text-truncate" data-i18n="Config">FAQ</span></a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>
