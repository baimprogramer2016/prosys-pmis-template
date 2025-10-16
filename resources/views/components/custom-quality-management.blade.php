@foreach ($sidebar_quality_management as $item_sidebar)
    {{-- <li class="nav-item">
        <a data-bs-toggle="collapse" href="#{{ $item_sidebar->id }}">
            <i class="{{ $item_sidebar->icon }}"></i>
            <p>{{ $item_sidebar->name }}</p>
            <span class="caret"></span>
        </a> --}}

    @php
        // Cek apakah salah satu sub-item sedang aktif berdasarkan tab di URL
        $isActiveParent = collect($item_sidebar->r_child)->contains(function ($subItem) {
            return request('tab') == $subItem->tab;
        });
    @endphp

    {{-- <div class="collapse {{ $isActiveParent ? 'show' : '' }}" id="{{ $item_sidebar->id }}">
            <ul class="nav nav-collapse"> --}}
    @foreach ($item_sidebar->r_child as $item_sub_sidebar)
        @can('view_' . $item_sub_sidebar->permission)
            <li class="nav-item-custom  {{ request('tab') == $item_sub_sidebar->tab ? 'active' : '' }}">
                <a
                    href="{{ route('custom-quality-management', ['tab' => $item_sub_sidebar->tab, 'icon' => $item_sidebar->icon]) }}">
                    <span class="sub-item">{{ $item_sub_sidebar->name }}</span>
                    <span class="badge badge-success">{{ $item_sub_sidebar->jml_doc }}</span>
                </a>
            </li>
        @endcan
    @endforeach
    {{-- </ul>
        </div>
    </li> --}}
@endforeach
