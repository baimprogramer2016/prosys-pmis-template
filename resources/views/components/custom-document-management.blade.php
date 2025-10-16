@foreach ($sidebar_document_management as $item_sidebar)
    @foreach ($item_sidebar->r_child as $item_sub_sidebar)
        @can('view_' . $item_sub_sidebar->permission)
            @php
                // Cek apakah salah satu sub-item sedang aktif berdasarkan tab di URL
                $isActiveParent = collect($item_sidebar->r_child)->contains(function ($subItem) {
                    return request('tab') == $subItem->tab;
                });
            @endphp
            <li class="nav-item-custom  {{ request('tab') == $item_sub_sidebar->tab ? 'active' : '' }}">
                <a
                    href="{{ route('custom-document-management', ['tab' => $item_sub_sidebar->tab, 'icon' => $item_sidebar->icon]) }}">
                    <i class="{{ $item_sidebar->icon }}"></i>
                    <p>{{ $item_sub_sidebar->name }}</p>
                    <span class="badge badge-success">{{ $item_sub_sidebar->jml_doc }}</span>
                </a>
            </li>
        @endcan
    @endforeach
@endforeach
