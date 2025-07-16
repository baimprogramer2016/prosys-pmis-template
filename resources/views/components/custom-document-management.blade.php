@can('view_doc_management')
    @foreach ($sidebar_document_management as $item_sidebar)
        @foreach ($item_sidebar->r_child as $item_sub_sidebar)
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
        @endforeach

        {{--      

        <div class="collapse {{ $isActiveParent ? 'show' : '' }}" id="{{ $item_sidebar->id }}">
            <ul class="nav nav-collapse">
                @foreach ($item_sidebar->r_child as $item_sub_sidebar)
                    <li class="nav-item-custom  {{ request('tab') == $item_sub_sidebar->tab ? 'active' : '' }}">
                        <a href="{{ route('custom-document-management', ['tab' => $item_sub_sidebar->tab,'icon' => $item_sidebar->icon]) }}">
                            <span class="sub-item">{{ $item_sub_sidebar->name }}</span>
                            <span class="badge badge-success">{{ $item_sub_sidebar->jml_doc }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </li> --}}
    @endforeach
@endcan
