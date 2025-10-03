@foreach ($sidebar_personnel_hr as $item_sidebar)
    <li class="nav-item">
        <a data-bs-toggle="collapse" href="#{{ $item_sidebar->id }}">
            <i class="{{ $item_sidebar->icon }}"></i>
            <p style="font-size: 12px;">{{ $item_sidebar->name }}</p>
            <span class="caret"></span>
        </a>
        @php
            // Cek apakah salah satu sub-item sedang aktif berdasarkan tab di URL
            $isActiveParent = collect($item_sidebar->r_child)->contains(function ($subItem) {
                return request('tab') == $subItem->tab;
            });
        @endphp

        <div class="collapse  {{ Request::is('time-sheet*') || Request::is('presensi*') || Request::is('cv*') ? 'show' : '' }} {{ $isActiveParent ? 'show' : '' }}"
            id="{{ $item_sidebar->id }}">
            <ul class="nav nav-collapse">
                @can('view_presensi')
                    <li class="nav-item nav-item-custom nav-item-custom {{ Request::is('presensi*') ? 'active' : '' }}">
                        <a href="{{ route('presensi') }}">
                            <span class="sub-item">Attendance</span>

                        </a>
                    </li>
                @endcan
                @can('view_time_sheet')
                    <li class="nav-item nav-item-custom nav-item-custom {{ Request::is('time-sheet*') ? 'active' : '' }}">
                        <a href="{{ route('time-sheet') }}">
                            <span class="sub-item">Time Sheet</span>

                        </a>
                    </li>
                @endcan
                @can('view_cv')
                    <li class="nav-item-custom {{ Request::is('cv-list*') ? 'active' : '' }}">
                        <a href="{{ route('cv-list') }}">
                            <span class="sub-item">Curriculum Vitae</span>

                        </a>
                    </li>
                @endcan
                @can('view_cv_group')
                    <li class="nav-item-custom {{ Request::is('cv-pengajuan*') ? 'active' : '' }}">
                        <a href="{{ route('cv-pengajuan') }}">
                            <span class="sub-item">Submit CV</span>

                        </a>
                    </li>
                @endcan

                @can('review_cv_group')
                    <li class="nav-item-custom {{ Request::is('cv-review*') ? 'active' : '' }}">
                        <a href="{{ route('cv-list') }}">
                            <span class="sub-item">Review CV</span>

                        </a>
                    </li>
                @endcan

                @can('view_personnel_hr')
                    @foreach ($item_sidebar->r_child as $item_sub_sidebar)
                        <li class="nav-item-custom {{ request('tab') == $item_sub_sidebar->tab ? 'active' : '' }}">
                            <a
                                href="{{ route('custom-personnel-hr', ['tab' => $item_sub_sidebar->tab, 'icon' => $item_sidebar->icon]) }}">
                                <span class="sub-item">{{ $item_sub_sidebar->name }}</span>
                                <span class="badge badge-success">{{ $item_sub_sidebar->jml_doc }}</span>
                            </a>
                        </li>
                    @endforeach
                @endcan
            </ul>
        </div>
    </li>
@endforeach
