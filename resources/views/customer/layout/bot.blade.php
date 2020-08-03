<!-- Plugins JS -->
<script src="{{asset('assets/customer/js/plugins.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('assets/customer/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
 
<script type="text/javascript">
    $(document).ready(function () {
            @if(Session:: has('fail'))
{{--        thư viện hổ trợ hiển thị mockup    --}}
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        Toast.fire({
            type: 'error',
            title: '{{ Session:: get('fail') }}'
        });
            @endif
            @if(Session:: has('success'))
        const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        Toast.fire({
            type: 'success',
            title: '{{ Session:: get('success') }}'
        });
        @endif
        @if(Session:: has('login'))
        Swal.fire({
            type: 'success',
            title: '{{ Session:: get('login') }}',
            showConfirmButton: false,
        });
        @endif
    });
</script>
