@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let formChanged = false;
            const form = document.querySelector("form");

            if (form) {
                form.addEventListener("change", () => {
                    formChanged = true;
                });

                form.addEventListener("submit", () => {
                    formChanged = false;
                });

                window.addEventListener("beforeunload", function (e) {
                    if (formChanged) {
                        e.preventDefault();
                        e.returnValue = "Perubahan Anda belum disimpan. Apakah yakin ingin keluar?";
                    }
                });
            }
        });
    </script>
@endpush
