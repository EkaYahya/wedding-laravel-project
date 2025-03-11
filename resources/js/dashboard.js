document.addEventListener("DOMContentLoaded", function () {
    const searchForm = document.getElementById("search-form");
    const searchInput = document.getElementById("search-input");
    const tableBody = document.querySelector("#guests-table tbody");
    const searchInputModal = document.getElementById("search-input-modal");
    const modalTableBody = document.getElementById("search-results-body");
    if (!searchForm || !searchInput || !tableBody || !searchInputModal || !modalTableBody) {
        return;
    }

    function populateGuestsTable(data) {
        tableBody.innerHTML = "";
        data.guests.forEach((guest) => {
            const row = `
                <tr class="border-b hover:bg-primary-light/50 transition-colors duration-200">
                    <td class="py-2 px-4 text-primary-dark">${guest.name}</td>
                    <td class="py-2 px-4 flex justify-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                            guest.will_attend ? "bg-primary-light text-primary" : "bg-gray-100 text-gray-600"
                        }">
                            ${guest.will_attend ? "Hadir" : "Tidak"}
                        </span>
                    </td>
                    <td class="py-2 px-4 text-primary-dark">${guest.number_of_guests ?? "-"}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    function populateModalTable(data) {
        modalTableBody.innerHTML = "";
        data.guests.forEach((guest) => {
            const row = `
                <tr class="border-b hover:bg-primary-light/50 transition-colors duration-200">
                    <td class="py-2 px-4 text-primary-dark">${guest.name}</td>
                </tr>
            `;
            modalTableBody.innerHTML += row;
        });
    }

    function fetchGuests(query, target = "table") {
        fetch(`/api/guests?search=${encodeURIComponent(query)}`, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                if (target === "table") {
                    populateGuestsTable(data);
                } else if (target === "modal") {
                    populateModalTable(data);
                }
            })
            .catch((error) => console.error("Error:", error));
    }

    searchForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            fetchGuests(query, "table");
        }
    });

    // Fungsi debounce untuk pencarian modal
    function debounce(func, delay) {
        let timer;
        return function (...args) {
            clearTimeout(timer);
            timer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const handleSearchModal = debounce(function () {
        const query = searchInputModal.value.trim();
        if (query) {
            fetchGuests(query, "modal");
        } else {
            modalTableBody.innerHTML = ""; // Kosongkan tabel modal jika tidak ada query
        }
    }, 500);

    // Event untuk pencarian di modal
    searchInputModal.addEventListener("input", handleSearchModal);

});
