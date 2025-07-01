// SweetAlert2 for delete confirmation
// This script will handle the delete confirmation using SweetAlert2
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-delete").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const url = btn.getAttribute("data-url");
            Swal.fire({
                title: "Yakin ingin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika ingin submit form, bisa submit form di sini
                    // Atau redirect ke url
                    window.location.href = url;
                }
            });
        });
    });
});

// Select2 initialization for the modal
// This script will initialize Select2 on the select elements inside the modal
$(document).on("shown.bs.modal", ".modal", function () {
    $(this)
        .find(".select2")
        .select2({
            dropdownParent: $(this),
        });
});
