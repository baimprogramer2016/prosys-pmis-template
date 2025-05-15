<style>
    .profile-card {
        border: 1px solid #e0e0e0;
        border-radius: 0.5rem;
        overflow: hidden;
        width: 250px;
        flex-shrink: 0;
        margin-right: 1rem;
    }

    .profile-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .profile-body {
        padding: 1rem;

    }

    .scroll-container {
        display: flex;
        overflow-x: auto;
        padding-bottom: 1rem;

    }

    .scroll-container::-webkit-scrollbar {
        height: 8px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }
</style>
<div class="col-md-12">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="card-title">Photograpich</div>
            <div class="d-flex align-items-center">
                <label for="start_date" class="me-2 mb-0">Tanggal Awal:</label>
                <input type="date" id="start_date_photographic" class="form-control form-control-sm me-3"
                    style="width: 150px;">

                <label for="end_date" class="me-2 mb-0">Tanggal Akhir:</label>
                <input type="date" id="end_date_photographic" class="form-control form-control-sm me-3"
                    style="width: 150px;">

                <button class="btn btn-primary btn-sm" id="filterBtnPhotographic">Filter</button>
            </div>
        </div>
        <div class="card-body row">
            <div class="container py-4">
                <div class="scroll-container d-flex justify-content-center " id="imageScroller">
                    <!-- Card Start -->
                    @foreach ($data_photographics as $item_photographic)
                        <div class="profile-card d-flex flex-column" style="height: 100%;">
                            <img src="{{ asset('storage/' . $item_photographic->path) }}" class="profile-img"
                                alt="Profile Photo">
                            <div class="profile-body d-flex flex-column flex-grow-1 ">
                                <p class="profile-desc mb-2">{{ $item_photographic->description }}</p>
                                <i class="far fa-eye" style="color: rgb(137, 135, 135); cursor: pointer"
                                    data-bs-toggle="modal" data-bs-target="#modal"
                                    onClick="return viewImage({{ $item_photographic }})"></i>
                            </div>
                        </div>
                    @endforeach

                    <!-- Tambahkan lebih banyak card di sini -->
                </div>
            </div>
        </div>
    </div>
</div>
{{-- storage/rkef/8vTGiIpEHfljroJKJY9NqlCtChPQhfWrtqfdXMPV.jpg' --}}

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen"> <!-- Tambahkan modal-lg di sini -->
        <div class="modal-content" style="padding: 0;">
            <div class="modal-body p-0">
                <img id="pdfIframe" style="height: 100%; width: auto;" />
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <p>
                        <b><u>Description </u>:</b> <span id="description-modal"></span>
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('bottom')
    <script>
        function viewImage(param) {
            document.getElementById('pdfIframe').src = "/storage/" + param.path;
            document.getElementById('description-modal').innerHTML = param.description
        }


        const scroller = document.getElementById('imageScroller');
        let scrollInterval;
        let isScrolling = true;

        function startScroll() {
            scrollInterval = setInterval(() => {
                if (scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth) {
                    scroller.scrollLeft = 0; // Kembali ke awal saat mentok
                } else {
                    scroller.scrollLeft += 1; // Scroll ke kanan
                }
            }, 20); // Kecepatan scroll
        }

        function stopScroll() {
            clearInterval(scrollInterval);
        }

        // Mulai scroll otomatis
        startScroll();

        // Toggle scroll saat container diklik
        scroller.addEventListener('click', () => {
            if (isScrolling) {
                stopScroll();
            } else {
                startScroll();
            }
            isScrolling = !isScrolling;
        });


        $("#filterBtnPhotographic").click(function() {
            start_date_p = document.getElementById('start_date_photographic').value;
            end_date_p = document.getElementById('end_date_photographic').value;
            imageScrollerContainer = document.getElementById('imageScroller');

            if (start_date_p == "" || end_date_p == "") {
                alert("Tanggal tidak boleh kosong");
                return
            }


            $.ajax({
                url: "{{ route('dashboard-new-image') }}",
                data: {
                    start_date: start_date_p,
                    end_date: end_date_p,
                },
                method: "GET",
                success: function(response) {

                    if (response.length == 0) {
                        alert('Data tidak ditemukan')
                        return
                    }

                    imageScrollerContainer.innerHTML = "";

                    response.forEach((item) => {
                        createImageElement(item.description, item.path)
                    })

                }
            })
        })

        function createImageElement(description, path) {
            imageScrollerContainer = document.getElementById('imageScroller');
            imageScrollerContainer.innerHTML += `
            <div class="profile-card d-flex flex-column" style="height: 100%;">
                <img src="/storage/${path}" class="profile-img" alt="Profile Photo">
                <div class="profile-body d-flex flex-column flex-grow-1">
                    <p class="profile-desc mb-2">${description}</p>
                    <i class="far fa-eye" style="color: rgb(137, 135, 135); cursor: pointer"
                        data-bs-toggle="modal" data-bs-target="#modal"
                        onClick="return viewImage({description:'${description}',path:'${path}'})"></i>
                </div>
            </div>
            `
        }
    </script>
@endpush
